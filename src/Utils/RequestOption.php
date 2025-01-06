<?php

namespace Coinremitter\Utils;

class RequestOption
{
    public static function setRequestParam($function,$param): array
    {
        if($function == 'createAddress'){
            return array(
                'label' => request()->label ?? '',
            );
        }else if($function == 'validateAddress'){
            return array(
                'address' => request()->address ?? '',
            );
        }else if($function == 'estimateWithdraw'){
            return array(
                'address' => request()->address ?? '',
                'amount' => request()->amount ?? '',
                'withdrawal_speed' => request()->withdrawal_speed ?? '',
            );
        }else if($function == 'withdraw'){
            return array(
                'address' => request()->address ?? '',
                'amount' => request()->amount ?? '',
                'withdrawal_speed' => request()->withdrawal_speed ?? '',
            );
        }else if($function == 'getTransaction'){
            return array(
                'id' => request()->id ?? '',
            );
        }else if($function == 'getTransactionByAddress'){
            return array(
                'address' => request()->address ?? '',
            );
        }else if($function == 'createInvoice'){
            return array(
                'amount' => request()->amount ?? '',
                'name' => request()->name ?? '',
                'email' => request()->email ?? '',
                'fiat_currency' => request()->fiat_currency ?? '',
                'expiry_time_in_minutes' => request()->expiry_time_in_minutes ?? '',
                'notify_url' => request()->notify_url ?? '',
                'success_url' => request()->success_url ?? '',
                'fail_url' => request()->fail_url ?? '',
                'description' => request()->description ?? '',
                'custom_data1' => request()->custom_data1 ?? '',
                'custom_data2' => request()->custom_data2 ?? '',
            );
        }else if($function == 'getInvoice'){
            return array(
                'invoice_id' => request()->invoice_id ?? '',
            );
        }else if($function == 'fiatToCryptoRate'){
            return array(
                'fiat' => request()->fiat ?? '',
                'fiat_amount' => request()->fiat_amount ?? '',
                'crypto' => request()->crypto ?? '',
            );
        }else if($function == 'cryptoToFiatRate'){
            return array(
                'crypto' => request()->crypto ?? '',
                'crypto_amount' => request()->crypto_amount ?? '',
                'fiat' => request()->fiat ?? '',
            );
        }else {
            return array();
        }
    }
}
