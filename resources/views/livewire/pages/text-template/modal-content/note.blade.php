<?php
use function Livewire\Volt\{placeholder};

placeholder('components.loading');

?>
<div>
    <p class="font-weight-bold">{{ __('label.note')}}</p>
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td class="px-1" width="25"><b>{{ __('[[TF_SENDER_NAME]]') }}</b></td>
                <td class="px-1" width="5">:</td>
                <td class="px-1">{{ __('text-template.label_tf_sender_name') }}</td>
            </tr>
            <tr>
                <td class="px-1" width="25"><b>{{ __('[[TF_SENDER_ACCOUNT]]') }}</b></td>
                <td class="px-1" width="5">:</td>
                <td class="px-1">{{ __('text-template.label_tf_sender_account') }}</td>
            </tr>
            <tr>
                <td class="px-1" width="25"><b>{{ __('[[TF_RECIPIENT_NAME]]') }}</b></td>
                <td class="px-1" width="5">:</td>
                <td class="px-1">{{ __('text-template.label_tf_recipient_name') }}</td>
            </tr>
            <tr>
                <td class="px-1" width="25"><b>{{ __('[[TF_RECIPIENT_ACCOUNT]]') }}</b></td>
                <td class="px-1" width="5">:</td>
                <td class="px-1">{{ __('text-template.label_tf_recipient_account') }}</td>
            </tr>
            <tr>
                <td class="px-1" width="25"><b>{{ __('[[TF_AMOUNT]]') }}</b></td>
                <td class="px-1" width="5">:</td>
                <td class="px-1">{{ __('text-template.label_tf_amount') }}</td>
            </tr>
            <tr>
                <td class="px-1" width="25"><b>{{ __('[[TF_DATETIME]]') }}</b></td>
                <td class="px-1" width="5">:</td>
                <td class="px-1">{{ __('text-template.label_tf_datetime') }}</td>
            </tr>
            <tr>
                <td class="px-1" width="25"><b>{{ __('[[TF_DATE]]') }}</b></td>
                <td class="px-1" width="5">:</td>
                <td class="px-1">{{ __('text-template.label_tf_date') }}</td>
            </tr>
            <tr>
                <td class="px-1" width="25"><b>{{ __('[[TF_TIME]]') }}</b></td>
                <td class="px-1" width="5">:</td>
                <td class="px-1">{{ __('text-template.label_tf_time') }}</td>
            </tr>
        </tbody>
    </table>
</div>