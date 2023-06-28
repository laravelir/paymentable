<?php

namespace Laravelir\Paymentable\Http\Controllers;

use Laravelir\Paymentable\Http\Controllers\Controller;

class PaymentableController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|int',
            'description' => 'nullable|string'
        ]);

        $newPayment = Paymentable
            ::amount($request->amount)
            ->description($request->description)
            ->request();

        if ($newPayment->failed()) {
            return back()->withErrors($newPayment->message());
        }

        // We should save amount and retrieved transaction id in order to
        // make it possible to verify after callback.
        Payment::create([
            'amount' => $request->amount,
            'transaction_id' => $newPayment->transactionId()
        ]);

        return $newPayment->pay();
    }

    /**
     * Verify payment callback request to check if the payment was successful or not
     *
     * @param CallbackRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function callback(CallbackRequest $request)
    {
        $payment = Payment::whereTransactionId($request->transactionId())->firstOrFail();

        $verification = $request->amount($payment->amount)->verify();

        if ($verification->failed()) {
            $payment->failed_at = now();
        }

        if ($verification->alreadyVerified()) {
            // In case you haven't saved reference_id on the first verification
            $payment->reference_id = $verification->referenceId();
        }

        if ($verification->successful()) {
            $payment->reference_id = $verification->referenceId();
            $payment->paid_at = now();
        }

        $payment->save();

        return view('show', compact(['payment', 'verification']));
    }
}
