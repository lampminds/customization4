<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;

class LmpFormRichEditor
{
    static function make(string $label, string $name = '') : RichEditor
    {
        return RichEditor::make($name == '' ? Str::snake($label) : $name)
            ->toolbarButtons([
                'attachFiles',
                'blockquote',
                'bold',
                'bulletList',
                'h1',
                'h2',
                'h3',
                'italic',
                'link',
                'orderedList',
                'redo',
                'undo',
            ])
            ->label(__($label))
            ->extraInputAttributes(['style' => 'min-height: 20rem; max-height: 50vh; overflow-y: auto;'])
            ->columnSpanFull();
    }
}
