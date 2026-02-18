<?php

namespace Lampminds\Customization\Models;

use Illuminate\Database\Eloquent\Model;

class LmpLanguage extends Model
{
    protected $dont_use_audit = true;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'code3'
    ];
}
