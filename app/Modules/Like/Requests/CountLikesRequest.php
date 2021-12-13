<?php

namespace App\Modules\Like\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountLikesRequest extends FormRequest
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
            'model' => 'required|string',
            'model_id' => 'required|string',
        ];
    }
}
