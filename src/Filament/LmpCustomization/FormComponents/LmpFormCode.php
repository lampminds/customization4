<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormCode
{
    /**
     * LmpFormCode is a custom form component for Filament that allows to input a code with a mask.
     * The mask format can be found at https://imask.js.org/guide.html#masked
     * It's only for letters and numbers, i.e: '999/aaa', but other special chars are in fixed postitions
     *
     * @param string $mask The mask format
     * @param string $label The label of the input
     * @param string $name The name of the input
     * @param boolean $capitalize true if code needs to be capitalized
     */
    static function make(string $mask, string $label = 'Code', string $name = '', bool $capitalize = true) : TextInput
    {
        $input = TextInput::make($name === '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->mask($mask);

        if ($capitalize) {
            $input
                ->reactive() // key to allow Livewire to update when JS modifies DOM
                ->extraInputAttributes(['oninput' => 'this.value = this.value.toUpperCase()'])
                ->formatStateUsing(fn (?string $state) => strtoupper($state))
                ->dehydrateStateUsing(fn (?string $state) => strtoupper($state));
        }

        return $input;    }
}
