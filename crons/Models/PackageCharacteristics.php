<?php

namespace Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * PackageCharacteristics
 * @ORM\Table(name="package_characteristics", indexes={@ORM\Index(name="fk_package_characteristics_packages1_idx", columns={"packages_id"}), @ORM\Index(name="fk_package_characteristics_service_characteristics1_idx", columns={"service_characteristics_id"})})
 * @ORM\Entity
 */
class PackageCharacteristics {
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="value", type="string", length=1024, nullable=false)
     */
    private $value;

    /**
     * @var \Models\Packages
     * @ORM\ManyToOne(targetEntity="Models\Packages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="packages_id", referencedColumnName="id")
     * })
     */
    private $package;

    /**
     * @var \Models\ServiceCharacteristics
     * @ORM\ManyToOne(targetEntity="Models\ServiceCharacteristics")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_characteristics_id", referencedColumnName="id")
     * })
     */
    private $serviceCharacteristic;

    /**
     * PackageCharacteristics constructor.
     * @param string                 $value
     * @param string                 $units
     * @param Packages               $package
     * @param ServiceCharacteristics $serviceCharacteristic
     */
    public function __construct($value, $units, Packages $package, ServiceCharacteristics $serviceCharacteristic) {
        $this->value = $value;
        $this->units = $units;
        $this->package = $package;
        $this->serviceCharacteristic = $serviceCharacteristic;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @var string
     * @ORM\Column(name="units", type="string", length=45, nullable=false)
     */
    private $units;

    /**
     * @return Packages
     */
    public function getPackage() {
        return $this->package;
    }

    /**
     * @return ServiceCharacteristics
     */
    public function getServiceCharacteristic() {
        return $this->serviceCharacteristic;
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
