<?php

namespace Lampminds\Customization\Filament\LmpCustomization\Traits;

use Lampminds\Customization\Models\User;
use Illuminate\Support\Facades\Auth;

trait AuditTrait
{

    /**
     *  This trait automatically fills the "created_by" and "updated_by" table columns
     *  of any model it's used into, whenever you create or update a new row.
     *
     *  Unless the model has a property called $dont_use_audit, it will automatically
     *  set and manage the created_by and updated_by fields.
     *
     *  Also, it includes the ability to get the nickname of the created_by and updated_by
     *  named created_by_nickname and updated_by_nickname.
     *
     *  Obviously you need to have the following fields defined in your model:
     *            $table->foreignId('created_by')->nullable()->constrained('users');
     *            $table->foreignId('updated_by')->nullable()->constrained('users');
     *
     */
    public static function boot()
    {
        parent::boot();
        if (!property_exists(get_called_class(), 'dont_use_audit')) {
            self::creating(function ($model) {
                $model->created_by = Auth::check() ? Auth::id() : 1;
                // since native Laravel trait also set updated_at on creation, we mimic it.
                $model->updated_by = Auth::check() ? Auth::id() : 1;
            });
            self::updating(function ($model) {
                $model->updated_by = Auth::check() ? Auth::id() : 1;
            });
        }

        // if (!property_exists(get_called_class(), 'dont_use_account')) {
        //     self::creating(function ($model) {
        //         $model->account_id = Auth::check() ? Auth::user()->account_id : 1;
        //     });
        //     static::addGlobalScope(new AccountScope());
        // }
    }

    /**
     * Get the user who created the record
     *
     */
    public function getCreatedByNicknameAttribute(): string | null
    {
        if (!property_exists(get_called_class(), 'dont_use_audit')
            && isset($this->created_by)
            && $this->created_by) {
            return nickname($this->created_by);
        }
        return null;
    }

    /**
     * Get the user who updated the record
     *
     */
    public function getUpdatedByNicknameAttribute(): string | null
    {
        if (!property_exists(get_called_class(), 'dont_use_audit')
            && isset($this->updated_by)
            && $this->updated_by) {
            return nickname($this->updated_by);
        }
        return null;
    }

    public function created_by()
    {
        $ret = null;

        if (Auth::check()) {
            if (!property_exists(get_called_class(), 'dont_use_audit')) {
                $ret = $this->belongsTo(User::class, 'created_by');
            }
        }
        return $ret;
    }

    public function updated_by()
    {
        $ret = null;

        if (Auth::check()) {
            if (!property_exists(get_called_class(), 'dont_use_audit')) {
                $ret = $this->belongsTo(User::class, 'updated_by');
            }
        }
        return $ret;
    }
}
