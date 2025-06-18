<?php

namespace App\Libraries;

use App\Dto\Lib\Imap\CollectionDto;
use App\Dto\Lib\Imap\FilterDto;
use App\Repositories\ImapRepository;
use Carbon\Carbon;
use Crypt;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;

class Imap
{
    public function __construct(
        protected ClientManager $clientManager,
        protected ImapRepository $imapRepo,
    ) {
        //
    }

    public function getClient()
    {
        $imap = $this->imapRepo->get();
        if ($imap === null) {
            throw new \Exception(trans('imap.error_imap_config_notfound'));
        }
        return $this->clientManager->make([
            'host' => $imap->host,
            'port' => $imap->port,
            'protocol' => $imap->protocol,
            'encryption' => $imap->encryption,
            'validate_cert' => $imap->validate_cert,
            'username' => Crypt::decryptString($imap->username),
            'password' => Crypt::decryptString($imap->password),
            'authentication' => $imap->authentication,
        ]);
    }

    public function checkImapConnection()
    {
        $client = $this->getClient();
        try {
            $client->connect();
            return true;
        } catch (\Exception $e) {
            // throw new \Exception('Gagal terhubung ke server IMAP: ' . $e->getMessage());
            throw new \Exception(trans('imap.error_check_connection', ['message' => $e->getMessage()]));
        } finally {
            $client->disconnect();
        }
    }

    public function generateTemplate(string $email = null, string $subject = null)
    {
        if ($email == null || $subject == null) {
            throw new \Exception(trans('imap.error_required_email_and_subject'));
        }

        try {
            $content = $this->first(
                FilterDto::from([
                    'folder'  => 'INBOX',
                    'from'  => $email,
                    'subject'  => $subject,
                    'limit'  => 1,
                    'isOrderDesc'  => true
                ])
            );

            if ($content) {
                $body = html_to_text($content->body);
                return $body;
            } else {
                return throw new \Exception(trans('imap.error_email_not_found'));
            }
        } catch (\Exception $e) {
            // throw new \Exception('Gagal terhubung ke server IMAP: ' . $e->getMessage());
            throw new \Exception(trans('imap.error_check_connection', ['message' => $e->getMessage()]));
        }
    }

    public function setConfiguration(Client $client, FilterDto $filter) {
        $folder = $client->getFolder($filter->folder ?? 'INBOX');

        $conf = $folder->messages();

        if ($filter->from != null) {
            $conf = $conf->from($filter->from);
        }

        if ($filter->subject != null) {
            $conf = $conf->subject($filter->subject);
        }

        if ($filter->unseen) {
            $conf = $conf->unseen();
        }

        if ($filter->onToday) {
            $conf = $conf->on(Carbon::today());
        }

        if ($filter->isOrderDesc) {
            $conf = $conf->fetchOrderDesc();
        }

        if ($filter->limit && $filter->limit > 0) {
            $conf = $conf->limit($filter->limit);
        }

        return $conf;
    }

    public function get(FilterDto $filter): array {
        $client = $this->getClient();

        try {
            $client->connect();
        
            $messages = $this->setConfiguration($client, $filter)->get();

            $data = [];
            
            foreach ($messages as $message) {
                /* $data[] = [
                    'subject' => $message->getSubject()[0],
                    'from'    => $message->getFrom()[0]->mail ?? '',
                    'date'    => $message->getDate()[0]->toDateTimeString(),
                    'body'    => $message->getHTMLBody(),
                    'text'    => $message->getTextBody(),
                ]; */

                $data[] = CollectionDto::from(
                    body: $message->getHTMLBody(),
                    text: $message->getTextBody(),
                    date: $message->getDate()[0]->toDateTimeString()
                );

                // Tandai sebagai sudah dibaca
                if ($filter->setFlagToSeen) {
                    $message->setFlag('SEEN');
                }
            }

            return $data;
        } catch (\Exception $e) {
            // throw new \Exception('Gagal terhubung ke server IMAP: ' . $e->getMessage());
            throw new \Exception(trans('imap.error_check_connection', ['message' => $e->getMessage()]));
        } finally {
            $client->disconnect();
        }
    }

    public function first(FilterDto $filter): ?CollectionDto {
        $client = $this->getClient();

        try {
            $client->connect();
        
            $message = $this->setConfiguration($client, $filter)->get()->first();

            if ($message) {
                return new CollectionDto(
                    body: $message->getHTMLBody(),
                    text: $message->getTextBody(),
                    date: $message->getDate()[0]->toDateTimeString()
                );
            } else {
                return null;
            }

        } catch (\Exception $e) {
            // throw new \Exception('Gagal terhubung ke server IMAP: ' . $e->getMessage());
            throw new \Exception(trans('imap.error_check_connection', ['message' => $e->getMessage()]));
        } finally {
            $client->disconnect();
        }
    }
}
