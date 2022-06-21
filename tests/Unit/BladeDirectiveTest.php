<?php

namespace Lankhaar\Multilingual\Tests\Unit;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Lankhaar\Multilingual\Tests\TestCase;

class BladeDirectiveTest extends TestCase
{
    use InteractsWithViews;

    /** @test */
    public function multilingualSwitcherContainsConfiguredLocales()
    {
        // Execute blade directive
        $multilingualSwitcherBladeFunctionOutput = $this->blade("@multilingualSwitcher");

        // Assert both locales configured in Testcase
        $multilingualSwitcherBladeFunctionOutput->assertSee('<a class="dropdown-item" href="http://example.com/multilingual/switch/en">English</a>', false);
        $multilingualSwitcherBladeFunctionOutput->assertSee('<a class="dropdown-item" href="http://example.com/multilingual/switch/nl">Dutch</a>', false);
    }

    /** @test */
    public function multilingualSwitcherContainsNoLocalesIfOneConfigured()
    {
        // Configure only 1 locale for app
        $this->app['config']->set('multilingual.availableLocales', [
            'en' => 'English',
        ]);

        // Execute blade directive
        $multilingualSwitcherBladeFunctionOutput = $this->blade("@multilingualSwitcher");

        // Assert if no locales are displayed
        $multilingualSwitcherBladeFunctionOutput->assertDontSee('<a class="dropdown-item"', false);
    }
}
