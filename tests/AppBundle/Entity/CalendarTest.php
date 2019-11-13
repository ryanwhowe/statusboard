<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Calendar;
use \DateTime;

class CalendarTest extends \PHPUnit_Framework_TestCase {

    public function translateTypeDescriptionDataProvider(){
        return [
            /* input, expected */
            [new Calendar(['type' => Calendar::TYPE_COMPANY_HOLIDAY, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2019-11-28')]), 'TRUECar Holiday'],
            [new Calendar(['type' => Calendar::TYPE_COMPANY_HOLIDAY, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2015-11-28')]), 'Ives Holiday'],
            [new Calendar(['type' => Calendar::TYPE_COMPANY_HOLIDAY, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2012-11-28')]), 'Avention Holiday'],
            [new Calendar(['type' => Calendar::TYPE_COMPANY_HOLIDAY, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2006-11-28')]), 'JNJ Holiday'],

            [new Calendar(['type' => Calendar::TYPE_PAY_DATE, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2019-11-28')]), 'TRUECar Pay Date'],
            [new Calendar(['type' => Calendar::TYPE_PAY_DATE, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2015-11-28')]), 'Ives Pay Date'],
            [new Calendar(['type' => Calendar::TYPE_PAY_DATE, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2012-11-28')]), 'Avention Pay Date'],
            [new Calendar(['type' => Calendar::TYPE_PAY_DATE, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2006-11-28')]), 'JNJ Pay Date'],

            [new Calendar(['type' => Calendar::TYPE_PTO, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2019-11-28')]), 'PTO'],
            [new Calendar(['type' => Calendar::TYPE_SICK, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2019-11-28')]), 'Sick Day'],
            [new Calendar(['type' => Calendar::TYPE_NATIONAL_HOLIDAY, 'eventDate' => DateTime::createFromFormat('Y-m-d', '2019-11-28'), 'description' => 'Test']), 'Test'],
        ];
    }

    /**
     * @test
     * @dataProvider translateTypeDescriptionDataProvider
     * @param Calendar $input
     * @param string $expected
     * @throws \Exception
     */
    public function translateTypeDescription(Calendar $input, string $expected){
        $actual = Calendar::translateTypeDescription($input);
        $this->assertEquals($expected, $actual);
    }
}
