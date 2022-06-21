<?php

namespace Lankhaar\Multilingual\Service;

use Lankhaar\Multilingual\Enum\LocaleIdentifierType;
use Lankhaar\Multilingual\Exception\InvalidConfigException;

class ConfigService
{
    /**
     * @return LocaleIdentifierType
     * @throws InvalidConfigException
     */
    public function getLocaleIdentifierType(): LocaleIdentifierType
    {
        if (!is_string($localeIdentifierType = config('multilingual.localeIdentifierType', 'session'))) {
            throw InvalidConfigException::invalidType('localeIdentifierType', 'string', gettype($localeIdentifierType));
        }

        return match ($localeIdentifierType) {
            'session' => LocaleIdentifierType::Session,
            'domain' => LocaleIdentifierType::Domain,
            'url' => LocaleIdentifierType::Url,
            default => throw new InvalidConfigException('Unexpected value for localeIdentifierType.'),
        };
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function getLocales(): array
    {
        if (!is_array($locales = config('multilingual.availableLocales', []))) {
            throw InvalidConfigException::invalidType('availableLocales', 'array', gettype($locales));
        }

        return $locales;
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function getLocaleDomains(): array
    {
        if (!is_array($localeDomains = config('multilingual.localeDomains', []))) {
            throw InvalidConfigException::invalidType('localeDomains', 'array', gettype($localeDomains));
        }

        return $localeDomains;
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function getDefaultLocale(): string
    {
        if (!is_string($defaultLocale = config('multilingual.defaultLocale', 'en'))) {
            throw InvalidConfigException::invalidType('defaultLocale', 'string', gettype($defaultLocale));
        }

        return $defaultLocale;
    }
}
