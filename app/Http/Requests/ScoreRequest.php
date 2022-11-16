<?php

namespace App\Http\Requests;

use App\Models\Userconfig;
use Illuminate\Foundation\Http\FormRequest;

class ScoreRequest extends FormRequest
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
        $eventversion_id = Userconfig::getValue('eventversion', auth()->id());
        $min = ($eventversion_id * 10000);
        $max = ($min + 9999);

        //removed 'required' from all score-* values to give widest flexibility
        return [
            'registration_id' => ['required','numeric','exists:registrants,id','min:'.$min,'max:'.$max],
            'adjudicator_id' => ['required','numeric','exists:adjudicators,id'],
            'score-0' => ['numeric','min:1','max:9'],
            'score-1' => ['numeric','min:1','max:9'],
            'score-2' => ['numeric','min:1','max:9'],
            'score-3' => ['numeric','min:1','max:9'],
            'score-4' => ['numeric','min:1','max:9'],
            'score-5' => ['numeric','min:1','max:9'],
        ];
    }

    public function messages()
    {
        return [
            'registrant_id.exists' => 'Incorrect registration id.',
            'score-0.min' => 'The score must be between 1 - 9.',
            'score-1.min' => 'The score must be between 1 - 9.',
            'score-2.min' => 'The score must be between 1 - 9.',
            'score-3.min' => 'The score must be between 1 - 9.',
            'score-4.min' => 'The score must be between 1 - 9.',
            'score-5.min' => 'The score must be between 1 - 9.',
            'score-0.max' => 'The score must be between 1 - 9.',
            'score-1.max' => 'The score must be between 1 - 9.',
            'score-2.max' => 'The score must be between 1 - 9.',
            'score-3.max' => 'The score must be between 1 - 9.',
            'score-4.max' => 'The score must be between 1 - 9.',
            'score-5.max' => 'The score must be between 1 - 9.',
        ];
    }
}
