<?php

namespace Laravelir\Paymentable\Drivers;

class IDPay
{
    params = array(
        'order_id' => '101',
        'amount' => 10000,
        'phone' => '09382198592',
        'name' => 'نام پرداخت کننده',
        'desc' => 'توضیحات پرداخت کننده',
        'callback' => URL_CALLBACK,
      );

      idpay_payment_create($params);


      /**
       * @param array $params
       * @return bool
       */
      function idpay_payment_create($params) {
          $header = array(
          'Content-Type: application/json',
          'X-API-KEY:' . APIKEY,
          'X-SANDBOX:' . SANDBOX,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, URL_PAYMENT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);

        if (empty($result) || empty($result->link)) {

          print 'Exception message:';
          print '<pre>';
          print_r($result);
          print '</pre>';

          return FALSE;
        }

        //.Redirect to payment form
        header('Location:' . $result->link);
      }


      //////////////////////
    public function payment()
    {
        $params = array(
            'order_id' => '101',
            'amount' => 10000,
            'name' => 'قاسم رادمان',
            'phone' => '09382198592',
            'mail' => 'my@site.com',
            'desc' => 'توضیحات پرداخت کننده',
            'callback' => 'https://example.com/callback',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: 6a7f99eb-7c20-4412-a972-6dfb7cd253a4',
            'X-SANDBOX: 1'
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        var_dump($result);
    }

    public function verify()
    {
        $params = array(
            'id' => 'd2e353189823079e1e4181772cff5292',
            'order_id' => '101',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment/verify');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: 6a7f99eb-7c20-4412-a972-6dfb7cd253a4',
            'X-SANDBOX: 1',
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        var_dump($result);
    }

    public function get_status_description($status)
    {
        switch ($status) {
            case 1:
                $this->msg = 'پرداخت انجام نشده است.  ';
                break;
            case 2:
                $this->msg = 'پرداخت ناموفق بوده است.';
                break;
            case 3:
                $this->msg = 'خطا رخ داده است.';
                break;
            case 4:
                $this->msg = 'بلوکه شده.';
                break;
            case 5:
                $this->msg = 'برگشت به پرداخت کننده.';
                break;
            case 6:
                $this->msg = 'برگشت خورده سیستمی.';
                break;
            case 7:
                $this->msg = 'انصراف از پرداخت.';
                break;
            case 8:
                $this->msg = 'به درگاه پرداخت منتقل شد.';
                break;
            case 10:
                $this->msg = 'در انتظار تایید پرداخت.';
                break;
            case 100:
                $this->msg = 'پرداخت تایید شده است.';
                break;
            case 101:
                $this->msg = 'پرداخت قبلا تایید شده است.';
                break;

            case 200:
                $this->msg = 'به دریافت کننده واریز شد.';
                break;
            case 405:
                $this->msg = 'تایید پرداخت امکان پذیر نیست.';
                break;
        }
    }

    function idpay_payment_get_inquiry($response) {

        define('URL_CALLBACK', 'http://idpay-payment.local/callback.php');

define('URL_PAYMENT', 'https://api.idpay.ir/v1.1/payment');
define('URL_INQUIRY', 'https://api.idpay.ir/v1.1/payment/inquiry');
define('URL_VERIFY', 'https://api.idpay.ir/v1.1/payment/verify');

define('APIKEY', '152a58fe-ac82-49e1-9bf3-07ec9305834f');
define('SANDBOX', TRUE);

        $header = array(
          'Content-Type: application/json',
          'X-API-KEY:' . APIKEY,
          'X-SANDBOX:' . SANDBOX,
        );

        $params = array(
          'id' => $response['id'],
          'order_id' => $response['order_id'],
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, URL_INQUIRY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);

        if (empty($result) ||
            empty($result->status)) {

          print 'Exception message:';
          print '<pre>';
          print_r($result);
          print '</pre>';

          return FALSE;
        }

        if ($result->status == 10) {
          return TRUE;
        }

        print idpay_payment_get_message($result->status);

        return FALSE;
      }


      /**
       * @param array $response
       * @return bool
       */
      function idpay_payment_verify($response) {

        $header = array(
          'Content-Type: application/json',
          'X-API-KEY:' . APIKEY,
          'X-SANDBOX:' . SANDBOX,
        );

        $params = array(
          'id' => $response['id'],
          'order_id' => $response['order_id'],
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, URL_VERIFY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);

        if (empty($result) ||
            empty($result->status)) {

          print 'Exception message:';
          print '<pre>';
          print_r($result);
          print '</pre>';

          return FALSE;
        }

        print idpay_payment_get_message($result->status);

        print '<pre>';
        print_r($result);
        print '</pre>';
      }

      /**
       * @param int $status
       * @return string
       */
      function idpay_payment_get_message($status) {

        switch ($status) {
          case 1:
            return 'پرداخت انجام نشده است';

          case 2:
            return 'پرداخت ناموفق بوده است';

          case 3:
            return 'خطا رخ داده است';

          case 10:
            return 'در انتظار تایید پرداخت';

          case 100:
            return 'پرداخت تایید شده است';

          case 101:
            return 'پرداخت قبلاً تایید شده است';

          default:
            return 'Error handeling';
        }
}
