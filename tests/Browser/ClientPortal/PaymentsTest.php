<?php

/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2021. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://www.elastic.co/licensing/elastic-license
 */

namespace Tests\Browser\ClientPortal;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ClientPortal\Login;
use Tests\DuskTestCase;

class PaymentsTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        foreach (static::$browsers as $browser) {
            $browser->driver->manage()->deleteAllCookies();
        }

        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new Login())
                ->auth();
        });
    }

    public function testPageLoads()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visitRoute('client.payments.index')
                ->assertSeeIn('span[data-ref="meta-title"]', 'Payments')
                ->visitRoute('client.logout');
        });
    }
}
