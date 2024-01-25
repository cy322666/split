<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Ramsey\Uuid\Uuid;

class Form extends Component
{
    public string $phone;
    public string $email;

    public string $sale;
    public string $minutes;
    public string $key;
    public string $type;

    public function render(Request $request)
    {
        $this->sale = $request->sale ?? 0;
        $this->minutes = $request->minutes ?? 0;
        $this->key = $request->key ?? 0;
        $this->type = $request->type ?? 0;

        Log::alert(__METHOD__, $request->toArray());

        return view('livewire.form');
    }

    public function save(Request $request)
    {
        Log::info(__METHOD__, $request->toArray());

        Artisan::call('order:create', [
            'email' => $this->email,
            'phone' => $this->phone,
            'sale'  => $this->sale,
            'minutes' => $this->minutes,
            'key'   => $this->key,
            'type'  => $this->type,
        ]);
    }
}
