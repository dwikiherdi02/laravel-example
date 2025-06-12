<?php

use function Livewire\Volt\{state};

state(['item']);

?>

<div>
    @foreach ( $item->childs as $key => $child)
        <tr>
            <td class="fs-6 text-truncate font-weight-bolder @if($key != 0) border-top @endif" colspan="2">
                {{ format_month_year($child->duesMonth->month, $child->duesMonth->year) }}
            </td>
        </tr>
        @foreach ($child->detail as $detail)
        <tr>
            <td class="fs-7 text-truncate font-weight-bolder">
                {{ $detail->contribution->name }}
            </td>
            <td class="fs-6 text-success text-right font-weight-bolder">
                <span class="fs-8 opacity-5">Rp</span>
                {{ number_format($detail->amount, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach
    @endforeach
    <tr>
        <td class="fs-7 text-truncate font-weight-bolder border-top">
            {{ __('Kode Unik') }}
        </td>
        <td class="fs-6 text-primary text-right font-weight-bolder border-top">
            {{ $item->unique_code }}
        </td>
    </tr>
    <tr>
        <td class="fs-5 text-truncate font-weight-bolder border-top">
            {{ __('Total') }}
        </td>
        <td class="fs-4 text-success text-right font-weight-bolder border-top">
            <span class="fs-6 opacity-5">Rp</span>
            @php
                $finalAmount = number_format($item->final_amount, 0, ',', '.');
                $uniqueCode = $item->unique_code;

                $pos = strrpos($finalAmount, $uniqueCode);

                if ($pos !== false) {
                    $finalAmount = substr_replace(
                        $finalAmount, 
                        '<span class="text-primary">' . $uniqueCode . '</span>', 
                        $pos,
                        strlen($uniqueCode)); 
                }

            @endphp
            {!! $finalAmount !!}
        </td>
    </tr>
</div>