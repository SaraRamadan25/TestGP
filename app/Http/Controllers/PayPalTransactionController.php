<?php

namespace App\Http\Controllers;

use Http\Client\Exception\HttpException;
use Illuminate\Http\Request;
use App\Models\PayPalTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class PayPalTransactionController extends Controller
{
    public function checkout(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Initialize PayPalTransaction SDK with Sandbox environment
        $environment = new SandboxEnvironment(config('services.paypal.client_id'),
            config('services.paypal.client_secret'));
        $client = new PayPalHttpClient($environment);

        $requestBody = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $request->currency,
                        'value' => $request->amount,
                    ],
                ]
            ],
            'application_context' => [
                'return_url' => 'https://example.com/return', // Updated return URL
            ],
        ];

        $createOrderRequest = new OrdersCreateRequest();
        $createOrderRequest->prefer('return=representation');
        $createOrderRequest->body = $requestBody;

        // Send the request and handle the response
        try {
            $response = $client->execute($createOrderRequest);

            // Extract PayPal transaction ID and status from the response
            $transactionId = $response->result->id;
            $status = $response->result->status;

            // Create a new PayPalTransaction record
            $transaction = PayPalTransaction::create([
                'transaction_id' => $transactionId,
                'user_id' => $request->user_id,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'status' => $status,
            ]);

            // PayPal Checkout Link
            $checkoutLink = 'https://www.sandbox.paypal.com/checkoutnow?token='.$transactionId;

            // Return response with checkout link
            return response()->json([
                'transaction' => $transaction,
                'checkout_link' => [
                    'href' => $checkoutLink,
                    'rel' => 'approve',
                    'method' => 'GET',
                ],
            ], 200);
        } catch (HttpException $e) {
            // Handle API exception
            return response()->json(['error' => 'PayPal API Error: '.$e->getMessage()], 500);
        }
    }
    public function captureOrder($orderId)
    {
        // Look up the transaction
        $transaction = PayPalTransaction::where('transaction_id', $orderId)->first();

        if (!$transaction) {
            Log::info('Transaction not found: ' . $orderId);
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Initialize PayPal SDK with Sandbox environment
        $environment = new SandboxEnvironment(config('services.paypal.client_id'), config('services.paypal.client_secret'));
        $client = new PayPalHttpClient($environment);

        // Create a request to capture the order
        $captureOrderRequest = new OrdersCaptureRequest($orderId);
        $captureOrderRequest->prefer('return=representation');

        try {
            // Execute the request
            $response = $client->execute($captureOrderRequest);

            // Update the transaction status in the database
            $transaction->status = $response->result->status;
            $transaction->save();

            Log::info('Payment captured successfully: ' . $orderId);
            return response()->json(['message' => 'Payment captured successfully'], 200);
        } catch (HttpException $e) {
            // Handle API exception
            Log::error('PayPal API Error: ' . $e->getMessage());
            return response()->json(['error' => 'PayPal API Error: ' . $e->getMessage()], 500);
        }
    }

public function completePayment(Request $request)
    {
        $transactionId = $request->query('token');

        // Look up the transaction
        $transaction = PayPalTransaction::where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            Log::info('Transaction not found: ' . $transactionId);
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Initialize PayPal SDK with Sandbox environment
        $environment = new SandboxEnvironment(config('services.paypal.client_id'), config('services.paypal.client_secret'));
        $client = new PayPalHttpClient($environment);

        // Create a request to capture the order
        $captureOrderRequest = new OrdersCaptureRequest($transactionId);
        $captureOrderRequest->prefer('return=representation');

        try {
            // Execute the request
            $response = $client->execute($captureOrderRequest);

            // Update the transaction status in the database
            $transaction->status = $response->result->status;
            $transaction->save();

            Log::info('Payment completed successfully: ' . $transactionId);
            return response()->json(['message' => 'Payment completed successfully'], 200);
        } catch (HttpException $e) {
            // Handle API exception
            Log::error('PayPal API Error: ' . $e->getMessage());
            return response()->json(['error' => 'PayPal API Error: ' . $e->getMessage()], 500);
        }
    }}
