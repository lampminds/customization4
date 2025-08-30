<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LmpFormCurrency
{
    public static function make(string $label = 'Price', string $name = ''): TextInput
    {
        $name = $name ?: Str::snake($label);
        $decimal_point = config('lmpcustomization.decimal_point', '.');
        $thousands_separator = config('lmpcustomization.thousands_separator', ',');

        return TextInput::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->formatStateUsing(fn($state) => is_numeric($state) ? formatNumber($state) : $state)
            ->mask(RawJs::make('$money($input, \''.$decimal_point."\', \'".$thousands_separator."\')"))
            ->dehydrateStateUsing(function ($state) use ($decimal_point, $thousands_separator) {
                if (empty($state)) return null;

                // Remove thousands separators (periods) and convert decimal comma to period
                // only if the decimal point is a comma
                if ($decimal_point === ',') {
                    $normalized = str_replace('.', '', $state); // Remove thousands separators
                    $normalized = str_replace(',', '.', $normalized); // Convert decimal separator
                } else {
                    $normalized = $state;
                }

                return is_numeric($normalized) ? (float) $normalized : null;
            })
            ->extraAttributes(function() use ($decimal_point, $thousands_separator) {
                return ($decimal_point === '.'
                ? []
                : [
                'novalidate' => true,
                'x-on:keydown' => "
                    if (\$event.key === '".$thousands_separator."' && \$event.target.selectionStart !== null) {
                        \$event.preventDefault();
                        const el = \$event.target;
                        const start = el.selectionStart;
                        const end = el.selectionEnd;
                        const val = el.value;
                        el.value = val.slice(0, start) + '".$decimal_point."' + val.slice(end);
                        el.setSelectionRange(start + 1, start + 1);
                    }
                ",
                ]);
            });
    }
}
