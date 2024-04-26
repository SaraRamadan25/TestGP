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

    public function completeOrder(Request $request, $orderId)
    {
        try {
            // Initialize PayPal SDK with Sandbox environment
            $environment = new SandboxEnvironment(config('services.paypal.client_id'),
                config('services.paypal.client_secret'));
            $client = new PayPalHttpClient($environment);

            // Create capture order request
            $captureOrderRequest = new OrdersCaptureRequest($orderId);
            $captureOrderRequest->prefer('return=representation');

            // Send the request to capture the order
            $captureResponse = $client->execute($captureOrderRequest);

            // Extract captured status from the response
            $captureStatus = $captureResponse->result->status;

            // Update the order status to "completed" in your application
            // Example: Update your database record or perform other actions
            // based on the captured status

            return response()->json(['status' => 'completed', 'order_id' => $orderId], 200);
        } catch (HttpException $e) {
            // Handle API exception
            return response()->json(['error' => 'PayPal API Error: '.$e->getMessage()], 500);
        }
    }
}
