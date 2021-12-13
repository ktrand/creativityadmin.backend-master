<?php

namespace App\Modules\Posts\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'title' => 'required',
            'img' => 'required|image',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Все поля обязательны для заполнения!',
            'image' => 'Выберите картинку формата jpg, png',
        ];
    }
}
