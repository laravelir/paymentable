<?php

namespace Laravelir\Paymentable\Drivers\Zarinpal;

use Illuminate\Support\Facades\Http;
use Laravelir\Paymentable\Drivers\Driver;

class Zarinpal
{

    public function getAliasDataFields(): array
    {
        return [
            'merchantId' => 'MerchantID',
            'amount' => 'Amount',
            'transactionId' => 'Authority',
            'callback' => 'CallbackURL',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'description' => 'Description',
        ];
    }

    public function payment($amount)
    {
        $MerchantID = '61187f93-e161-4b65-917a-47c91b3aa54c'; //Required
        $Amount = $amount; //Amount will be based on Toman - Required
        $Description = 'شارژ کیف پول'; // Required
        $Email = $user->email; // Optional
        $Mobile = $user->phone; // Optional
        $CallbackURL = url('/account/wallet/payment/callback'); // Required

        // $result = Http::get('url');
        $client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

        $result = $client->PaymentRequest(
            [
                'MerchantID' => $MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'Email' => $Email,
                'Mobile' => $Mobile,
                'CallbackURL' => $CallbackURL,
            ]
        );

        if ($result->Status === 100) {
            auth()->user()->payments()->create([
                'resnumber' =>  $result->Authority,
                'amount' => $amount,
                'type'     => 'شارژ کیف پول'
            ]);
            // return "{$this->baseUrl}/pg/StartPay/{$this->transactionId()}{$gateway}";

            return redirect('https://sandbox.zarinpal.com/pg/StartPay/' . $result->Authority);
        } else {
            echo 'ERR ' . $result->Status;
        }
    }

    public function callback(Request $request)
    {
        $MerchantID = '61187f93-e161-4b65-917a-47c91b3aa54c';
        $Authority = $request->get('Authority');
        $user = auth()->user();

        $payment = Payment::where('resnumber', $Authority)->first();

        if ($request->Status === 'OK') {

            $client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

            $result = $client->PaymentVerification(
                [
                    'MerchantID' => $MerchantID,
                    'Authority' => $Authority,
                    'Amount' => $payment->amount,
                ]
            );

            if ($result->Status == 100) {

                if ($this->chargeWalletAmount($user, $payment->amount)) {
                    $payment->update([
                        'payment' => true
                    ]);
                    alert()->success('Transation success. RefID:' . $result->RefID, 'fsd');
                    return back();
                }
            } else {
                alert()->error('Transation failed. Status:' .  $result->Status);
                return back();
            }
        } else {
            alert()->error('Transaction canceled by use', 'Transaction canceled by use');
            return back();
        }
    }

    const INCOMPLETE_DATA = -1;
    const WRONG_IP_OR_MERCHANT_ID = -2;
    const SHAPARAK_LIMITED = -3;
    const INSUFFICIENT_USER_LEVEL = -4;
    const REQUEST_NOT_FOUND = -11;
    const UNABLE_TO_EDIT_REQUEST = -12;
    const NO_FINANCIAL_OPERATION = -21;
    const FAILED_TRANSACTION = -22;
    const AMOUNTS_NOT_EQUAL = -33;
    const TRANSACTION_SPLITTING_LIMITED = -34;
    const METHOD_ACCESS_DENIED = -40;
    const INVALID_ADDITIONAL_DATA = -41;
    const INVALID_EXPIRATION_RANGE = -42;
    const REQUEST_ARCHIVED = -54;
    const OPERATION_SUCCEED = 100;
    const ALREADY_VERIFIED = 101;

    // Package related
    const NOT_PAID = 9000;
    const UNEXPECTED = 9001;

    public static function toMessage($status)
    {
        $translationKey = strtolower(self::getConstantName($status)) ?: $status;

        return __("toman::zarinpal.status.$translationKey");
    }

    protected static function getConstantName($value)
    {
        $constants = array_flip((new ReflectionClass(static::class))->getConstants());

        return Arr::get($constants, $value);
    }
}
