<?php
/**
 * Invoice Ninja (https://invoiceninja.com)
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2019. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\Http\Requests\Client;

use App\DataMapper\ClientSettings;
use App\Http\Requests\Request;
use App\Http\ValidationRules\ValidSettingsRule;
use App\Models\Client;
use Illuminate\Support\Facades\Log;

class StoreClientRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize() : bool
    {

        return auth()->user()->can('create', Client::class);

    }

    public function rules()
    {
        $this->sanitize();

        /* Ensure we have a client name, and that all emails are unique*/
        $rules['name'] = 'required|min:1';
        $rules['id_number'] = 'unique:clients,id_number,' . $this->id . ',id,company_id,' . $this->company_id;
        $rules['settings'] = new ValidSettingsRule();
        
        $contacts = request('contacts');

        if(is_array($contacts))
        {

            for ($i = 0; $i < count($contacts); $i++) {
                //$rules['contacts.' . $i . '.email'] = 'required|email|unique:client_contacts,email,' . isset($contacts[$i]['id']);
                $rules['contacts.' . $i . '.email'] = 'nullable|email';
            }

        }

        return $rules;
            
    }


    public function sanitize()
    {
        $input = $this->all();
        
        $input['settings'] = ClientSettings::defaults();
        
        $this->replace($input);   

    }

    public function messages()
    {
        return [
            'unique' => ctrans('validation.unique', ['attribute' => 'email']),
            //'required' => trans('validation.required', ['attribute' => 'email']),
            'contacts.*.email.required' => ctrans('validation.email', ['attribute' => 'email']),
        ];
    }


}