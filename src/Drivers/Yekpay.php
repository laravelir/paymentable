<?php

namespace Laravelir\Paymentable\Drivers;

use SoapClient;

final class YekpayPaymentService
{

    public static function request()
    {
        /* Currency Codes
        978 = EUR
        364 = IRR
        784 = AED
        826 = GBP
        949 = TRY
        */

        try {
            $client = new SoapClient('https://gate.yekpay.com/api/payment/server?wsdl', array('encoding' => 'UTF-8'));

            $result = $client->request((object)array(
                'merchantId'       => 'XZE2DA9AX49F5K5BH75KN4J24AB77PW6',
                'fromCurrencyCode' => 364, // IRR
                'toCurrencyCode'   => 978, // EUR
                'email'            => 'mail@server.com',
                'mobile'           => '09123456789',
                'firstName'        => 'Name',
                'lastName'         => 'Family',
                'address'          => 'No.1, Second.St, Third.Sq',
                'postalCode'       => '1234567890',
                'country'          => 'Iran',
                'city'             => 'Tehran',
                'description'      => 'Payment Description',
                'amount'           => number_format(1000000, 2)
            ));

            if ($result->Code == 100) {
                $paymentURL = 'https://gate.yekpay.com/api/payment/start/' . $result->Authority;
                return redirect()->to($paymentURL);
            } else {
                echo ('YekPay Error : ' . $result->Description);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}



// $result = $client->request($p = (object)array(
//     'merchantId'       => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456',
//     'fromCurrencyCode' => 364, // IRR
//     'toCurrencyCode'   => 978, // EUR
//     'email'            => 'mail@server.com',
//     'mobile'           => '09123456789',
//     'firstName'        => 'Name',
//     'lastName'         => 'Family',
//     'address'          => 'No.1, Second.St, Third.Sq',
//     'postalCode'       => '1234567890',
//     'country'          => 'Iran',
//     'city'             => 'Tehran',
//     'description'      => 'Payment Description',
//     'amount'           => number_format(1000000,2)); // it means the price is 1.000.000 IRR in our site , and we want to pay the invoice with euro (about 6-7 euro)
//     // 'orderNumberPHP CODEecode($result);
