<?php


namespace Statusboard\Lottery\Magayo\Lottery;

use Statusboard\Lottery\Magayo\MagayoLottery;

class Massachusetts extends AbstractLottery {

    CONST LOTTERY_MASSCASH = 1;
    CONST LOTTERY_MEGABUCKS = 2;
    CONST LOTTERY_NUMBERS = 3;

    CONST DRAWINGS_MIDDAY = 10;
    CONST DRAWINGS_EVENING = 20;

    private $groupname = 'us_ma';

    public function __construct() {
        $this->basename .= self::SEPARATOR . $this->groupname;
        $this->buildLottery();
    }

    protected function buildLottery() {
        $lotteries = [
            self::LOTTERY_MASSCASH => [
                "name" => "Mass Cash",
                "id" => "mass"
            ],
            self::LOTTERY_MEGABUCKS => [
                "name" => "Megabucks Doubler",
                "id" => "mega"
            ],
            self::LOTTERY_NUMBERS => [
                "name" => "Numbers",
                "id" => "numbers",
                "drawings" =>[
                    self::DRAWINGS_MIDDAY => "mid",
                    self::DRAWINGS_EVENING => "eve"
                ]
            ]
        ];

        $this->lotteries = [];
        foreach ($lotteries as $id=>$lottery){
            $this->lotteries[$id] = (new MagayoLottery)::parseArray($lottery);
        }
    }

}