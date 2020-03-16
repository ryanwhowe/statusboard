<?php
namespace Statusboard\Utility;
use \DateTime;
use \Exception;
/**
 * Class PayDate
 *
 * @author Ryan W. Howe <ryanwhowe@gmail.com>
 * @package Utility
 */
class PayDate {

    const EMPLOYER_TRUECAR = 4;
    const EMPLOYER_IVES = 3;
    const EMPLOYER_AVENTION = 2;
    const EMPLOYER_JNJ = 1;

    const ONE_DAY_SECONDS = 86400;

    /**
     * Return an array of DateTime objects for every day of the year starting from the passed start_day, which defaults
     * to 0.  There is no validation on the year passed
     *
     * @param int $year year to generate DateTime objects from, expects a four digit positive year.
     * @param int $start_day 0 based day of the year to start the count from.
     * @return array an array of DateTime objects from January 1st to December 31st of the year passed.
     */
    public static function generateDatesInYear(int $year, int $start_day = 0){
        $dates = [];
        foreach (range($start_day, (int)DateTime::createFromFormat("Y-m-d","${year}-12-31")->format('z')) as $dayOfYear) {
            $dates[] = DateTime::createFromFormat('!Y z', "${year} ${dayOfYear}");
        }
        return $dates;
    }

    /**
     * Return the Employer class constant by date.
     * @param DateTime $date
     * @return int|null Employer constant PayDate::EMPLOYER_* or null for unemployed
     * @throws Exception
     */
    public static function getEmployerByDate(DateTime $date){
        if($date >= new DateTime('2019-05-27')) return self::EMPLOYER_TRUECAR;
        if($date >= new DateTime('2015-08-31') && $date <= new DateTime('2019-03-07')) return self::EMPLOYER_IVES;
        if($date >= new DateTime('2007-06-01') && $date <= new DateTime('2015-08-01')) return self::EMPLOYER_AVENTION;
        if($date <= new DateTime('2007-05-31')) return self::EMPLOYER_JNJ;
        return null;
    }

    /**
     * Get the Text name of the employer from the passed Employer constant, if the $short_name is set to false the full
     * name of the employer will be returned
     *
     * @param int $employerConstant PayDate::EMPLOYER_*
     * @param bool $short_name defaults to true to return the short name of the employer
     * @return string|null
     * @throws Exception
     */
    public static function getEmployerByConstant(int $employerConstant, bool $short_name = true){
        switch($employerConstant) {
            case self::EMPLOYER_JNJ:
                return ($short_name) ? "JNJ" : "JNJ Industries, Inc.";
                break;
            case self::EMPLOYER_AVENTION:
                return ($short_name) ? "Avention" : "Avention, Inc.";
                break;
            case self::EMPLOYER_IVES:
                return ($short_name) ? "Ives" : "Ives Group, Inc.";
                break;
            case self::EMPLOYER_TRUECAR:
                return ($short_name) ? "TrueCar" : "TrueCar, Inc.";
                break;
            case null:
                return null;
                break;
        }
        throw new \Exception("Unknown Employer Constant, Unable to get name");
    }

    /**
     * Recursively check the passed date to ensure that it does not fall on a holiday or a weekend day.
     *
     * @param DateTime $pay_date
     * @param array $holidays dates of holidays in 'Y-m-d' format
     * @return DateTime the updated pay_date based on the holidays and weekend dates
     * @throws Exception
     */
    public static function checkPayDate(DateTime $pay_date, array $holidays){
        $moved = false;
        $new_date = $pay_date;
        if(in_array(date('w', $pay_date->getTimeStamp()), [0,6])){ // check is this a weekend day
            $new_date = new DateTime(date('Y-m-d',$pay_date->getTimestamp() - self::ONE_DAY_SECONDS));
            $moved = true;
        }
        if(!$moved){
            if(in_array($pay_date->format('Y-m-d'), $holidays)){ // check is this a holiday
                $new_date = new DateTime(date('Y-m-d',$pay_date->getTimestamp() - self::ONE_DAY_SECONDS));
            }
        }
        if($new_date === $pay_date) return $new_date;
        return self::checkPayDate($new_date, $holidays);
    }

