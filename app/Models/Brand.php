<?php

namespace App\Models;

use App\BrandAgent;
use App\Helpers\Helper;
use App\Models\Promotion;
use App\Models\CustomerWithdraw;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    //
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'game_id',
        'logo',
        'logo_url',
        'name',
        'line_id',
        'liff_id',
        'website',
        'telephone',
        'subdomain',
        'agent_prefix',
        'line_token',
        'line_notify_token',
        'line_liff_connect',
        'line_liff_connect_react',
        'line_liff_register',
        'line_liff_transfer',
        'line_liff_info',
        'line_channel_secret',
        'line_menu_register',
        'line_menu_member',
        'agent_username',
        'agent_password',
        'agent_username_2',
        'agent_password_2',
        'agent_credit',
        'agent_member_value',
        'credit_remain',
        'cost_service',
        'cost_working',
        'deposit_min',
        'type_deposit',
        'withdraw_min',
        'withdraw_auto_max',
        'noty_register',
        'noty_deposit',
        'noty_withdraw',
        'stock',
        'status_telephone',
        'status_rank',
        'rank_description',
        'status_bot_deposit',
        'status_line_id',
        'hash',
        'server_api',
        'bot_ip',
        'app_id',
        'bot_bank',
        'bot_deposit',
        'bot_withdraw',
        'invite',
        'invite_min',
        'invite_deposit_type',
        'invite_type',
        'invite_cost',
        'last_update_credit_remain',
        'type_api',
        'code_sms',
        'status',
        'maintenance',
    ];

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function bankAccounts() {
        return $this->hasMany(BankAccount::class);
    }

    public function bankAccountWebs() {
        return $this->hasMany(BankAccount::class)->where('status_web','=',1);
    }

    public function promotions() {
        return $this->hasMany(Promotion::class);
    }

    public function ranks() {
        return $this->hasMany(BrandRank::class);
    }

    public function agents() {
        return $this->hasMany(BrandAgent::class);
    }

    public function getDepositTodayAttribute() {

        $dates = Helper::getTimeMonitor();

        $customer_deposit = CustomerDeposit::whereBrandId($this->id)->whereBetween('created_at', [$dates[0],$dates[1]])->get();

        return $customer_deposit->sum('amount');

    }

    public function getWithdrawTodayAttribute() {

        $dates = Helper::getTimeMonitor();

        $customer_deposit = CustomerWithdraw::whereBrandId($this->id)->whereBetween('created_at', [$dates[0],$dates[1]])->get();

        return $customer_deposit->sum('amount');

    }

    public function getCustomerTodayAttribute() {

        $dates = Helper::getTimeMonitor();

        $customer_deposit = $customers = Customer::whereBrandId($this->id)->whereBetween('created_at', [$dates[0],$dates[1]])->get();

        return $customer_deposit->count();

    }

    /**
     * Get all of the creditFrees for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creditFrees(): HasMany
    {
        return $this->hasMany(CreditFree::class, 'foreign_key', 'local_key');
    }
}
