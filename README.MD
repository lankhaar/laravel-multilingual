## Configurable language switcher delivered to your multilingual laravel application

* [Installation](#installation)
* [Usage](#usage)
    * [Configure available locales](#configure-available-locales)
    * [Display language switcher in navigation](#display-language-switcher-in-navigation)
    * [How to create your own language switcher layout](#create-your-own-language-switcher-layout)
* [Testing](#testing)
* [Changelog](#changelog)
* [Supported features](#supported-features)
* [Advanced configurations](#supported-features)

## Installation

You can install the package via composer:

```bash
composer require lankhaar/laravel-multilingual
```

Then publish the configurations:

```bash
php artisan vendor:publish --provider="Lankhaar\Multilingual\MultilingualServiceProvider"
```

## Usage

### Configure available locales
You can edit the config/multilingual.php file to configure the available locales as follows:

```php
    'availableLocales' => [
        'en' => 'English',
        'nl' => 'Dutch',
    ],
```

### Display language switcher in navigation
The current language switcher is only compatible with bootstrap navbars.
If you're not using bootstrap in your project, you can easily [create your
own layout for it](#create-your-own-language-switcher-layout). If you are using bootstrap, just call `@multilingualSwitcher` in your navbar like this:

```html
<nav id="navbar" class="navbar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('about') }}">{{ __('About') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('services') }}">{{ __('Services') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('contact') }}">{{ __('Contact') }}</a>
        </li>
        @multilingualSwitcher
    </ul>
</nav>
```

### Create your own language switcher layout
As stated earlier, the standard layout shipped with the package is specifically designed to fit in a
bootstrap navigation. If you aren't using bootstrap, you're probably better off creating your own layout.

Create a file in your project at `resources/views/vendor/laravel-multilingual/language-switcher.blade.php`
to overwrite the existing language switcher template. See code snippet below for an example on
how to loop over the available locales and link to them.

```html
@if(count($locales = getMultilingualLocales()) > 1)
    <li class="dropdown">
        <a href="#"><span>{{ __('Language') }}</span> <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
        <ul>
            @foreach($locales as $locale => $label)
                <li><a href="{{ route('switch-locale', ['locale' => $locale]) }}">{{ __($label) }}</a></li>
            @endforeach
        </ul>
    </li>
@endif
```

Now you can call it wherever you want with the `@multilingualSwitcher` blade directive

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


### Supported features
Feel free to creat issues for feature requests!

- [x] Support languages per session (No changes in domain or URL structure);
- [x] Support languages per domain (`example.com`, `example.nl`);
- [x] Support languages in url (`/en/example`, `/nl/example`);


### Advanced config

```php
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
```
