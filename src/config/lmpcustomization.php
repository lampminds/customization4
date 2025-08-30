<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Timezone shift
    |--------------------------------------------------------------------------
    |
    | This value complements the timezone setting in the application.
    | Its goal is to convert back and forth between the local time and
    | the UTC time, which is the default timezone for Laravel.
    | Check the helpers `toUtc()` and `fromUtc()` for more details.
    |
    */

    'timezone_shift' => 'America/Argentina/Buenos_Aires',

    /*
    |--------------------------------------------------------------------------
    | Application default date and time formats for display
    |--------------------------------------------------------------------------
    |
    | These are the default date and time formats used throughout the application
    | for displaying dates and times.
    |
    */

    'display_date_format' => 'M d, Y',
    'display_datetime_format' => 'M d, Y H:i',
    'display_time_format' => 'H:i',

    /*
     | --------------------------------------------------------------------------
     | Database date and datetime formats
     | --------------------------------------------------------------------------
     |
     | These are the formats used for storing dates and datetimes in the database.
     |
     */
    'database_date_format' => 'Y-m-d',
    'database_datetime_format' => 'Y-m-d\TH:i:s',

    /*
     * --------------------------------------------------------------------------
     * Decimal point and thousands separator
     * --------------------------------------------------------------------------
     *
     * These settings define the decimal point and thousands separator used in the application.
     *
     */
    'decimal_point' => env('LMP_DECIMAL_POINT', '.'),
    'thousands_separator' => env('LMP_THOUSANDS_SEPARATOR', ','),
    'currency_symbol' => env('LMP_CURRENCY_SYMBOL', '$'),

];
