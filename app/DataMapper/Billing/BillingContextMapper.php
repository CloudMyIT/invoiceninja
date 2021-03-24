<?php

/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2021. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\DataMapper\Billing;


class BillingContextMapper
{
    /**
     * @var int
     */
    public $billing_subscription_id;

    /**
     * @var string
     */
    public $email;

    /**
     * @var int
     */
    public $client_id;

    /**
     * @var int
     */
    public $invoice_id;

    /**
     * @var string[]
     */
    public $casts = [
        'billing_subscription_id' => 'integer',
        'email' => 'string',
        'client_id' => 'integer',
        'invoice_id' => 'integer',
    ];
}