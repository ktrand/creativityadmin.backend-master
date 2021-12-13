<?php

namespace App\Modules\Posts\Validations;

use App\Modules\Posts\Models\PostContent;
use Illuminate\Contracts\Validation\Rule;

class CanPublishPost implements Rule
{
    /**
     * @param string $attribute
     * @param mixed $postId
     * @return bool
     */
    public function passes($attribute, $postId)
    {
        return PostContent::where('post_id', $postId)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Не возможно опубликовать пость, сначало добавьте контент!';
    }
}
