<?php
/**
 * Created by PhpStorm.
 * User: vichanse
 * Date: 22/02/2018
 * Time: 04:45
 */

namespace Calendar\Model;

class LeapYear
{
    public function isLeapYear($year = null)
    {
        if (null === $year) {
            $year = date('Y');
        }

        return 0 == $year % 400 || (0 == $year % 4 && 0 != $year % 100);
    }
}