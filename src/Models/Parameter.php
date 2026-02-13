<?php

namespace Lampminds\Customization\Models;

use Lampminds\Customization\Filament\LmpCustomization\Models\BaseModel;
use Lampminds\Customization\Filament\LmpCustomization\Traits\AuditTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Parameter extends BaseModel implements HasMedia
{
    use HasFactory, AuditTrait, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'code',
        'type_id',
        'value',
        'mode_id',
        'help',
        'comments',
        'created_by',
        'updated_by',
    ];

    /**
     * Type
     *
     * @var array
     */
    public const TYPES = [
        0 => 'string',
        1 => 'integer',
        2 => 'boolean',
        3 => 'date',
        4 => 'datetime',
        5 => 'time',
        6 => 'timestamp',
        7 => 'text',
    ];

    /**
     * Mode
     *
     * @var array
     */
    public const MODES = [
        'editable',
        'readonly',
        'internal',
    ];

    /**
     * Register media conversions (static for extension in child classes).
     * Instance method delegates here so you can override in a child model.
     */
    public static function registerMediaConversionsStatic(self $parameter): void
    {
        $parameter->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CONTAIN, 100, 100)
            ->nonQueued();

        $parameter->addMediaConversion('parameters')
            ->fit(Manipulations::FIT_CONTAIN, 600, 600)
            ->nonQueued();

        $parameter->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CONTAIN, 300, 300)
            ->nonQueued();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        static::registerMediaConversionsStatic($this);
    }

    /**
     * Register media collections (static for extension in child classes).
     */
    public static function registerMediaCollectionsStatic(self $parameter): void
    {
        $parameter->addMediaCollection('parameters');
    }

    public function registerMediaCollections(): void
    {
        static::registerMediaCollectionsStatic($this);
    }

    /**
     * returns the id of a given type
     *
     * @param string $code code
     * @return int codeID
     */
    public static function getTypeID($code)
    {
        return array_search($code, self::TYPES);
    }

    /**
     * returns the id of a given mode
     *
     * @param string $code code
     * @return int codeID
     */
    public static function getModeID($code)
    {
        return array_search($code, self::MODES);
    }

    /**
     * Get type name by type_id (static for extension in child classes).
     *
     * @param int|null $typeId
     * @return string
     */
    public static function getTypeName(?int $typeId): string
    {
        if ($typeId !== null && isset(static::TYPES[$typeId])) {
            return static::TYPES[$typeId];
        }
        return static::TYPES[0];
    }

    /**
     * Get mode name by mode_id (static for extension in child classes).
     *
     * @param int|null $modeId
     * @return string
     */
    public static function getModeName(?int $modeId): string
    {
        if ($modeId !== null && isset(static::MODES[$modeId])) {
            return static::MODES[$modeId];
        }
        return static::MODES[0];
    }

    /**
     * get type (accessor delegates to static).
     */
    public function getTypeAttribute()
    {
        $typeId = $this->attributes['type_id'] ?? null;
        return static::getTypeName($typeId !== null ? (int) $typeId : null);
    }

    /**
     * get mode (accessor delegates to static).
     */
    public function getModeAttribute()
    {
        $modeId = $this->attributes['mode_id'] ?? null;
        return static::getModeName($modeId !== null ? (int) $modeId : null);
    }

    /**
     * set type
     */
    public function setTypeAttribute($value)
    {
        $this->attributes['type_id'] = static::getTypeID($value);
    }

    /**
     * set mode
     */
    public function setModeAttribute($value)
    {
        $this->attributes['mode_id'] = static::getModeID($value);
    }
}
