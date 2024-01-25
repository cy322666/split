<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class FormController extends Controller
{
    public function send(Request $request)
    {
        Log::alert(__METHOD__, $request->toArray());

        $response = Http::withHeaders([
            'X-Request-Id' => Uuid::uuid4()->toString(),
            'X-Request-Timeout' => 15000,
            'X-Request-Attempt' => 0,
            'Authorization' => 'Api-Key '.env('YA_TOKEN'),
        ])->post('https://pay.yandex.ru/api/merchant/v1/orders', [

            'availablePaymentMethods' => 'SPLIT',
            'cart' => [
                'externalId' => Uuid::uuid4()->toString(),
                'items' => [
                    [
                        "description" => 'Какой то курс',
                        "discountedUnitPrice" => 10000.00,
                        "productId" => Uuid::uuid4()->toString(),
                        "quantity" => [
                            "available" => 10000.00,
                            "count" => 1,
                            "label" => "Название (label)"
                        ],
                        "receipt" => [
                            "agent" => [
                                "agentType" => 1,
                                "operation" => "operation",
                                "paymentsOperator" => [
                                    "phones" => [$request->phone]
                                ],
                                "phones" => [$request->phone]
                            ],
                            "excise" => 10000.00,
                            "markQuantity" => [
                                'denominator' => 0,
                                "numerator"   => 0,
                            ],
                            "measure" => 0,
                            "paymentMethodType" => 1,
                            "paymentSubjectType" => 1,
                            "productCode" => base64_encode(Uuid::uuid4()->toString()),
                            "tax" => 1,
                            "title" => "title",
                        ],
                        "subtotal" => 10000.00,
                        "title" => "title total",
                        "total" => 10000.00,
                        "unitPrice" => 10000.00,
                    ],
                ],
                'total' => [
                    'amount' => 10000.00,
                    'label'  => 'final total label'
                ],
            ],
            'currencyCode' => 'RUB',
            //extensions
            'orderId' => Uuid::uuid4()->toString(),
            'purpose' =>'purpose',//TODO
            'redirectUrls' => [
                'onAbort' => 'https://google.com',
                'onError' => 'https://google.com',
                'onSuccess' => 'https://google.com',
            ],
            'ttl' => 1800,
        ]);

        Log::alert(__METHOD__, [$response->json()]);

        if ($response->ok()) {

            return redirect($response->json()['data']['paymentUrl']);
        }
    }
}
