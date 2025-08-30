<?php

namespace Lampminds\Customization\Models;

use Lampminds\Customization\Filament\LmpCustomization\Traits\AuditTrait;

class Page extends BaseModel
{
    use AuditTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_id',
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'order',
        'active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Page Types
     *
     * @var array
     */
    public const TYPES = [
        'home' => 'Home',
        'page' => 'Page',
    ];

    /**
     * Returns the type ID of a given type code
     *
     * @param string $code type code
     * @return string|null type code
     */
    public static function getTypeID($code)
    {
        return array_key_exists($code, self::TYPES) ? $code : null;
    }

    /**
     * Get the human-readable type name
     */
    public function getTypeAttribute()
    {
        if (isset($this->attributes['type_id']) && array_key_exists($this->attributes['type_id'], self::TYPES)) {
            return self::TYPES[$this->attributes['type_id']];
        }
        return self::TYPES['page']; // default to 'page'
    }

    /**
     * Set type using human-readable name
     */
    public function setTypeAttribute($value)
    {
        if (array_key_exists($value, self::TYPES)) {
            $this->attributes['type_id'] = $value;
        } else {
            $this->attributes['type_id'] = 'page'; // default
        }
    }
}
