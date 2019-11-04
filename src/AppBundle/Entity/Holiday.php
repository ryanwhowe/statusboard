<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Holiday
 *
 * @ORM\Table(name="holiday")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HolidayRepository")
 */
class Holiday
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="observed_date", type="date")
     */
    private $observedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     * Set observedDate
     *
     * @param \DateTime $observedDate
     *
     * @return Holiday
     */
    public function setObservedDate($observedDate)
    {
        $this->observedDate = $observedDate;

        return $this;
    }

    /**
     * Get observedDate
     *
     * @return \DateTime
     */
    public function getObservedDate()
    {
        return $this->observedDate;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Holiday
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

