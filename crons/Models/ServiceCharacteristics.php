<?php

namespace Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceCharacteristics
 * @ORM\Table(name="service_characteristics", uniqueConstraints={@ORM\UniqueConstraint(name="alias_UNIQUE", columns={"alias"})}, indexes={@ORM\Index(name="fk_service_characteristics_services1_idx", columns={"services_id"})})
 * @ORM\Entity
 */
class ServiceCharacteristics {
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=250, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="alias", type="string", length=45, nullable=false)
     */
    private $alias;

    /**
     * @var string
     * @ORM\Column(name="units", type="string", length=45, nullable=false)
     */
    private $units;

    /**
     * @var \Models\Services
     * @ORM\ManyToOne(targetEntity="Models\Services")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="services_id", referencedColumnName="id")
     * })
     */
    private $service;

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
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias) {
        $this->alias = $alias;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return Services
     */
    public function getService() {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getUnits() {
        return $this->units;
    }

    /**
     * @param string $units
     */
    public function setUnits($units) {
        $this->units = $units;
    }
}
