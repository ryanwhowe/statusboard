<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Index;
use Statusboard\Utility\PayDate;

/**
 * Calendar
 *
 * @ORM\Table(name="calendar",
 *      uniqueConstraints={
 *          @UniqueConstraint(name="type_date", columns={"type","event_date"})},
 *     indexes={
 *          @Index(name="date_type_search", columns={"event_date", "type"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CalendarRepository")
 */
class Calendar
{

    const TYPE_COMPANY_HOLIDAY = 1;
    const TYPE_PTO = 2;
    const TYPE_SICK = 3;
    const TYPE_NATIONAL_HOLIDAY = 4;
    const TYPE_PAY_DATE = 99;

    public function __construct(array $values = []) {
        if(!empty($values)){
            foreach (['type', 'description', 'eventDate'] as $item) {
                $this->$item = (isset($values[$item])) ? $values[$item] : null;
            }
        }
    }

    /**
     * Translate the calendar event type to a description to be used in the UI
     *
     * @param Calendar $calendar
     * @return string
     * @throws \Exception
     */
    public static function translateTypeDescription(Calendar $calendar){
        switch($calendar->getType()){
            case Calendar::TYPE_COMPANY_HOLIDAY:
                return PayDate::getEmployerByConstant(PayDate::getEmployerByDate($calendar->getEventDate())) . ' Holiday';
                break;
            case Calendar::TYPE_PTO:
                return 'PTO';
                break;
            case Calendar::TYPE_SICK:
                return 'Sick Day';
                break;
            case Calendar::TYPE_NATIONAL_HOLIDAY:
                return $calendar->getDescription();
                break;
            case Calendar::TYPE_PAY_DATE:
                return PayDate::getEmployerByConstant(PayDate::getEmployerByDate($calendar->getEventDate())) . ' Pay Date';
                break;
            default:
                throw new \Exception('Invalid Type');
        }
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_date", type="date")
     */
    private $eventDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=true)
     */
    private $description;

    /**
     * @return string
     */
    public function getDescription(): string {
        if($this->description === null) return '';
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return Calendar
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Calendar
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set eventDate
     *
     * @param \DateTime $eventDate
     *
     * @return Calendar
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime
     */
    public function getEventDate() {
        return $this->eventDate;
    }

    /**
     * Serialize the calendar object to an array
     *
     * @return array
     * @throws \Exception
     */
    public function toArray() {
        return [
            'id'              => $this->getId(),
            'type_id'         => $this->getType(),
            'description'     => self::translateTypeDescription($this),
            'description_raw' => $this->description,
            'date'            => $this->getEventDate()->format('Y-m-d'),
        ];
    }
}

