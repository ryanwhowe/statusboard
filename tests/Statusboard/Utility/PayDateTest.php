<?php

namespace Tests\Statusboard\Utility;

use Statusboard\Utility\PayDate;
use \DateTime;

class PayDateTest extends \PHPUnit_Framework_TestCase {

    public static function getTestHolidays(int $year = 2019){
        switch ($year){
            case 2019:
                return [
                    '2019-01-01', '2019-01-21',
                    '2019-02-18',
                    '2019-05-27',
                    '2019-07-04', '2019-07-05',
                    '2019-09-02',
                    '2019-10-14',
                    '2019-11-11', '2019-11-28', '2019-11-29',
                    '2019-12-25', '2019-12-24', '2019-12-31'
                ];
                break;
            case 2015:
                return [
                    '2015-01-01', '2015-01-19',
                    '2015-02-16',
                    '2015-05-25',
                    '2015-07-03', '2015-07-04',
                    '2015-09-07',
                    '2015-10-12',
                    '2015-11-11', '2015-11-26',
                    '2015-12-25',
                ];
                break;
            case 2007:
                return [
                    '2007-01-01', '2007-01-15',
                    '2007-02-19',
                    '2007-05-28',
                    '2007-07-04',
                    '2007-09-03',
                    '2007-10-08',
                    '2007-11-11', '2007-11-22',
                    '2007-12-25'];
        }

    }

    public function employerByDateDataProvider(){
        return [
            /* input, expected */
            [new DateTime('2019-11-07'), PayDate::EMPLOYER_TRUECAR],
            [new DateTime('2019-05-27'), PayDate::EMPLOYER_TRUECAR],
            [new DateTime('2015-08-31'), PayDate::EMPLOYER_IVES],
            [new DateTime('2017-03-07'), PayDate::EMPLOYER_IVES],
            [new DateTime('2019-03-07'), PayDate::EMPLOYER_IVES],
            [new DateTime('2007-06-01'), PayDate::EMPLOYER_AVENTION],
            [new DateTime('2013-08-01'), PayDate::EMPLOYER_AVENTION],
            [new DateTime('2015-08-01'), PayDate::EMPLOYER_AVENTION],
            [new DateTime('2007-05-01'), PayDate::EMPLOYER_JNJ],
        ];
    }

    public function getEmployerByConstantDataProvider(){
        return [
            /*  input, short, expected */
            [PayDate::EMPLOYER_JNJ, true, 'JNJ'],
            [PayDate::EMPLOYER_JNJ, false, 'JNJ Industries, Inc.'],

            [PayDate::EMPLOYER_AVENTION, true, 'Avention'],
            [PayDate::EMPLOYER_AVENTION, false, 'Avention, Inc.'],

            [PayDate::EMPLOYER_IVES, true, 'Ives'],
            [PayDate::EMPLOYER_IVES, false, 'Ives Group, Inc.'],

            [PayDate::EMPLOYER_TRUECAR, true, 'TRUECar'],
            [PayDate::EMPLOYER_TRUECAR, false, 'TRUECar, Inc.'],
        ];
    }

    public function checkPayDateDataProvider(){
        return [
            /* input, expected */
            [new DateTime('2019-04-14'), new DateTime('2019-04-12')],
            [new DateTime('2019-03-30'), new DateTime('2019-03-29')],
            [new DateTime('2019-06-29'), new DateTime('2019-06-28')],
            [new DateTime('2019-07-14'), new DateTime('2019-07-12')],
            [new DateTime('2019-09-14'), new DateTime('2019-09-13')],
            [new DateTime('2019-09-29'), new DateTime('2019-09-27')],
            [new DateTime('2019-10-14'), new DateTime('2019-10-11')],
            [new DateTime('2019-10-30'), new DateTime('2019-10-30')],
            [new DateTime('2019-11-29'), new DateTime('2019-11-27')],
            [new DateTime('2019-12-14'), new DateTime('2019-12-13')],

        ];
    }

    public function generateFourteenthSecondToLastPayDatesDataProvider() {
        return [
            /* input, expected */
            [DateTime::createFromFormat('Y-m-d', '2019-09-30'), ['2019-09-13', '2019-09-27']],
            [DateTime::createFromFormat('Y-m-d', '2019-10-31'), ['2019-10-11', '2019-10-30']],
            [DateTime::createFromFormat('Y-m-d', '2019-11-12'), ['2019-11-14', '2019-11-27']],
            [DateTime::createFromFormat('Y-m-d', '2019-12-03'), ['2019-12-13', '2019-12-30']]
        ];
    }

