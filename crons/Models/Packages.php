<?php

namespace Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Packages
 * @ORM\Table(name="packages", indexes={@ORM\Index(name="fk_packages_company_service1_idx", columns={"company_service_id"}), @ORM\Index(name="fk_packages_bundles1_idx", columns={"bundles_id"})})
 * @ORM\Entity
 */
class Packages {
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
     * @var string
     * @ORM\Column(name="price", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(name="scraper_id_hint", type="string", length=127, nullable=true)
     */
    private $scraperIdHint;

    /**
     * @var \Models\Bundles
     * @ORM\ManyToOne(targetEntity="Models\Bundles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bundles_id", referencedColumnName="id")
     * })
     */
    private $bundles;

    /**
     * @var \Models\CompanyService
     * @ORM\ManyToOne(targetEntity="Models\CompanyService", inversedBy="packages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_service_id", referencedColumnName="id")
     * })
     */
    private $companyService;

    /**
     * Packages constructor.
     * @param string                 $name
     * @param string                 $price
     * @param \Models\CompanyService $companyService
     * @param string                 $scraperIdHint
     */
    public function __construct($name, $price, $companyService, $scraperIdHint = null) {
        $this->name = $name;
        $this->price = $price;
        $this->companyService = $companyService;
        $this->scraperIdHint = $scraperIdHint;
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
     * @return string
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getScraperIdHint() {
        return $this->scraperIdHint;
    }

    /**
     * @param string $scraperIdHint
     */
    public function setScraperIdHint($scraperIdHint) {
        $this->scraperIdHint = $scraperIdHint;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return Bundles
     */
    public function getBundles() {
        return $this->bundles;
    }

    /**
     * @return CompanyService
     */
    public function getCompanyService() {
        return $this->companyService;
    }

    /**
     * @return Services
     */
    public function getService() {
        return $this->companyService->getService();
    }

    /**
     * @var \Models\PackageCharacteristics[]
     * @ORM\OneToMany(targetEntity="Models\PackageCharacteristics", mappedBy="package", cascade={"persist"})
     */
    private $packageCharacteristics;

    public function setCharacteristic($alias, $value, $units) {
        $sc = $this->getService()->getCharacteristic($alias);
        $pc = new PackageCharacteristics($value, $units, $this, $sc);

        $this->packageCharacteristics[] = $pc;
    }
}
