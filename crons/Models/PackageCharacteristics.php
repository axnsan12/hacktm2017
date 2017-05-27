<?php

namespace Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * PackageCharacteristics
 *
 * @ORM\Table(name="package_characteristics", indexes={@ORM\Index(name="fk_package_characteristics_packages1_idx", columns={"packages_id"}), @ORM\Index(name="fk_package_characteristics_service_characteristics1_idx", columns={"service_characteristics_id"})})
 * @ORM\Entity
 */
class PackageCharacteristics
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=1024, nullable=false)
     */
    private $value;

    /**
     * @var \Models\Packages
     *
     * @ORM\ManyToOne(targetEntity="Models\Packages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="packages_id", referencedColumnName="id")
     * })
     */
    private $packages;

    /**
     * @var \Models\ServiceCharacteristics
     *
     * @ORM\ManyToOne(targetEntity="Models\ServiceCharacteristics")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_characteristics_id", referencedColumnName="id")
     * })
     */
    private $serviceCharacteristics;


}
