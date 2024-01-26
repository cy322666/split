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
                        "discountedUnitPrice" => $request->sale,
                        "productId" => Uuid::uuid4()->toString(),
                        "quantity" => [
                            "available" => $request->sale,
                            "count" => 1,
                            "label" => $request->key,
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
                            "excise" => $request->sale,
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
                        "subtotal" => $request->sale,
                        "title" => $request->key,
                        "total" => $request->sale,
                        "unitPrice" => $request->sale,
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
            'redirectUrls'  => [
                'onAbort'   => 'https://centriym.ru',
                'onError'   => 'https://centriym.ru',
                'onSuccess' => 'https://centriym.ru',
            ],
            'ttl' => 1800,
        ]);

        Log::alert(__METHOD__, [$response->json()]);

        if ($response->ok()) {

            return redirect($response->json()['data']['paymentUrl']);
        }
    }
}
