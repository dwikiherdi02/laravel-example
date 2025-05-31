<?php

namespace App\Libraries;

use App\Repositories\ImapRepository;
use Crypt;
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
            /*  $folder = $client->getFolder('INBOX');

            // $messages = $folder->messages()->all()->limit(1)->get();

            // Ambil email yang belum dibaca dan datang hari ini
            $messages = $folder->messages()
                            ->unseen() // Belum dibaca
                            ->on(Carbon::today()) // Hari ini
                            // ->since($start->toDateTimeString())                // Dari awal hari
                            // ->before($end->addSecond()->toDateTimeString())    // Sampai sekarang
                            ->subject('Contoh Uang Masuk')
                            // ->from('dwikiherdi520@gmail.com')
                            ->get();

            $data = [];

            foreach ($messages as $message) {
                $data[] = [
                    'subject' => $message->getSubject()[0],
                    'from'    => $message->getFrom()[0]->mail ?? '',
                    'date'    => $message->getDate()[0]->toDateTimeString(),
                    'body'    => $message->getTextBody()
                    // 'body'    => $message->getHTMLBody()
                ];

                // Tandai sebagai sudah dibaca
                $message->setFlag('SEEN');
             } */
            return true;
        } catch (\Exception $e) {
            // throw new \Exception('Gagal terhubung ke server IMAP: ' . $e->getMessage());
            throw new \Exception(trans('imap.error_check_connection', ['message' => $e->getMessage()]));
        } finally {
            $client->disconnect();
        }
    }

    public function generateBodyMail(string $email = null, string $subject = null)
    {
        if ($email == null || $subject == null) {
            throw new \Exception(trans('Pastikan email dan subjek tidak boleh kosong'));
        }

        $client = $this->getClient();

        try {
            $client->connect();
            $folder = $client->getFolder('INBOX');

            $message = $folder->messages()
                ->from($email)
                ->subject($subject)
                ->limit(1)
                ->fetchOrderDesc() // Ambil email terbaru
                ->get()
                ->first();

            if ($message) {
                $body = html_to_text($message->getHtmlBody());
                return $body;
            } else {
                return throw new \Exception(trans('Email tidak ditemukan'));
            }
        } catch (\Exception $e) {
            // throw new \Exception('Gagal terhubung ke server IMAP: ' . $e->getMessage());
            throw new \Exception(trans('imap.error_check_connection', ['message' => $e->getMessage()]));
        } finally {
            $client->disconnect();
        }
    }
}