    public function generateFridayPayDatesDataProvider(){
        return [
            /* input, even, expected */
            [DateTime::createFromFormat('Y-m-d', '2019-01-05'), false, ['2019-01-04', '2019-01-18']],
            [DateTime::createFromFormat('Y-m-d', '2019-02-28'), false, ['2019-02-01', '2019-02-15']],
            [DateTime::createFromFormat('Y-m-d', '2015-06-03'), true, ['2015-06-12', '2015-06-26']],
            [DateTime::createFromFormat('Y-m-d', '2015-05-03'), true, ['2015-05-01', '2015-05-15', '2015-05-29']],
            [DateTime::createFromFormat('Y-m-d', '2015-05-03'), null, ['2015-05-01', '2015-05-08', '2015-05-15', '2015-05-22', '2015-05-29']],
        ];
    }

    public function generatePayDatesInYearDataProvider(){
        return [
            /* year, expected */
            [
                2007,
                [
                    DateTime::createFromFormat('!Y-m-d','2007-01-05'),
                    DateTime::createFromFormat('!Y-m-d','2007-01-12'),
                    DateTime::createFromFormat('!Y-m-d','2007-01-19'),
                    DateTime::createFromFormat('!Y-m-d','2007-01-26'),
                    DateTime::createFromFormat('!Y-m-d','2007-02-02'),
                    DateTime::createFromFormat('!Y-m-d','2007-02-09'),
                    DateTime::createFromFormat('!Y-m-d','2007-02-16'),
                    DateTime::createFromFormat('!Y-m-d','2007-02-23'),
                    DateTime::createFromFormat('!Y-m-d','2007-03-02'),
                    DateTime::createFromFormat('!Y-m-d','2007-03-09'),
                    DateTime::createFromFormat('!Y-m-d','2007-03-16'),
                    DateTime::createFromFormat('!Y-m-d','2007-03-23'),
                    DateTime::createFromFormat('!Y-m-d','2007-03-30'),
                    DateTime::createFromFormat('!Y-m-d','2007-04-06'),
                    DateTime::createFromFormat('!Y-m-d','2007-04-13'),
                    DateTime::createFromFormat('!Y-m-d','2007-04-20'),
                    DateTime::createFromFormat('!Y-m-d','2007-04-27'),
                    DateTime::createFromFormat('!Y-m-d','2007-05-04'),
                    DateTime::createFromFormat('!Y-m-d','2007-05-11'),
                    DateTime::createFromFormat('!Y-m-d','2007-05-18'),
                    DateTime::createFromFormat('!Y-m-d','2007-05-25'),
                    DateTime::createFromFormat('!Y-m-d','2007-06-01'),
                    DateTime::createFromFormat('!Y-m-d','2007-06-15'),
                    DateTime::createFromFormat('!Y-m-d','2007-06-29'),
                    DateTime::createFromFormat('!Y-m-d','2007-07-13'),
                    DateTime::createFromFormat('!Y-m-d','2007-07-27'),
                    DateTime::createFromFormat('!Y-m-d','2007-08-10'),
                    DateTime::createFromFormat('!Y-m-d','2007-08-24'),
                    DateTime::createFromFormat('!Y-m-d','2007-09-07'),
                    DateTime::createFromFormat('!Y-m-d','2007-09-21'),
                    DateTime::createFromFormat('!Y-m-d','2007-10-05'),
                    DateTime::createFromFormat('!Y-m-d','2007-10-19'),
                    DateTime::createFromFormat('!Y-m-d','2007-11-02'),
                    DateTime::createFromFormat('!Y-m-d','2007-11-16'),
                    DateTime::createFromFormat('!Y-m-d','2007-11-30'),
                    DateTime::createFromFormat('!Y-m-d','2007-12-14'),
                    DateTime::createFromFormat('!Y-m-d','2007-12-28'),
                ]
            ],
            [2015,
                [
                    DateTime::createFromFormat('!Y-m-d','2015-01-09'),
                    DateTime::createFromFormat('!Y-m-d','2015-01-23'),
                    DateTime::createFromFormat('!Y-m-d','2015-02-06'),
                    DateTime::createFromFormat('!Y-m-d','2015-02-20'),
                    DateTime::createFromFormat('!Y-m-d','2015-03-06'),
                    DateTime::createFromFormat('!Y-m-d','2015-03-20'),
                    DateTime::createFromFormat('!Y-m-d','2015-04-03'),
                    DateTime::createFromFormat('!Y-m-d','2015-04-17'),
                    DateTime::createFromFormat('!Y-m-d','2015-05-01'),
                    DateTime::createFromFormat('!Y-m-d','2015-05-15'),
                    DateTime::createFromFormat('!Y-m-d','2015-05-29'),
                    DateTime::createFromFormat('!Y-m-d','2015-06-12'),
                    DateTime::createFromFormat('!Y-m-d','2015-06-26'),
                    DateTime::createFromFormat('!Y-m-d','2015-07-10'),
                    DateTime::createFromFormat('!Y-m-d','2015-07-24'),
                    DateTime::createFromFormat('!Y-m-d','2015-09-11'),
                    DateTime::createFromFormat('!Y-m-d','2015-09-25'),
                    DateTime::createFromFormat('!Y-m-d','2015-10-09'),
                    DateTime::createFromFormat('!Y-m-d','2015-10-23'),
                    DateTime::createFromFormat('!Y-m-d','2015-11-06'),
                    DateTime::createFromFormat('!Y-m-d','2015-11-20'),
                    DateTime::createFromFormat('!Y-m-d','2015-12-04'),
                    DateTime::createFromFormat('!Y-m-d','2015-12-18'),
                ]
            ],
            [
                2019,
                [
                    DateTime::createFromFormat('!Y-m-d','2019-01-04'),
                    DateTime::createFromFormat('!Y-m-d','2019-01-18'),
                    DateTime::createFromFormat('!Y-m-d','2019-02-01'),
                    DateTime::createFromFormat('!Y-m-d','2019-02-15'),
                    DateTime::createFromFormat('!Y-m-d','2019-03-01'),
                    DateTime::createFromFormat('!Y-m-d','2019-05-30'),
                    DateTime::createFromFormat('!Y-m-d','2019-06-14'),
                    DateTime::createFromFormat('!Y-m-d','2019-06-28'),
                    DateTime::createFromFormat('!Y-m-d','2019-07-12'),
                    DateTime::createFromFormat('!Y-m-d','2019-07-30'),
                    DateTime::createFromFormat('!Y-m-d','2019-08-14'),
                    DateTime::createFromFormat('!Y-m-d','2019-08-30'),
                    DateTime::createFromFormat('!Y-m-d','2019-09-13'),
                    DateTime::createFromFormat('!Y-m-d','2019-09-27'),
                    DateTime::createFromFormat('!Y-m-d','2019-10-11'),
                    DateTime::createFromFormat('!Y-m-d','2019-10-30'),
                    DateTime::createFromFormat('!Y-m-d','2019-11-14'),
                    DateTime::createFromFormat('!Y-m-d','2019-11-27'),
                    DateTime::createFromFormat('!Y-m-d','2019-12-13'),
                    DateTime::createFromFormat('!Y-m-d','2019-12-30'),
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function generateDatesInYear() {
        $expected = [
            DateTime::createFromFormat('!Y z', '2019 360'),
            DateTime::createFromFormat('!Y z', '2019 361'),
            DateTime::createFromFormat('!Y z', '2019 362'),
            DateTime::createFromFormat('!Y z', '2019 363'),
            DateTime::createFromFormat('!Y z', '2019 364')
        ];
        $actual = PayDate::generateDatesInYear(2019, 360);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider getEmployerByConstantDataProvider
     * @param int $test_constant
     * @param bool $short
     * @param string $expected
     * @throws \Exception
     */
    public function getEmployerByConstant(int $test_constant, bool $short, string $expected) {
        $actual = PayDate::getEmployerByConstant($test_constant, $short);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider checkPayDateDataProvider
     * @param DateTime $input
     * @param DateTime $expected
     * @throws \Exception
     */
    public function checkPayDate(DateTime $input, DateTime $expected) {
        $holidays = self::getTestHolidays();
        $actual = PayDate::checkPayDate($input, $holidays);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider employerByDateDataProvider
     * @param DateTime $test_date
     * @param int $expected
     * @throws \Exception
     */
    public function getEmployerByDate(DateTime $test_date, int $expected) {
        $actual = PayDate::getEmployerByDate($test_date);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider generateFourteenthSecondToLastPayDatesDataProvider
     * @param DateTime $input
     * @param array $expected
     * @throws \Exception
     */
    public function generateFourteenthSecondToLastPayDates(DateTime $input, array $expected) {
        $holidays = self::getTestHolidays();
        $actual = PayDate::generateFourteenthSecondToLastPayDates($input, $holidays);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider generateFridayPayDatesDataProvider
     * @param DateTime $input
     * @param bool|null $even
     * @param array $expected
     */
    public function generateFridayPayDates(DateTime $input, $even, array $expected) {
        $holidays = self::getTestHolidays();
        $actual = PayDate::generateFridayPayDates($input, $holidays, $even);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider generatePayDatesInYearDataProvider
     * @param int $year
     * @param array $expected
     */
    public function generatePayDatesInYear(int $year, array $expected){
        $holidays = self::getTestHolidays();
        $actual = PayDate::generatePayDatesInYear($year, $holidays);
        $this->assertEquals($expected, $actual);
    }
}

