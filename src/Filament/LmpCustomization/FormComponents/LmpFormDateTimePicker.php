<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\Str;

class LmpFormDateTimePicker
{
    static function make(string $label = 'Date', string $name = '') : DateTimePicker
    {
        return DateTimePicker::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->native(false)
            ->live(onBlur: true)
            ->displayFormat(config('lmpcustomization.display_datetime_format'))
            ->format(config('lmpcustomization.database_datetime_format'))
            ->default(Carbon::now()->addDays(30)->setTime(18, 0))
            ->live(onBlur: true);
//            ->required()
//            ->formatStateUsing(function ($state) {
//                $ret = $state ? fromUtc($state)->format('Y-m-d\TH:i:s') : null;
//                return $ret;
//            })
//            ->default(Carbon::now()->addDays(30)->setTime(18, 0))
//            ->dehydrateStateUsing(function ($state) {
//                $ret = $state ? toUtc($state) : null;
//                return $ret;
//            } );
    }
}
