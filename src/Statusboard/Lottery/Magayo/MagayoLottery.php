<?php


namespace Statusboard\Lottery\Magayo;

use Statusboard\Lottery\Magayo\Lottery\AbstractLottery;

class MagayoLottery {

    CONST SEPARATOR = AbstractLottery::SEPARATOR;

    private $id = null;
    private $name = null;
    private $drawings = null;

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return null
     */
    public function getDrawings() {
        return $this->drawings;
    }

    /**
     * @param int $drawing
     * @return string
     */
    public function getDrawingId($drawing) {
        return $this->id . self::SEPARATOR . $this->drawings[$drawing];
    }

    /**
     * @return bool
     */
    public function hasDrawings(): bool {
        return (is_array($this->drawings) && count($this->drawings));
    }

    /**
     * MagayoLottery constructor.
     *
     * @param string $id optional
     * @param string $name optional
     * @param array $drawings optional
     */
    public function __construct($id = null, $name = null, $drawings = null) {
        if(!is_null($id)) $this->id = $id;
        if(!is_null($name)) $this->name = $name;
        if(!is_null($drawings)) $this->drawings = $drawings;
    }

    /**
     * @param array $lottery
     * @return MagayoLottery
     */
    public static function parseArray(array $lottery){
        $self = new self($lottery['id'], $lottery['name']);
        if(isset($lottery['drawings'])){
            $self->drawings = $lottery['drawings'];
        }
        return $self;
    }

}