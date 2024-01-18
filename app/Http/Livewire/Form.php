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

    public function render()
    {
        return view('livewire.form');
    }

    public function save(Request $request)
    {
        Log::info(__METHOD__, $request->toArray());

        Artisan::call('order:create', [
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
    }
}
