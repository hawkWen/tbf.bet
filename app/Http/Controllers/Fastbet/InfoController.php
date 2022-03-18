<?php

namespace App\Http\Controllers\Fastbet;

use App\Helpers\Bot;
use App\Models\Brand;
use App\Models\Customer;
use App\Helpers\RachaApi;
use App\Helpers\FastbetApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InfoController extends Controller
{
    //
    public function index($brand) {
    
        $brand = Brand::whereSubdomain($brand)->first();

        $credit = Bot::creditFastbet($brand,$customer);

        $customer->update([
            'credit' => $credit['data']['credit']
        ]);

        return view('fastbet.info',\compact('brand'));

    }

    public function view($brand,$line_user_id) {
    
        $brand = Brand::whereSubdomain($brand)->first();

        $customer = Customer::whereBrandId($brand->id)->whereLineUserId($line_user_id)->first();

        $customer_deposits = $customer->deposits->sortByDesc('created_at')->take(5);

        $customer_withdraws = $customer->withdraws->sortByDesc('created_at')->take(5);

        $histories = $customer_deposits->concat($customer_withdraws);
        
        $balance = $customer->credit;

        return view('fastbet.info-view', compact('customer','brand','balance','histories'));

    }
}
