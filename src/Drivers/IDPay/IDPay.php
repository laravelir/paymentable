<?php

namespace Laravelir\Paymentable\Drivers;

class IDPay
{

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
        $result = json_decode($result);
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

        private ?string $paymentUrl = null;

        public function purchase(): string
        {
            $purchaseData = $this->getPurchaseData();

            $response = $this->callApi($this->getPurchaseUrl(), $purchaseData);

            if (isset($response['error_code'])) {
                $message = $response['error_message'] ?? $this->getStatusMessage($response['error_code']);

                throw new PurchaseFailedException($message, $response['error_code'], $purchaseData);
            }

            $this->getInvoice()->setTransactionId($response['id']);

            $this->setPaymentUrl($response['link']);

            return $response['id'];
        }

        public function pay(): RedirectionForm
        {
            return $this->redirect($this->getPaymentUrl(), [], 'GET');
        }

        public function verify(): Receipt
        {
            $status = (int) request('status');

            if (! in_array($status, [
                $this->getPendingVerificationStatusCode(),
                $this->getPaymentAlreadyVerifiedStatusCode(),
                $this->getSuccessResponseStatusCode(),
            ], true)
            ) {
                throw new PaymentFailedException($this->getStatusMessage($status), $status);
            }

            $response = $this->callApi($this->getVerificationUrl(), $this->getVerificationData());

            if (isset($response['error_code'])) {
                $message = $response['error_message'] ?? $this->getStatusMessage($response['error_code']);

                throw new PaymentFailedException($message, $response['error_code']);
            }

            $status = (int) $response['status'];

            if ($status === $this->getPaymentAlreadyVerifiedStatusCode()) {
                throw new PaymentAlreadyVerifiedException('پرداخت قبلا تایید شده است', $status);
            }

            if ($status !== $this->getSuccessResponseStatusCode()) {
                throw new PaymentFailedException($this->getStatusMessage($status), $status);
            }

            return new Receipt(
                $this->getInvoice(),
                $response['track_id'],
                $response['payment']['track_id'],
                $response['payment']['card_no'],
            );
        }

        /**
         * @throws InvalidConfigurationException
         * @throws \Exception
         */
        protected function getPurchaseData(): array
        {
            if (empty($this->settings['api_key'])) {
                throw new InvalidConfigurationException('Api key has not been set.');
            }

            $description = $this->getInvoice()->getDescription() ?? $this->settings['description'];

            $mobile = $this->getInvoice()->getPhoneNumber();
            $email = $this->getInvoice()->getEmail();

            if (! empty($mobile)) {
                $mobile = $this->checkPhoneNumberFormat($mobile);
            }

            return [
                'order_id' => $this->getInvoice()->getInvoiceId(),
                'amount' => $this->getInvoice()->getAmount(),
                'name' => $this->getInvoice()->getUserName(),
                'phone' => $mobile,
                'mail' => $email,
                'desc' => $description,
                'callback' => $this->getInvoice()->getCallbackUrl() ?: $this->settings['callback_url'],
            ];
        }

        protected function getVerificationData(): array
        {
            return [
                'id' => request('id', $this->getInvoice()->getTransactionId()),
                'order_id' => request('order_id', $this->getInvoice()->getInvoiceId()),
            ];
        }

        protected function getStatusMessage(int|string $statusCode): string
        {
            $messages = [
                1 => 'پرداخت انجام نشده است.',
                2 => 'پرداخت ناموفق بوده است.',
                3 => 'خطا رخ داده است.',
                4 => 'بلوکه شده',
                5 => 'برگشت به پرداخت کننده',
                6 => 'برگشت خورده سیستمی',
                7 => 'انصراف از پرداخت.',
                8 => 'به درگاه پرداخت منتقل شد.',
                10 => 'در انتظار تایید پرداخت.',
                11 => 'کاربر مسدود شده است.',
                12 => 'API Key یافت نشد.',
                13 => 'درخواست شما از IP نامعتبر ارسال شده است.',
                14 => 'وب سرویس شما در حال بررسی است و یا تایید نشده است.',
                21 => 'حساب بانکی متصل به وب سرویس تایید نشده است.',
                22 => 'وب سریس یافت نشد.',
                23 => 'اعتبار سنجی وب سرویس ناموفق بود.',
                24 => 'حساب بانکی مرتبط با این وب سرویس غیر فعال شده است.',
                31 => 'کد تراکنش id نباید خالی باشد.',
                32 => 'شماره سفارش order_id نباید خالی باشد.',
                33 => 'مبلغ amount نباید خالی باشد.',
                34 => 'مبلغ amount باید بیشتر باشد.',
                35 => 'مبلغ amount باید کمتر باشد.',
                36 => 'مبلغ amount بیشتر از حد مجاز است.',
                37 => 'آدرس بازگشت callback نباید خالی باشد.',
                38 => 'دامنه آدرس بازگشت callback با آدرس ثبت شده در وب سرویس همخوانی ندارد.',
                41 => 'فیلتر وضعیت تراکنش ها می بایست آرایه ای (لیستی) از وضعیت های مجاز در مستندات باشد.',
                42 => 'فیلتر تاریخ پرداخت می بایست آرایه ای شامل المنت های min و max از نوع timestamp باشد.',
                43 => 'فیلتر تاریخ تسویه می بایست آرایه ای شامل المنت های min و max از نوع timestamp باشد.',
                51 => 'تراکنش ایجاد نشد.',
                52 => 'استعلام نتیجه ای نداشت.',
                53 => 'تایید پرداخت امکان پذیر نیست.',
                54 => 'مدت زمان تایید پرداخت سپری شده است.',
                100 => 'پرداخت تایید شده است.',
                101 => 'پرداخت قبلا تایید شده است.',
                200 => 'به دریافت کننده واریز شد',
            ];

            $unknownError = 'خطای ناشناخته رخ داده است.';

            return array_key_exists($statusCode, $messages) ? $messages[$statusCode] : $unknownError;
        }

        protected function getSuccessResponseStatusCode(): int
        {
            return 100;
        }

        private function getPendingVerificationStatusCode(): int
        {
            return 10;
        }

        private function getPaymentAlreadyVerifiedStatusCode(): int
        {
            return 101;
        }

        protected function getPurchaseUrl(): string
        {
            return 'https://api.idpay.ir/v1.1/payment';
        }

        protected function getPaymentUrl(): string
        {
            return $this->paymentUrl;
        }

        protected function getVerificationUrl(): string
        {
            return 'https://api.idpay.ir/v1.1/payment/verify';
        }

        private function callApi(string $url, array $data)
        {
            $response = Http::withHeaders($this->getRequestHeaders())->post($url, $data);

            return $response->json();
        }

        private function getRequestHeaders(): array
        {
            return [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-API-KEY' => $this->settings['api_key'],
                'X-SANDBOX' => (int) $this->settings['sandbox'],
            ];
        }

        private function checkPhoneNumberFormat(string $phoneNumber): string
        {
            if (strlen($phoneNumber) === 12 && Str::startsWith($phoneNumber, '98')) {
                return Str::replaceFirst('98', '0', $phoneNumber);
            }
            if (strlen($phoneNumber) === 10 && Str::startsWith($phoneNumber, '9')) {
                return '0'.$phoneNumber;
            }

            return $phoneNumber;
        }

        private function setPaymentUrl(string $url): void
        {
            $this->paymentUrl = $url;
        }
}
