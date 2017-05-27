<?php

namespace Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Services
 * @ORM\Table(name="services")
 * @ORM\Entity
 */
class Services {
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var \Models\ServiceCharacteristics[]
     * @ORM\OneToMany(targetEntity="Models\ServiceCharacteristics", mappedBy="service")
     */
    private $characteristics;

    /**
     * Services constructor.
     * @param string                 $name
     * @param ServiceCharacteristics $characteristics
     */
    public function __construct($name, ServiceCharacteristics $characteristics) {
        $this->name = $name;
        $this->characteristics = $characteristics;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return ServiceCharacteristics[]
     */
    public function getCharacteristics() {
        return $this->characteristics;
    }

    /**
     * @param $alias
     * @return ServiceCharacteristics
     * @throws \Exception
     */
    public function getCharacteristic($alias) {
        foreach ($this->getCharacteristics() as $value) {
            if ($value->getAlias() == $alias) {
                return $value;
            }
        }
        throw new \Exception("Get characteristic error");
    }

    /**
     * @param ServiceCharacteristics $characteristics
     */
    public function setCharacteristics($characteristics) {
        $this->characteristics = $characteristics;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

}
