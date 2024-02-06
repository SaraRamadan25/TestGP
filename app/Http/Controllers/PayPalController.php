<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

class PayPalController extends Controller
{
    private ApiContext $apiContext;

    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.client_secret')
            )
        );

        $this->apiContext->setConfig(['mode' => config('paypal.settings.mode')]);
    }

    public function createPayment(Request $request): JsonResponse
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setTotal($request->input('amount'));
        $amount->setCurrency('USD');

        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.execute')) // Set the return URL
        ->setCancelUrl(url('/'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);

            return response()->json(['approval_url' => $payment->getApprovalLink(), 'payment' => $payment->toArray()]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function executePayment(Request $request): JsonResponse
    {
        $paymentId = $request->query('paymentId');
        $payerId = $request->query('PayerID');

        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            $result = $payment->execute($execution, $this->apiContext);

            return response()->json(['status' => $result->getState()]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
