<?php

namespace Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Packages
 *
 * @ORM\Table(name="packages", indexes={@ORM\Index(name="fk_packages_company_service1_idx", columns={"company_service_id"}), @ORM\Index(name="fk_packages_bundles1_idx", columns={"bundles_id"})})
 * @ORM\Entity
 */
class Packages
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="scraper_id_hint", type="string", length=127, nullable=true)
     */
    private $scraperIdHint;

    /**
     * @var \Models\Bundles
     *
     * @ORM\ManyToOne(targetEntity="Models\Bundles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bundles_id", referencedColumnName="id")
     * })
     */
    private $bundles;




    /**
     * @var \Models\CompanyService
     *
     * @ORM\ManyToOne(targetEntity="Models\CompanyService", inversedBy="packages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_service_id", referencedColumnName="id")
     * })
     */
    private $companyService;


}
