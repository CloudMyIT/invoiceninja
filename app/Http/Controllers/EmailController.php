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

namespace App\Http\Controllers;

use App\Events\Credit\CreditWasEmailed;
use App\Events\Quote\QuoteWasEmailed;
use App\Http\Requests\Email\SendEmailRequest;
use App\Jobs\Entity\EmailEntity;
use App\Models\Credit;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\RecurringInvoice;
use App\Transformers\CreditTransformer;
use App\Transformers\InvoiceTransformer;
use App\Transformers\QuoteTransformer;
use App\Transformers\RecurringInvoiceTransformer;
use App\Utils\Ninja;
use App\Utils\Traits\MakesHash;
use Illuminate\Http\Response;

class EmailController extends BaseController
{
    use MakesHash;

    protected $entity_type = Invoice::class;

    protected $entity_transformer = InvoiceTransformer::class;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns a template filled with entity variables.
     *
     * @param SendEmailRequest $request
     * @return Response
     *
     * @OA\Post(
     *      path="/api/v1/emails",
     *      operationId="sendEmailTemplate",
     *      tags={"emails"},
     *      summary="Sends an email for an entity",
     *      description="Sends an email for an entity",
     *      @OA\Parameter(ref="#/components/parameters/X-Api-Secret"),
     *      @OA\Parameter(ref="#/components/parameters/X-Requested-With"),
     *      @OA\RequestBody(
     *         description="The template subject and body",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="subject",
     *                     description="The email subject",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="body",
     *                     description="The email body",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="entity",
     *                     description="The entity name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="entity_id",
     *                     description="The entity_id",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="template",
     *                     description="The template required",
     *                     type="string",
     *                 ),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\Header(header="X-MINIMUM-CLIENT-VERSION", ref="#/components/headers/X-MINIMUM-CLIENT-VERSION"),
     *          @OA\Header(header="X-RateLimit-Remaining", ref="#/components/headers/X-RateLimit-Remaining"),
     *          @OA\Header(header="X-RateLimit-Limit", ref="#/components/headers/X-RateLimit-Limit"),
     *          @OA\JsonContent(ref="#/components/schemas/Template"),
     *       ),
     *       @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError"),
     *       ),
     *       @OA\Response(
     *           response="default",
     *           description="Unexpected Error",
     *           @OA\JsonContent(ref="#/components/schemas/Error"),
     *       ),
     *     )
     */
    public function send(SendEmailRequest $request)
    {
        $entity = $request->input('entity');
        $entity_obj = $entity::find($request->input('entity_id'));
        $subject = $request->input('subject');
        $body = $request->input('body');
        $entity_string = strtolower(class_basename($entity_obj));
        $template = str_replace("email_template_", "", $request->input('template'));

        $data = [
            'subject' => $subject,
            'body' => $body
        ];

        $entity_obj->invitations->each(function ($invitation) use ($data, $entity_string, $entity_obj, $template) {
            if ($invitation->contact->send_email && $invitation->contact->email) {
                $entity_obj->service()->markSent()->save();

                EmailEntity::dispatch($invitation->fresh(), $invitation->company, $template, $data)
                            ->delay(now()->addSeconds(30));
            }
        });

        $entity_obj->last_sent_date = now();

        $entity_obj->save();

        /*Only notify the admin ONCE, not once per contact/invite*/
        if ($entity_obj instanceof Invoice) {
            $this->entity_type = Invoice::class;
            $this->entity_transformer = InvoiceTransformer::class;

            if ($entity_obj->invitations->count() >= 1) {
                $entity_obj->entityEmailEvent($entity_obj->invitations->first(), 'invoice', $template);
            }
        }

        if ($entity_obj instanceof Quote) {
            $this->entity_type = Quote::class;
            $this->entity_transformer = QuoteTransformer::class;

            if ($entity_obj->invitations->count() >= 1) {
                event(new QuoteWasEmailed($entity_obj->invitations->first(), $entity_obj->company, Ninja::eventVars(auth()->user() ? auth()->user()->id : null), 'quote'));
            }
        }

        if ($entity_obj instanceof Credit) {
            $this->entity_type = Credit::class;
            $this->entity_transformer = CreditTransformer::class;

            if ($entity_obj->invitations->count() >= 1) {
                event(new CreditWasEmailed($entity_obj->invitations->first(), $entity_obj->company, Ninja::eventVars(auth()->user() ? auth()->user()->id : null), 'credit'));
            }
        }

        if ($entity_obj instanceof RecurringInvoice) {
            $this->entity_type = RecurringInvoice::class;
            $this->entity_transformer = RecurringInvoiceTransformer::class;
        }

        return $this->itemResponse($entity_obj->fresh());
    }
}
