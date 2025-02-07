<?php
/**
 * Credit Ninja (https://creditninja.com).
 *
 * @link https://github.com/creditninja/creditninja source repository
 *
 * @copyright Copyright (c) 2021. Credit Ninja LLC (https://creditninja.com)
 *
 * @license https://www.elastic.co/licensing/elastic-license
 */

namespace App\Observers;

use App\Models\Client;
use App\Models\Credit;

class CreditObserver
{
    /**
     * Handle the client "created" event.
     *
     * @param Credit $credit
     * @return void
     */
    public function created(Credit $credit)
    {
    }

    /**
     * Handle the client "updated" event.
     *
     * @param Credit $credit
     * @return void
     */
    public function updated(Credit $credit)
    {
    }

    /**
     * Handle the client "deleted" event.
     *
     * @param Credit $credit
     * @return void
     */
    public function deleted(Credit $credit)
    {
    }

    /**
     * Handle the client "restored" event.
     *
     * @param Credit $credit
     * @return void
     */
    public function restored(Credit $credit)
    {
        //
    }

    /**
     * Handle the client "force deleted" event.
     *
     * @param Credit $credit
     * @return void
     */
    public function forceDeleted(Credit $credit)
    {
        //
    }
}
