<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11/1/2020
 * Time: 2:46 AM
 */

namespace App\Modules\Category;


class CategoryService
{
    const LETTERS = [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'j',
        'з' => 'z',
        'и' => 'i',
        'й' => 'yi',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'kh',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sh',
        'ъ' => '',
        'ы' => 'i',
        'ь' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
    ];

    public static function convertToLatin(string $title): string
    {
        $latinTitle = mb_strtolower(str_replace(' ', '', $title), 'UTF-8');

        return strtr($latinTitle, static::LETTERS);
    }
}