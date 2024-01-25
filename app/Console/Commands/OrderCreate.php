<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class OrderCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:create {phone} {email} {sale} {minutes} {key} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');
        $email = $this->argument('email');
        $sale = $this->argument('sale');
        $minutes = $this->argument('minutes');
        $key = $this->argument('key');
        $type = $this->argument('type');

        Log::alert(__METHOD__, [
            $phone, $email, $sale, $minutes, $key, $type
        ]);

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
                                    "phones" => [$phone]
                                ],
                                "phones" => [$phone]
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

            redirect($response->json()['data']['paymentUrl']);
        }
    }
}
