# Customization

In this section of the **Helper** platform documentation you can learn how to customize this platform.

The platform comes with a configuration file `config/system.php` where you can change some of the application behaviors.

## Platform languages

The configuration variable `config('system.locales.list')` contains all the languages (locales) that the platform can use.

So if you want to add more locales, you can update this variable to add/remove some languages.

```php
// ...
// Locales
'locales' => [
    // Locales list
    'list' => [
        'en' => 'English',
        'fr' => 'French',
        'ar' => 'Arabic',
        'az' => 'Azerbaijani',
        'be' => 'Belarusian',
        'bg' => 'Bulgarian',
        'bn' => 'Bengali',
        'bs' => 'Bosnian',
        'ca' => 'Catalan',
        'cs' => 'Czech',
        'cy' => 'Welsh',
        'da' => 'Danish',
        'de' => 'German',
        'el' => 'Greek',
        'es' => 'Spanish',
        'et' => 'Estonian',
        'eu' => 'Basque',
        'fa' => 'Persian',
        'fi' => 'Finnish',
        'fil' => 'Filipino',
        'gl' => 'Galician',
        'he' => 'Hebrew',
        'hi' => 'Hindi',
        'hr' => 'Croatian',
        'hu' => 'Hungarian',
        'hy' => 'Armenian',
        'id' => 'Indonesian',
        'is' => 'Icelandic',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'ka' => 'Georgian',
        'kk' => 'Kazakh',
        'km' => 'Central Khmer',
        'kn' => 'Kannada',
        'ko' => 'Korean',
        'lt' => 'Lithuanian',
        'lv' => 'Latvian',
        'mk' => 'Macedonian	',
        'mn' => 'Mongolian',
        'mr' => 'Marathi',
        'ms' => 'Malay',
        'nb' => 'Norwegian Bokmål',
        'ne' => 'Nepali',
        'nl' => 'Dutch',
        'pl' => 'Polish',
        'ps' => 'Pashto',
        'pt' => 'Portuguese',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'si' => 'Sinhala',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'sq' => 'Albanian',
        'sv' => 'Swedish',
        'sw' => 'Swahili',
        'tg' => 'Tajik',
        'th' => 'Thai',
        'tk' => 'Turkmen',
        'tl' => 'Tagalog',
        'tr' => 'Turkish',
        'ug' => 'Uighur',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'vi' => 'Vietnamese',
    ],
],
// ...
```

> For each language of those locales you will find a folder inside `lang/` folder named with the same key (e.g. `lang/fr/`), and a JSON file (e.g. `/lang/fr.json`).
> 
> So if you want to add another language, you need to add a folder and a JSON file associeted to it.

## Max file size

The platform **max file size** autorized is defined in the configuration variable `config('system.max_file_size')` (by default this value equals 10Mb), you can change it if you want.

## Projects configuration

The configuration file `config/system.php` contains also the projects configuration, like:

- Projects affectations roles: `config('system.projects.affectations.roles.list')`
- Projects affectations roles colors: `config('system.projects.affectations.roles.colors')`
- Projects affectations default role: `config('system.projects.affectations.default')`
- Projects affectations role with permissions to manage the project: `config('system.projects.affectations.can_manage')`

## Tickets configuration

The configuration file `config/system.php` contains also the tickets configuration, like:

- Tickets relations types: `config('system.tickets.relations.list')`
- Tickets relations types colors: `config('system.tickets.relations.colors')`
- Tickets relations default type: `config('system.tickets.relations.default')`

## Social authentication

To use the different social authentications that the platform provides you can update the configuration variable `config('filament-socialite.providers')` by adding or removing items:

```php
// ...
'providers' => [
    'facebook' => [
        'label' => 'Facebook',
        'icon' => 'fab-facebook-f',
    ],
    'github' => [
        'label' => 'GitHub',
        'icon' => 'fab-github',
    ],
    'google' => [
        'label' => 'Google',
        'icon' => 'fab-google',
    ],
    'twitter' => [
        'label' => 'Twitter',
        'icon' => 'fab-twitter',
    ],
],
// ...
```