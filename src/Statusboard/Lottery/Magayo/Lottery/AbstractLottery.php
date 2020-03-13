<?php


namespace Statusboard\Lottery\Magayo\Lottery;


use Statusboard\Lottery\Magayo\MagayoLottery;
use Statusboard\Lottery\MissingLotteryException;
use Statusboard\Lottery\MissingLotteryGameException;
use Symfony\Component\Intl\Exception\NotImplementedException;

abstract class AbstractLottery {

    CONST OUTPUT_ID = 'id';
    CONST OUTPUT_NAME = 'name';

    protected $lotteries = null;
    protected $basename = null;

    CONST SEPARATOR = "_";

    /**
     * @param int $lottery
     * @param int $game
     * @return array
     * @throws MissingLotteryException
     * @throws MissingLotteryGameException
     * @throws NotImplementedException
     */
    public function getLottery(int $lottery, int $game = 0) {
        if (is_null($this->lotteries)) throw new NotImplementedException('The lotteries have not been implemented for this region');
        if (isset($this->lotteries[$lottery])) {
            /**
             * @var MagayoLottery $result
             */
            $result = $this->lotteries[$lottery];
            if ($result->hasDrawings() && ($game === 0)) {
                throw new MissingLotteryGameException("The chosen lottery " . $result->getName() . " requires a game to be specified");
            }
            if($game === 0){
                return [
                    self::OUTPUT_ID => $this->generate_id($result->getId()),
                    self::OUTPUT_NAME => $result->getName()
                ];
            } else {
                return [
                    self::OUTPUT_ID => $this->generate_id($result->getDrawingId($game)),
                    self::OUTPUT_NAME => $result->getName()
                ];
            }
        } else {
            throw new MissingLotteryException();
        }
    }

    private function generate_id($id){
        return $this->basename . self::SEPARATOR . $id;
    }

    abstract protected function buildLottery();

}