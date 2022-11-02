<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DEFAULT PROVIDER
    |--------------------------------------------------------------------------
    |
    | This value is the provider to use when generating the user's avatar url
    |
    */
    'default_provider' => 'ui-avatar',

    /*
    |--------------------------------------------------------------------------
    | PROVIDERS
    |--------------------------------------------------------------------------
    |
    | This value is the definition of the different avatar providers
    |
    */
    'providers' => [
        // UI Avatar provider (https://ui-avatars.com/)
        'ui-avatar' => [
            // Class used to generate the user avatar
            'class' => \Devaslanphp\FilamentAvatar\Core\UiAvatarsProvider::class,

            // UI Avatar source url
            'url' => 'https://ui-avatars.com/api/',

            // User's field used to generate avatar
            'name_field' => 'name',

            // Color used in url text color
            'text_color' => 'FFFFFF',

            // Background color used if the 'dynamic_bg_color' flag is false
            'bg_color' => '111827',

            // If 'true' the provider will generate a dynamic 'bg_color' based on user's name
            'dynamic_bg_color' => true,

            // HSL ranges
            // You can change them as you like to adapt the dynamic background color
            'hRange' => [0, 360],
            'sRange' => [50, 75],
            'lRange' => [25, 60],
        ],

        // Gravatar provider (https://gravatar.com)
        'gravatar' => [
            // Class used to generate the user avatar
            'class' => \Devaslanphp\FilamentAvatar\Core\GravatarProvider::class,

            // Gravatar source url
            'url' => 'https://www.gravatar.com/avatar/',

            // User's field used to generate avatar
            'name_field' => 'email'
        ],
    ],

];
