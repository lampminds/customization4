<?php

namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormIsbn
{
    /**
     * LmpFormIsbn is a custom form component for Filament that handles ISBN input.
     * It stores the ISBN exactly as the user types it (with their preferred dash formatting).
     * Validation ensures the ISBN contains exactly 10 or 13 digits.
     * Input is restricted to digits, dashes, and X (for ISBN-10 check digits).
     *
     * @param string $label The label of the input
     * @param string $name The name of the input
     */
    public static function make(string $label = 'ISBN', string $name = ''): TextInput
    {
        return TextInput::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->placeholder('Enter ISBN with dashes (e.g., 978-987-1136-44-5)')
            ->helperText('Enter ISBN with your preferred dash formatting. Must contain 10 or 13 digits.')
            ->mask(function ($get, $set) {
                // Allow only digits and dashes
                return null; // We'll use a different approach with live validation
            })
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, $set) {
                // Filter out any characters that aren't digits or dashes
                if ($state) {
                    $filtered = preg_replace('/[^0-9X-]/i', '', $state);
                    if ($filtered !== $state) {
                        $set('isbn', $filtered);
                    }
                }
            })
            ->rules([
                'nullable',
                'string',
                'regex:/^[0-9X-]+$/i', // Only digits, X, and dashes allowed
                function ($get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        if (empty($value)) {
                            return; // Allow empty values
                        }

                        // Clean the value for validation (remove dashes)
                        $cleaned = preg_replace('/[^0-9X]/i', '', $value);

                        // Basic length validation
                        if (strlen($cleaned) < 10 || strlen($cleaned) > 13) {
                            $fail('The ISBN must contain exactly 10 or 13 digits.');
                            return;
                        }

                        // More specific length validation
                        if (strlen($cleaned) !== 10 && strlen($cleaned) !== 13) {
                            $fail('The ISBN must contain exactly 10 or 13 digits.');
                            return;
                        }

                        // Character validation
                        if (strlen($cleaned) === 10) {
                            // ISBN-10: 9 digits + 1 digit or X
                            if (!preg_match('/^[0-9]{9}[0-9X]$/i', $cleaned)) {
                                $fail('Invalid ISBN-10 format. Must be 9 digits followed by a digit or X.');
                                return;
                            }
                        } elseif (strlen($cleaned) === 13) {
                            // ISBN-13: all digits
                            if (!preg_match('/^[0-9]{13}$/', $cleaned)) {
                                $fail('Invalid ISBN-13 format. Must be 13 digits.');
                                return;
                            }
                        }

                        // Check for duplicate ISBN
                        $currentRecordId = null;

                        // Try to get the current record ID from the Livewire component
                        $livewire = \Livewire\Livewire::current();
                        if ($livewire) {
                            // Check if it's an edit form with a record
                            if (property_exists($livewire, 'record') && $livewire->record) {
                                $currentRecordId = $livewire->record->getKey();
                            }
                            // Check if it's a resource edit page
                            elseif (method_exists($livewire, 'getRecord') && $livewire->getRecord()) {
                                $currentRecordId = $livewire->getRecord()->getKey();
                            }
                            // Check for data array with id
                            elseif (property_exists($livewire, 'data') && is_array($livewire->data) && isset($livewire->data['id'])) {
                                $currentRecordId = $livewire->data['id'];
                            }
                        }

                        // First check exact match
                        $exactQuery = \App\Models\Book::where('isbn', $value);
                        if ($currentRecordId) {
                            $exactQuery->where('id', '!=', $currentRecordId);
                        }

                        if ($exactQuery->exists()) {
                            $fail('This ISBN is already in use by another book.');
                            return;
                        }

                        // Also check for the same ISBN with different formatting
                        // Compare cleaned versions (digits only)
                        $cleanedValue = preg_replace('/[^0-9X]/i', '', $value);

                        $formattedQuery = \App\Models\Book::whereNotNull('isbn')
                            ->where('isbn', '!=', '')
                            ->get()
                            ->filter(function ($book) use ($cleanedValue, $currentRecordId) {
                                if ($currentRecordId && $book->id == $currentRecordId) {
                                    return false; // Exclude current record
                                }
                                $cleanedDbIsbn = preg_replace('/[^0-9X]/i', '', $book->isbn ?? '');
                                return $cleanedDbIsbn === $cleanedValue;
                            });

                        if ($formattedQuery->isNotEmpty()) {
                            $existingBook = $formattedQuery->first();
                            $fail("This ISBN is already in use by another book ('{$existingBook->title}' with ISBN: {$existingBook->isbn}).");
                            return;
                        }
                    };
                },
            ]);
    }

    /**
     * Format ISBN - now returns as-is since we store user formatting
     */
    public static function formatIsbn(?string $isbn): ?string
    {
        if (empty($isbn)) {
            return null;
        }

        // With new behavior, we return the ISBN as-is since we store user formatting
        return $isbn;
    }

    /**
     * Clean ISBN by removing all non-digit characters except X (for validation purposes)
     */
    public static function cleanIsbn(?string $isbn): ?string
    {
        if (empty($isbn)) {
            return null;
        }

        return preg_replace('/[^0-9X]/i', '', strtoupper($isbn));
    }

    /**
     * Validate ISBN format and check digit
     */
    public static function isValidIsbn(?string $isbn): bool
    {
        if (empty($isbn)) {
            return false;
        }

        $cleaned = self::cleanIsbn($isbn);

        if (strlen($cleaned) === 10) {
            return self::isValidIsbn10($cleaned);
        } elseif (strlen($cleaned) === 13) {
            return self::isValidIsbn13($cleaned);
        }

        return false;
    }

    /**
     * Validate ISBN-10 check digit
     */
    private static function isValidIsbn10(string $isbn): bool
    {
        if (strlen($isbn) !== 10) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            if (!is_numeric($isbn[$i])) {
                return false;
            }
            $sum += (int)$isbn[$i] * (10 - $i);
        }

        $checkDigit = (11 - ($sum % 11)) % 11;
        $expectedCheckDigit = $checkDigit === 10 ? 'X' : (string)$checkDigit;

        return $isbn[9] === $expectedCheckDigit;
    }

    /**
     * Validate ISBN-13 check digit
     */
    private static function isValidIsbn13(string $isbn): bool
    {
        if (strlen($isbn) !== 13) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            if (!is_numeric($isbn[$i])) {
                return false;
            }
            $sum += (int)$isbn[$i] * (($i % 2 === 0) ? 1 : 3);
        }

        $checkDigit = (10 - ($sum % 10)) % 10;
        return $isbn[12] === (string)$checkDigit;
    }

    /**
     * Convert ISBN-10 to ISBN-13
     */
    public static function convertIsbn10To13(string $isbn10): ?string
    {
        $cleaned = self::cleanIsbn($isbn10);

        if (strlen($cleaned) !== 10) {
            return null;
        }

        // Remove check digit and add 978 prefix
        $isbn12 = '978' . substr($cleaned, 0, 9);

        // Calculate check digit for ISBN-13
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$isbn12[$i] * (($i % 2 === 0) ? 1 : 3);
        }
        $checkDigit = (10 - ($sum % 10)) % 10;

        return $isbn12 . $checkDigit;
    }

    /**
     * Convert ISBN-13 to ISBN-10
     */
    public static function convertIsbn13To10(string $isbn13): ?string
    {
        $cleaned = self::cleanIsbn($isbn13);

        if (strlen($cleaned) !== 13 || substr($cleaned, 0, 3) !== '978') {
            return null;
        }

        // Remove 978 prefix and check digit
        $isbn9 = substr($cleaned, 3, 9);

        // Calculate check digit for ISBN-10
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$isbn9[$i] * (10 - $i);
        }
        $checkDigit = (11 - ($sum % 11)) % 11;
        $checkDigit = $checkDigit === 10 ? 'X' : (string)$checkDigit;

        return $isbn9 . $checkDigit;
    }
}
