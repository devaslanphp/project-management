<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Default Sets
    |--------------------------------------------------------------------------
    |
    | This configures the icon sets the plugin should use by default for
    | every Icon Picker.
    |
    | You can pass a string with the icon set's name or an array
    | of multiple icon set names.
    |
    | When set to null (default), every installed icon set will be used.
    |
    */
    'sets' => [
        'heroicons',
        'fontawesome',
    ],
//     example:
//     'sets' => 'heroicons',
//     'sets' => [
//        'heroicons',
//        'fontawesome-solid',
//    ],

    /*
    |--------------------------------------------------------------------------
    | Default Columns
    |--------------------------------------------------------------------------
    |
    | This is the default value for the columns configuration. It is used by
    | every icon picker, when not set explicitly.
    |
    | Can be either an integer from 1 - 12 or an array of integers
    | with breakpoints (default, sm, md, lg, xl, 2xl) as the key.
    |
    */
    'columns' => 3,
//     example:
//    'columns' => [
//        'default' => 1,
//        'lg' => 3,
//        '2xl' => 5,
//    ],

    /*
    |--------------------------------------------------------------------------
    | Default Layout
    |--------------------------------------------------------------------------
    |
    | This is the default value for the layout configuration. It is used by
    | every icon picker, when not set explicitly.
    |
    | FLOATING: The select will behave the same way as the default filament
    | select. It will show when selected and hide when clicked outside.
    |
    | ON_TOP: The select options will always be visible in a catalogue-like
    | grid view.
    |
    */
    'layout' => \Guava\FilamentIconPicker\Layout::FLOATING,

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | This section lets you configure the caching option of the plugin.
    |
    | Since icon packs are often packed with a lots of icons,
    | searching through all of them can take quite a lot of time, which is
    | why the plugin caches each field with it's configuration and search queries.
    |
    | This section let's you configure how caching should be done or disable it
    | if you wish.
    |
    */
    'cache' => [
        'enabled' => true,
        'duration' => '7 days',
    ],

];
