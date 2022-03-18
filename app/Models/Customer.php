<?php

namespace App\Models;

use App\Models\CustomerRefer;
use App\CustomerCreditHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    use Notifiable;
    // $table->unsignedInteger('brand_id');
    // $table->unsignedInteger('game_id');
    // $table->unsignedInteger('bank_id');
    // $table->unsignedInteger('bank_transfer_id')->default(0);
    // $table->unsignedInteger('promotion_id')->nullable();
    // $table->unsignedInteger('line_user_id')->nullable();
    // $table->text('img')->nullable();
    // $table->text('img_url')->nullable();
    // $table->text('line_token')->nullable();
    // $table->string('name');
    // $table->string('telephone')->nullable();
    // $table->string('line_id')->nullable();
    // $table->string('email')->nullable();
    // $table->string('username');
    // $table->string('password');
    // $table->string('bank_account');
    // $table->integer('status')->default(1); //1 active //0 unactive
    // $table->integer('status_password')->default(1)  ; //0 please reset password //1 reset password already
    protected $fillable = [
        'brand_id',
        'game_id',
        'bank_id',
        'bank_transfer_id',
        'promotion_id',
        'line_user_id',
        'invite_id',
        'app_id',
        'code_bank',
        'img',
        'img_url',
        'line_token',
        'name',
        'telephone',
        'line_id',
        'email',
        'username',
        'password',
        'credit',
        'wheel_score',
        'wheel_amount',
        'bank_account',
        'bank_account_scb',
        'bank_account_krungsri',
        'bank_account_kbank',
        'invite_url',
        'invite_bonus',
        'type',
        'status',
        'status_deposit',
        'status_credit',
        'status_password',
        'status_invite',
        'status_manual',
        'last_update_credit',
        'last_update_password',
        'remark',
        'credit',
        'update_log',
        'from_type',
        'from_type_remark',
        'password_generate',
        'password_generate_2',
        'last_login',
        'last_session',
        'operation',
        'browser',
        'ip',
        'line_menu_member',
        'agent_order',
        'refer_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'last_update_credit'
    ];  

    protected $dates = [
        'last_update_credit',
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function bank() {
        return $this->belongsTo(Bank::class);
    }

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class)->withTrashed();
    }

    public function bankTransfer() {
        return $this->belongsTo(Bank::class,'bank_transfer_id');
    }

    public function deposits() {
        return $this->hasMany(CustomerDeposit::class);
    }

    public function withdraws() {
        return $this->hasMany(CustomerWithdraw::class);
    }

    public function promotion() {
        return $this->belongsTo(Promotion::class)->withTrashed();
    }

    public function invites() {
        return $this->hasMany(Customer::class,'invite_id');
    }

    public function promotionCosts() {
        return $this->hasMany(PromotionCost::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function bets() {
        return $this->hasMany(CustomerBet::class,'username','username');
    }

    public function betDetails() {
        return $this->hasMany(CustomerBetDetail::class,'username','username');
    }

    public function depositFirst() {
        return $this->hasOne(CustomerDeposit::class)->where('status','=',1);
    }

    public function betDetailInvites() {
        return $this->hasMany(CustomerBetDetail::class,'username','username')
        ->select('username',
            DB::raw('sum(turn_over) as total_turn_over'),
            DB::raw('sum(bet) as total_bet'),
            DB::raw('sum(win_loss) as total_win_loss')
            )->where('status_invite','=',0)->groupBy('username');
    }

    public function betInvites() {
        return $this->hasMany(CustomerBet::class, 'username', 'username')
        ->select('username',
            DB::raw('sum(turn_over) as total_turn_over'),
            DB::raw('sum(turn_over_received) as total_turn_over_received'),
            DB::raw('sum(bet) as total_bet'),
            DB::raw('sum(win_loss) as total_win_loss')
            )->where('status_invite','=',0)->groupBy('username');
    }

    public function creditHistories() {
        return $this->hasMany(CustomerCreditHistory::class);
    }

    /**
     * Get all of the comments for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refers()
    {
        return $this->hasMany(CustomerRefer::class);
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

    public function wheels() {
        return $this->hasMany(CustomerWheel::class);
    }
}
