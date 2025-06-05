<?php

namespace App\Services;

use App\Dto\DuesMonthDto;
use App\Dto\DuesPaymentDetailDto;
use App\Dto\DuesPaymentDto;
use App\Repositories\DuesMonthRepository;
use App\Repositories\DuesPaymentDetailRepository;
use App\Repositories\DuesPaymentRepository;
use App\Repositories\ResidentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Str;

class DuesMonthService
{
    function __construct(
        protected DuesMonthRepository $duesMonthRepo,
        protected DuesPaymentDetailRepository $duesPaymentDetailRepo,
        protected DuesPaymentRepository $duesPaymentRepo,
        protected ResidentRepository $residentRepo
    ) { }

    public function create(DuesMonthDto $data)
    {
        try {
            // Cek format mm-YYYY
            $date = Carbon::createFromFormat('m-Y', $data->dues_date);
            // Jika ingin memastikan format persis, cek juga dengan format yang sama
            if ($date && $date->format('m-Y') === $data->dues_date) {
                $data->year = (int) $date->format('Y');
                $data->month = (int) $date->format('m'); // Hilangkan angka 0 di depan
            } else {
                throw ValidationException::withMessages([
                    'dues_date' => trans('dues_month.error_label_wrong_dues_date_format'),
                ]);
            }
        } catch (\Exception $e) {
            throw new \Exception(trans('dues_month.error_process_parse_dues_date'));
        }
    
        if (empty($data->contribution_ids)) {
            throw new \Exception(trans('contribution.error_no_contribution_selected'));
        }
        
        if ($this->duesMonthRepo->findByYearAndMonth($data->year, $data->month)) {
            // throw new \Exception('Tanggal iuran sudah ada');
            throw ValidationException::withMessages([
                'dues_date' => trans('dues_month.error_label_dues_date_exist'),
            ]);
        }

        DB::beginTransaction();
        try {
            $now = Carbon::now();
            // simpan data dues_month
            $duesMonth = $this->duesMonthRepo->create($data->toArray());
            $contributions = $duesMonth->contributions;

            // simpan data dues_payments
            $resindents = $this->residentRepo->getAll();
            $payments = [];

            foreach ($resindents as $resindent) {
                $payments[] = DuesPaymentDto::from([
                    'id' => Str::uuid7(),
                    'resident_id' => $resindent->id,
                    'dues_month_id' => $duesMonth->id,
                    'parent_id' => null,
                    'base_amount' => $contributions->sum('amount'),
                    'unique_code' => $resindent->unique_code,
                    'final_amount' => array_sum([$contributions->sum('amount'), $resindent->unique_code]),
                    'is_paid' => false,
                    'is_merge' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->toArray();
            }

            $this->duesPaymentRepo->createMany($payments);

            // simpan data dues_payment_details
            $paymentDetails = [];
            foreach ($payments as $payment) {
                $payment = (object) $payment;
                foreach ($contributions as $contribution) {
                    $paymentDetails[] = DuesPaymentDetailDto::from([
                        'id' => Str::uuid7(),
                        'dues_payment_id' => $payment->id,
                        'contribution_id' => $contribution->id,
                        'amount' => $contribution->amount,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->toArray();
                }
            }

            $this->duesPaymentDetailRepo->createMany($paymentDetails);

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('label.error_save'));
        }

    }
}