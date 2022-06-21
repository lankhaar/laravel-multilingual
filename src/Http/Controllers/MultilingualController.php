<?php

namespace Lankhaar\Multilingual\Http\Controllers;

use Lankhaar\Multilingual\Service\LocaleService;
use Symfony\Component\HttpFoundation\Response;

class MultilingualController
{
    public function __construct(
        protected LocaleService $localeService
    ) {}

    /**
     * Change locale to specified locale
     */
    public function changeLanguage(string $locale): Response
    {
        return $this->localeService->handleLocaleSwitch($locale);
    }
}
