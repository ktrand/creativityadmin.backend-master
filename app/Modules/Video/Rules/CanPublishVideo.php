<?php

namespace App\Modules\Video\Rules;

use App\Modules\Video\Models\Video;
use Illuminate\Contracts\Validation\Rule;

class CanPublishVideo implements Rule
{
    private $message = "Видео еще не загружено, пожалуйста загрузите видео.";
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $video = Video::find($value);

        if ($video->video)
            return true;

         return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
