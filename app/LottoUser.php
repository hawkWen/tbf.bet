<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LottoUser extends Model
{
    //
    protected $connection = 'mysql-lotto';

    protected $table = 'users';

    protected $guarded = ['id'];
}
