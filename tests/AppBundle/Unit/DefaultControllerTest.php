<?php

namespace Tests\AppBundle\Unit;

use AppBundle\Controller\DefaultController;
use DateTime;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase {

    public function formatArrayDataProvider(){
        return [
            /* input, expected */
            [
                [
                    ['EventDate' => new DateTime('2019-11-12')],
                    ['EventDate' => new DateTime('2019-11-13')],
                    ['EventDate' => new DateTime('2019-11-14')],
                    ['EventDate' => new DateTime('2019-11-15')],
                    ['EventDate' => new DateTime('2019-11-16')]
                ],
                [
                    '2019-11-12',
                    '2019-11-13',
                    '2019-11-14',
                    '2019-11-15',
                    '2019-11-16'
                ]
            ]
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

    /**
     * @test
     * @dataProvider formatArrayDataProvider
     * @param array $input
     * @param array $expected
     */
    public function formatArray(array $input, array $expected){
        $actual = DefaultController::formatArray($input, function ($v) { return $v['EventDate']->format('Y-m-d');});
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider checkPayDateDataProvider
     * @param DateTime $input
     * @param DateTime $expected
     */
    public function checkPayDate(DateTime $input, DateTime $expected){
        $holidays = [
            '2019-01-01',
            '2019-01-21',
            '2019-02-18',
            '2019-05-27',
            '2019-07-04',
            '2019-07-05',
            '2019-09-02',
            '2019-10-14',
            '2019-11-11',
            '2019-11-28',
            '2019-11-29',
            '2019-12-25',
            '2019-12-24',
            '2019-12-31'
        ];
        $actual = DefaultController::checkPayDate($input, $holidays);
        $this->assertEquals($expected, $actual);
    }

}
