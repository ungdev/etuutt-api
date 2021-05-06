<?php

namespace App\Util;

use Faker\Factory;

class Text
{
    public static function createRandomText($nbOfParagraphs, $nbOfWordsPerParagraphs): string
    {
        $faker = Factory::create('fr_FR');

        $text = '';
        for ($i = 0; $i < $nbOfParagraphs; ++$i) {
            $text .= '<p>';
            for ($j = 0; $j < $nbOfWordsPerParagraphs; ++$j) {
                if (0 !== $j) {
                    $text .= ' ';
                }
                $text .= $faker->word;
            }
            $text .= str_repeat($faker->word, $nbOfWordsPerParagraphs);
            $text .= '</p>';
        }

        return $text;
    }

    public static function createRandomLine($nbOfWords): string
    {
        $faker = Factory::create('fr_FR');

        $text = $faker->word;
        for ($j = 0; $j < $nbOfWords; ++$j) {
            $text .= ' '.$faker->word;
        }

        return $text;
    }
}
