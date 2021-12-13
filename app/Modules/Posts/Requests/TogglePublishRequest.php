<?php

namespace App\Modules\Posts\Requests;

use App\Modules\Posts\Validations\CanPublishPost;
use Illuminate\Foundation\Http\FormRequest;

class TogglePublishRequest extends FormRequest
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
            'postId' => ['required', new CanPublishPost()]
        ];
    }
}
