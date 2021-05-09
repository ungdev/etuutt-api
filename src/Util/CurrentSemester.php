<?php

namespace App\Util;

class CurrentSemester
{
    private static $currentSemesterCode = 'P21';

    public static function setCurrentSemesterCode(string $semesterCode)
    {
        CurrentSemester::$currentSemesterCode = $semesterCode;
    }

    public static function getCurrentSemesterCode()
    {
        return CurrentSemester::$currentSemesterCode;
    }
}
