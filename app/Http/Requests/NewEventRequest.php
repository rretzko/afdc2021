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
            'name' => ['string','required'],
            'shortname' => ['string','nullable'],
            'organization_id' => ['numeric','required','organizations:id'],
            'auditioncount' => ['numeric','required','min:0','max:1'],
        ];
    }
}