    /**
     * Generate the fourteenth and second to last pay dates for the month that the passed date is part of.
     *
     * @param DateTime $check_date
     * @param array $holidays
     * @return array of the two pay dates in "Y-m-d" format
     * @throws Exception
     */
    public static function generateFourteenthSecondToLastPayDates(DateTime $check_date, array $holidays){
        $fourteenth = new DateTime ($check_date->format('Y') . "-" . $check_date->format('m') . "-14");
        $second_to_last_day = new DateTime($check_date->format('Y') . '-' . $check_date->format('m') . '-' . ((int)$check_date->format('t')-1));

        $fourteenth_pay_day = self::checkPayDate($fourteenth, $holidays);
        $second_to_last_pay_day = self::checkPayDate($second_to_last_day, $holidays);

        return [
            $fourteenth_pay_day->format('Y-m-d'),
            $second_to_last_pay_day->format('Y-m-d')
        ];
    }


    /**
     * Generate the pay dates for the month that the passed date is part of.
     * @param DateTime $check_date
     * @param array $holidays
     * @param bool|null $even is the pay schedule on even weeks, if false then odd weeks used, for null ALL fridays are used
     * @return array of the pay dates in "Y-m-d" format after going through checkPayDate()
     */
    public static function generateFridayPayDates(DateTime $check_date, array $holidays, $even){

        $dates = self::generateDatesInMonth($check_date);
        $dates = array_filter($dates, function($v){ /* filter out all dates but friday */
            return $v->format('w') === '5';
        });
        $pay_dates = array_map(function($v) use ($even, $holidays){
            if(($even !== null && ($v)->format('W') % 2 != $even)|| $even === null) {
                return self::checkPayDate($v, $holidays)->format('Y-m-d');
            }
        }, $dates);

        return array_values(array_filter($pay_dates));
    }

    /**
     * Generate an array of dates for the year month of the date given.
     *
     * @param DateTime $check_date
     * @return array an array of DateTime objects from the first day of the month to the last day of the month for the year month given.
     */
    private static function generateDatesInMonth(DateTime $check_date) {
        $dates = [];
        $year = (int)$check_date->format('Y');
        $month = (int)$check_date->format('m');
        $last_day_of_month = (int)$check_date->format('t');
        $first_day_of_month = (int)DateTime::createFromFormat("Y-m-d","${year}-${month}-01")->format('z');
        $last_day_of_month = (int)DateTime::createFromFormat("Y-m-d","${year}-${month}-${last_day_of_month}")->format('z');
        foreach (range($first_day_of_month, $last_day_of_month) as $dayOfYear) {
            $dates[] = DateTime::createFromFormat('!Y z', "${year} ${dayOfYear}");
        }
        return $dates;
    }

    public static function generatePayDatesInYear(int $year, array $holiday){
        $dates = self::generateDatesInYear($year);
        $new_month_employer = true;
        $month = 0;
        $employer = null;
        $employer_pay_dates = [];
        $year_pay_dates = [];
        foreach ($dates as $date) {
            if ($month !== (int)$date->format('m')){
                $month = (int)$date->format('m');
                $new_month_employer = true;
            }
            if( $employer !== self::getEmployerByDate($date)) {
                $employer = self::getEmployerByDate($date);
                $new_month_employer = true;
            }
            if($new_month_employer) {
                $employer_pay_dates = self::getEmployerPayDatesInMonth($employer, $date, $holiday);
            }

            if(in_array($date->format('Y-m-d'), $employer_pay_dates)){
                $year_pay_dates[] = $date;
            }
        }
        return $year_pay_dates;
    }

    public static function getEmployerPayDatesInMonth($employer, DateTime $date, array $holiday) {
        if($employer === null) return [];
        switch ($employer){
            case self::EMPLOYER_TRUECAR:
                return self::generateFourteenthSecondToLastPayDates($date, $holiday);
                break;
            case self::EMPLOYER_IVES:
                return self::generateFridayPayDates($date, $holiday, false);
                break;
            case self::EMPLOYER_AVENTION:
                return self::generateFridayPayDates($date, $holiday, true);
                break;
            case self::EMPLOYER_JNJ:
                return self::generateFridayPayDates($date, $holiday, null);
                break;
        }
    }

}