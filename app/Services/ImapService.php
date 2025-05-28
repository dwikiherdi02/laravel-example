<?php

namespace App\Services;

use App\Dto\ImapDto;
use App\Repositories\ImapRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Webklex\PHPIMAP\ClientManager;

class ImapService
{
    function __construct(
        protected ImapRepository $imapRepo,
    ) {
        //
    }

    public function get(): ?ImapDto
    {
        $item = $this->imapRepo->get();
        if($item === null) {
            return null;
        }
        return ImapDto::from($item);
    }

    public function save(ImapDto $data): ImapDto
    {
        $item = $this->imapRepo->get();

        DB::beginTransaction();
        try {
            
            if ($data->username != null) {
                $data->username = Crypt::encryptString($data->username);
            }

            if ($data->password != null) {
                $data->password = Crypt::encryptString($data->password);
            }
            
            if ($item) {
                $item->host = $data->host; 
                $item->port = $data->port; 
                $item->protocol = $data->protocol; 
                $item->encryption = $data->encryption; 
                $item->validate_cert = $data->validate_cert; 
                $item->username = $data->username;
                $item->password = $data->password; 
                $item->authentication = $data->authentication;

                // dd($item);
                
                $item->save();
            } else {
                $item = $this->imapRepo->create($data->toArray());
            }

            DB::commit();
            return ImapDto::from($item);
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('label.error_save'));
        }
    }

    public function checkImapConnection() 
    {
        $imap = $this->imapRepo->get();
        if ($imap === null) {
            throw new \Exception('IMAP configuration not found.');
        }

        try {
            // Buat konfigurasi IMAP dinamis
            $clientManager = new ClientManager();

            $client = $clientManager->make([
                'host' => $imap->host,
                'port' => $imap->port,
                'protocol' => $imap->protocol,
                'encryption' => $imap->encryption,
                'validate_cert' => $imap->validate_cert,
                'username' => Crypt::decryptString($imap->username),
                'password' => Crypt::decryptString($imap->password),
                'authentication' => $imap->authentication,
            ]);

            // Coba koneksi
            $client->connect();

            $folder = $client->getFolder('INBOX');
            
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
            }
            
            // Jika berhasil, disconnect & return true
            $client->disconnect();
            
            dd($data);

            return true;

        } catch (\Exception $e) {
            throw new \Exception('Gagal terhubung ke server IMAP: ' . $e->getMessage());
        }
    }
}