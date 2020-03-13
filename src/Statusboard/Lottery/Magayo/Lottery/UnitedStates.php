<?php


namespace Statusboard\Lottery\Magayo\Lottery;

use Statusboard\Lottery\Magayo\MagayoLottery;

class UnitedStates extends AbstractLottery {

    CONST LOTTERY_LOTTOAMERICA = 1;
    CONST LOTTERY_MEGAMILLIONS = 2;
    CONST LOTTERY_POWERBALL = 3;
    CONST LOTTERY_LUCKYFORLIFE = 4;

    private $groupname = "us";

    public function __construct() {
        $this->basename = $this->groupname;
        $this->buildLottery();
    }

    protected function buildLottery() {
        $lotteries = [
            self::LOTTERY_LOTTOAMERICA => [
                "name" => "Lotto America",
                "id" => "lotto_america"
            ],
            self::LOTTERY_MEGAMILLIONS => [
                "name" => "Mega Millions",
                "id" => "mega_millions"
            ],
            self::LOTTERY_POWERBALL => [
                "name" => "Powerball",
                "id" => "powerball"
            ],
            self::LOTTERY_LUCKYFORLIFE => [
                "name" => "Lucky for Life",
                "id" => "lucky_life"
            ],
        ];

        $this->lotteries = [];
        foreach ($lotteries as $id=>$lottery){
            $this->lotteries[$id] = (new MagayoLottery)::parseArray($lottery);
        }
    }
}