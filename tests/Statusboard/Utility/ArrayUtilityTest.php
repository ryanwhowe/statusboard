<?php

namespace Tests\Statusboard\Utility;

use Statusboard\Utility\ArrayUtility;
use \DateTime;

class ArrayUtilityTest extends \PHPUnit_Framework_TestCase {

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

    public function csvToAssociativeArrayDataProvider() {
        return [
            [
                /* input, expected */
                [
                    'Make,Model,Description,Model_Code,Model#,12-35,36-47,48-59,60+,12-24,27,36,39,42,48,60,Admin Fee' . "\r\n",
                    ',,4dr Wgn Limited V6,\'8648,820224,7.34%,6.29%,6.24%,5.89%,71,69,64,63,61,58,50,$595' . "\r\n",
                    ',,4dr Wgn SR5 Premium V6,\'8646,820224,7.34%,6.29%,6.24%,5.89%,72,70,65,64,62,59,51,$595' . "\r\n"
                ],
                [
                    [
                        'Make' => '',
                        'Model' => '',
                        'Description' => '4dr Wgn Limited V6',
                        'Model_Code' => '\'8648',
                        'Model#' => '820224',
                        '12-35' => '7.34%',
                        '36-47' => '6.29%',
                        '48-59' => '6.24%',
                        '60+' => '5.89%',
                        '12-24' => '71',
                        '27' => '69',
                        '36' => '64',
                        '39' => '63',
                        '42' => '61',
                        '48' => '58',
                        '60' => '50',
                        'Admin Fee' => '$595'
                    ],
                    [
                        'Make' => '',
                        'Model' => '',
                        'Description' => '4dr Wgn SR5 Premium V6',
                        'Model_Code' => '\'8646',
                        'Model#' => '820224',
                        '12-35' => '7.34%',
                        '36-47' => '6.29%',
                        '48-59' => '6.24%',
                        '60+' => '5.89%',
                        '12-24' => '72',
                        '27' => '70',
                        '36' => '65',
                        '39' => '64',
                        '42' => '62',
                        '48' => '59',
                        '60' => '51',
                        'Admin Fee' => '$595'
                    ]
                ]
            ]
        ];
    }

    public function uniqueIndexValuesDataProvider() {
        return [
            [
                /* input, expected */
                [
                    'row',
                    'row',
                    'row',
                    'test',
                    'row'
                ],
                [
                    'row',
                    'row1',
                    'row2',
                    'test',
                    'row3'
                ]
            ],
            [
                /* input, expected */
                [
                    'row',
                    'row',
                    'row',
                    'row',
                    'row'
                ],
                [
                    'row',
                    'row1',
                    'row2',
                    'row3',
                    'row4'
                ]
            ],
            [
                [
                    'Make',
                    'Model',
                    'Description',
                    'Model_Code',
                    'Model#',
                    '12-35',
                    '36-47',
                    '48-59',
                    '60+',
                    '12-24',
                    '27',
                    '36',
                    '39',
                    '42',
                    '48',
                    '60',
                    'Admin Fee'
                ],
                [
                    'Make',
                    'Model',
                    'Description',
                    'Model_Code',
                    'Model#',
                    '12-35',
                    '36-47',
                    '48-59',
                    '60+',
                    '12-24',
                    '27',
                    '36',
                    '39',
                    '42',
                    '48',
                    '60',
                    'Admin Fee'
                ],
            ],
            [
                [
                    'test',
                    'test1',
                    'test',
                    'test2',
                    'test',
                    'test1'
                ],
                [
                    'test',
                    'test1',
                    'test11',
                    'test2',
                    'test21',
                    'test111'
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider csvToAssociativeArrayDataProvider
     * @param array $input
     * @param array $expected
     * @throws \Exception
     */
    public function csvToAssociativeArray(array $input, array $expected) {
        $actual = ArrayUtility::csvToAssociativeArray($input);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider uniqueIndexValuesDataProvider
     * @param array $input
     * @param array $expected
     * @throws \Exception
     */
    public function uniqueIndexValues(array $input, array $expected) {
        $actual = ArrayUtility::uniqueIndexValues($input);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider formatArrayDataProvider
     * @param array $input
     * @param array $expected
     */
    public function formatArray(array $input, array $expected){
        $actual = ArrayUtility::formatArray($input, function ($v) { return $v['EventDate']->format('Y-m-d');});
        $this->assertEquals($expected, $actual);
    }
}
