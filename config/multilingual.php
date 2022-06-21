<?php

return [
    /*
     * Define the default locale.
     */
    'defaultLocale' => env('LOCALE_DEFAULT', 'en'),

    /*
     * Define the locale identifier type.
     *
     * Possible values:
     * - session: Locale chosen by the user is stored in the session;
     * - domain: Domain will switch based of chosen locale (domains can be configured in localeDomains config);
     * - url: URL's change based of the chosen locale (routes will be prefixed with the locale like /en/example or /nl/example);
     */
    'localeIdentifierType' => env('LOCALE_IDENTIFIER_TYPE', 'session'),

    /*
     * Configure the available locales for your application.
     * Define as an array with locale as array key and label in language switcher as array value.
     */
    'availableLocales' => [
        'en' => 'English',
        'nl' => 'Dutch',
    ],

    /*
     * Locale domains (only used when localeIdentifierType is set to domain).
     */
    'localeDomains' => [
        'en' => env('LOCALE_EN_DOMAIN_URL', 'example.com'),
        'nl' => env('LOCALE_NL_DOMAIN_URL', 'example.nl'),
    ],
];
