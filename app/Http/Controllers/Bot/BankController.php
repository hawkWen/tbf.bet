<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankController extends Controller
{
    //
    public function bank() {
        
        return response()->json(['status' => true]);

    }
}
