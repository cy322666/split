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


    }
}
