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

namespace App\Services\Quote;

use App\Jobs\Entity\CreateEntityPdf;
use App\Models\ClientContact;
use App\Models\Quote;
use App\Services\AbstractService;
use Illuminate\Support\Facades\Storage;

class GetQuotePdf extends AbstractService
{
    public function __construct(Quote $quote, ClientContact $contact = null)
    {
        $this->quote = $quote;

        $this->contact = $contact;
    }

    public function run()
    {
        if (! $this->contact) {
            $this->contact = $this->quote->client->primary_contact()->first();
        }

        $invitation = $this->quote->invitations->where('client_contact_id', $this->contact->id)->first();

        $path = $this->quote->client->quote_filepath($invitation);

        $file_path = $path.$this->quote->numberFormatter().'.pdf';

        $disk = config('filesystems.default');

        $file_path = CreateEntityPdf::dispatchNow($invitation);
        
        return $file_path;
        //return Storage::disk($disk)->path($file_path);
    }
}
