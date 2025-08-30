<?php

namespace Lampminds\Customization\Filament\LmpCustomization\Models;

use Lampminds\Customization\Filament\LmpCustomization\Traits\AuditTrait;
use Lampminds\Customization\Filament\LmpCustomization\Traits\AuditablePivotTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * This class is a Lampminds base that is intended to be used as a base for all models, EXCEPT User.
 *
 * You can customize this class to include any functionality that you want to be available in all models.
 * LmpAudiTrait is included here by default, so all models will have the created_by and updated_by fields
 *
 * Note: do not use this class as base for Users (because Users use a different base model)
 *
 */

class BaseModel extends Model
{
    use HasFactory, AuditTrait, AuditablePivotTrait;

    /** @var string[]
     *
     * Filament needs this in order to show the user's nickname in tables and forms
     */
    protected $appends = [
        'created_by_nickname',
        'updated_by_nickname'
    ];
}
