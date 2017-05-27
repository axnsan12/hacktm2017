<?php

namespace Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scrapers
 *
 * @ORM\Table(name="scrapers")
 * @ORM\Entity
 */
class Scrapers
{
    /**
     * @return int
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
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getScriptName() {
        return $this->scriptName;
    }

    /**
     * @param string $scriptName
     */
    public function setScriptName($scriptName) {
        $this->scriptName = $scriptName;
    }

    /**
     * @return bool
     */
    public function isEnabled() {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdated() {
        return $this->lastUpdated;
    }

    /**
     * @param \DateTime $lastUpdated
     */
    public function setLastUpdated($lastUpdated) {
        $this->lastUpdated = $lastUpdated;
    }

    /**
     * @return string
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError($error) {
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getCompanyServices() {
        return $this->companyServices;
    }

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
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=127, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="script_name", type="string", length=127, nullable=false)
     */
    private $scriptName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_updated", type="datetime", nullable=true)
     */
    private $lastUpdated;

    /**
     * @var string
     *
     * @ORM\Column(name="error", type="text", length=65535, nullable=true)
     */
    private $error;

    /**
     * @var \Models\CompanyService
     *
     * @ORM\OneToMany(targetEntity="Models\CompanyService", mappedBy="scrapers")
     */
    private $companyServices;

}
