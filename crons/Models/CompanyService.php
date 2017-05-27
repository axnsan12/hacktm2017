<?php

namespace Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompanyService
 *
 * @ORM\Table(name="company_service", indexes={@ORM\Index(name="fk_company_service_companies1_idx", columns={"companies_id"}), @ORM\Index(name="fk_company_service_services1_idx", columns={"services_id"}), @ORM\Index(name="fk_company_service_scrapers1_idx", columns={"scrapers_id"})})
 * @ORM\Entity
 */
class CompanyService
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
     * @var \Models\Companies
     *
     * @ORM\ManyToOne(targetEntity="Models\Companies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="companies_id", referencedColumnName="id")
     * })
     */
    private $companies;

    /**
     * @var \Models\Scrapers
     *
     * @ORM\ManyToOne(targetEntity="Models\Scrapers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scrapers_id", referencedColumnName="id")
     * })
     */
    private $scrapers;

    /**
     * @var \Models\Services
     *
     * @ORM\ManyToOne(targetEntity="Models\Services")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="services_id", referencedColumnName="id")
     * })
     */
    private $services;

    /**
     * @var \Models\Packages
     *
     * @ORM\OneToMany(targetEntity="Models\Packages", mappedBy="companyService")
     */
    private $packages;

    /**
     * @return Companies
     */
    public function getCompanies() {
        return $this->companies;
    }

    /**
     * @return Scrapers
     */
    public function getScrapers() {
        return $this->scrapers;
    }

    /**
     * @return Services
     */
    public function getServices() {
        return $this->services;
    }

    /**
     * @return mixed
     */
    public function getPackages() {
        return $this->packages;
    }

}
