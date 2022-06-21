<?php

use Lankhaar\Multilingual\Service\ConfigService;

if (!function_exists('getMultilingualLocales')) {

    function getMultilingualLocales(): array {
        /** @var ConfigService $configService */
        $configService = app()->get(\Lankhaar\Multilingual\Service\ConfigService::class);

        return $configService->getLocales();
    }

}
