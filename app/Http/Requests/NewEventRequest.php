<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'auditioncount' => ['numeric','required','min:0','max:1'],
            'first_event' => ['string','required','min:4','max:4'],
            'frequency' => ['string','required'],
            'grades' => ['array','required','min:1'],
            'grades.*' => ['numeric','required','exists:gradetypes,id'],
            'logo_file' => ['string','nullable'],
            'logo_file_alt' => ['string','nullable'],
            'name' => ['string','required'],
            'organization_id' => ['numeric','required','exists:organizations,id'],
            'requiredheight' => ['numeric','required','min:0','max:1'],
            'requiredshirtsize' => ['numeric','required','min:0','max:1'],
            'short_name' => ['string','nullable'],
            'status' => ['string','required'],
        ];
    }
}
