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

namespace Tests\Feature\ClientPortal;


use App\Http\Livewire\InvoicesTable;
use App\Models\Account;
use App\Models\Client;
use App\Models\ClientContact;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class InvoicesTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function testInvoiceTableFilters()
    {
        $account = Account::factory()->create();

        $user = User::factory()->create(
            ['account_id' => $account->id, 'email' => $this->faker->safeEmail]
        );

        $company = Company::factory()->create(['account_id' => $account->id]);

        $client = Client::factory()->create(['company_id' => $company->id, 'user_id' => $user->id]);

        ClientContact::factory()->count(2)->create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'company_id' => $company->id,
        ]);

        $sent = Invoice::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'client_id' => $client->id,
            'status_id' => Invoice::STATUS_SENT,
        ]);

        $paid = Invoice::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'client_id' => $client->id,
            'status_id' => Invoice::STATUS_PAID,
        ]);

        $unpaid = Invoice::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'client_id' => $client->id,
            'status_id' => Invoice::STATUS_UNPAID,
        ]);

        $this->actingAs($client->contacts->first(), 'contact');

        Livewire::test(InvoicesTable::class, ['company' => $company])
            ->assertSee($sent->number)
            ->assertSee($paid->number)
            ->assertSee($unpaid->number);

        Livewire::test(InvoicesTable::class, ['company' => $company])
            ->set('status', ['paid'])
            ->assertSee($paid->number)
            ->assertDontSee($unpaid->number);
    }
}
