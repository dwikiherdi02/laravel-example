<?php

namespace App\Services;

use App\Dto\ImapDto;
use App\Libraries\Imap;
use App\Repositories\ImapRepository;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Webklex\PHPIMAP\ClientManager;

class ImapService
{
    function __construct(
        protected Imap $imapLib,
        protected ImapRepository $imapRepo,
    ) {
        //
    }

    public function get(): ?ImapDto
    {
        $item = $this->imapRepo->get();
        if ($item === null) {
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
        try {
            $this->imapLib->checkImapConnection();       
            return true;

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
