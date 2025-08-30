<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
////////////////////////////////////
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Lampminds\Customization\Models\User;

/**
 * Returns a verbose version of the user_id, assuming the user->name is in the form "firstname lastname".
 * If the name is just one word, it is returned as is.
 * If the user_id is not found, it returns a "???" string.
 *
 * @param $user_id
 * @return string
 */

function nickname($user_id): string
{
    static $cache = [];

    if (in_array($user_id, $cache)) {
        $ret = $cache[$user_id];
    } else {
        if ($user = User::where('id', $user_id)->first()) {
            $aux = explode(' ', $user->name);
            switch (count($aux)) {
                case 0:
                    $ret = $user->name;
                    break;
                case 1:
                    $ret = Str::ucfirst($aux[0]);
                    break;
                case 2:
                    $ret = Str::ucfirst($aux[0]) . '-' . Str::ucfirst($aux[1][0]);
                    break;
                default:
                    $ret = Str::ucfirst($aux[0]) . '-' . Str::ucfirst($aux[2][0]);
            }
            $cache[$user_id] = $ret;
        } else {
            $ret = 'n/a';
        }
    }
    return $ret;
}

/**
 * Returns a human-readable file size.
 * This function converts bytes into a more readable format, such as KB, MB, GB, etc.
 *
 * @param $bytes
 * @param $decimals
 * @return string
 */
function human_filesize($bytes, $decimals = 2)
{
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

/**
 * Returns a human-readable count.
 * This function converts a count into a more readable format, such as K (thousands), M (millions), or B (billions).
 *
 * @param $count
 * @return int|string
 */
function human_count($count)
{
    $count = intval($count);
    if ($count < 1000) {
        return $count;
    } elseif ($count < 1000000) {
        return round($count / 1000, 1) . 'K';
    } elseif ($count < 1000000000) {
        return round($count / 1000000, 1) . 'M';
    } else {
        return round($count / 1000000000, 1) . 'B';
    }
}

function getCurrentAccountName()
{
    return Auth::check() ? Auth::user()->account->name : config('app.name');
}

/**
 * Formats a phone number according to a given mask.
 * This function replaces '9' characters in the mask with corresponding digits from the phone number.
 *
 * @param $phone
 * @param $parameter
 * @return string
 */
function formatPhoneMask($phone, $parameter)
{
    $mask = $parameter;
    $mask = str_split($mask);
    $phone = str_split($phone);
    foreach ($mask as $key => $value) {
        if ($value == '9') {
            $mask[$key] = array_shift($phone);
        }
    }
    $mask = implode('', $mask);
    return $mask;
}

/**
 * Checks if the current route is for creating a Filament resource.
 * This is helpful to be used in forms to setup the autofocus on a field
 *
 * @return bool
 */
function isFilamentCreating(): bool
{
    return request()->routeIs('filament.admin.resources.*.create');
}

function isFilamentEditing(): bool
{
    return request()->routeIs('filament.admin.resources.*.edit');
}

function fromUtc($datetime, ?string $tz = null): Carbon
{
    $tz ??= config('lmpcustomization.timezone_shift');
    $ret = Carbon::parse($datetime)->setTimezone($tz);
    return $ret;
}

function toUtc($datetime, ?string $tz = null): Carbon
{
    $tz ??= config('lmpcustomization.timezone_shift');
    $ret = Carbon::parse($datetime, $tz)->setTimezone('UTC');
    return $ret;
}

/**
 * Formats a date to a localized string.
 * This function converts a date to a string formatted according to the Argentine locale.
 *
 * @param Carbon $date
 * @return string
 */
function localized_date($date): string
{
    // check if the argument is a carbon instance, if not, parse it
    if (!$date instanceof Carbon) {
        $date = Carbon::parse($date);
    }

    return Carbon::parse($date)
        ->setTimezone(config('lmpcustomization.timezone_shift'))
        ->translatedFormat(config('lmpcustomization.display_date_format'));
}

/**
 * Formats a time to a localized string.
 * This function converts a time object to a string formatted according to the Argentine locale.
 * @param Carbon $date
 * @return string
 */
function localized_time($date): string
{
    // check if the argument is a carbon instance, if not, parse it
    if (!$date instanceof Carbon) {
        $date = Carbon::parse($date);
    }

    return Carbon::parse($date)
        ->setTimezone(config('lmpcustomization.timezone_shift'))
        ->translatedFormat(config('lmpcustomization.display_time_format'));
}

/**
 * Use this function to format a number with a prefix (like currency) instead of using number_format directly.
 *
 * @param $number float|int The number to format.
 * @param $prefix true to add the prefix '$ ' before the number.
 * @return string
 */
function formatNumber($number, $prefix = false, $decimals = 2): string
{
    $decimalPoint = config('lmpcustomization.decimal_point', '.');
    $thousandsSeparator = config('lmpcustomization.thousands_separator', ',');
    return ($prefix ? config('lmpcustomization.currency_symbol', '$').' ':'') . number_format($number, $decimals, $decimalPoint, $thousandsSeparator);
}

function formatPercentage($value, $decimals = 0): string
{
    $decimalPoint = config('lmpcustomization.decimal_point', '.');
    $thousandsSeparator = config('lmpcustomization.thousands_separator', ',');
    $formattedValue = number_format(($value - 1) * 100, $decimals, $decimalPoint, $thousandsSeparator);
    return $formattedValue . ' %';
}

function isRecentlyWorkedOn($updated_at): bool
{
    $recentlyWorkedOn = getParameterValue('is_recently_worked_on', 5); // Default to 5 minutes
    $updatedAt = Carbon::parse($updated_at);
    return $updatedAt->diffInDays(Carbon::now()) <= $recentlyWorkedOn;
}

function lmpStamps(Blueprint $table) : void
{
    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamp('created_at')->nullable();
    $table->timestamp('updated_at')->nullable();
}
