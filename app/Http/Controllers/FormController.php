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

        $orderId = Uuid::uuid4()->toString();

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
                    'amount' => $request->sale,
                    'label'  => $request->key,
                ],
            ],
            'currencyCode' => 'RUB',
            //extensions
            'orderId' => $orderId,
            'purpose' =>'purpose',//TODO
            'redirectUrls'  => [
                'onAbort'   => 'https://centriym.com',
                'onError'   => 'https://centriym.com',
                'onSuccess' => 'https://centriym.com',
            ],
            'ttl' => 1800,
        ]);

        Log::alert(__METHOD__, [$response->json()]);

        if ($response->ok()) {

            try {
                Http::post('https://tglk.ru/in/4By9VuEJxUpBby2l', [
                    'order_id' => $orderId,
                    'phone' => $request->phone,
                    'email' => $request->email,
                ]);
            } catch (\Throwable $exception) {

                Log::error(__METHOD__.' '.$exception->getMessage());
            }

            return redirect($response->json()['data']['paymentUrl']);
        }
    }
}
