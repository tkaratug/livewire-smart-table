<?php

namespace Tkaratug\LivewireSmartTable\Tests\Database\Models;

use Illuminate\Database\Eloquent\Model;

class Dummy extends Model
{
    protected $table = 'dummies';

    protected $fillable = ['firstname', 'lastname', 'email'];
}
