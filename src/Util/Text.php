<?php

namespace App\Util;

use Faker\Factory;

class Text
{
    public static function createRandomText($nbOfParagraphs, $nbOfWordsPerParagraphs): string
    {
        $faker = Factory::create('fr_FR');

        $text = '';
        for ($j = 0; $j < $nbOfParagraphs; ++$j) {
            $text .= '<p>';
            $text .= str_repeat($faker->word, $nbOfWordsPerParagraphs);
            $text .= '</p>';
        }

        return $text;
    }
}
