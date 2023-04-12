<?php

namespace Laravelir\Paymentable\Models;

use Illuminate\Database\Eloquent\Model;

class Paymentable extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paymentables';

    // protected $fillable = ['name'];

    protected $guarded = [];
}
