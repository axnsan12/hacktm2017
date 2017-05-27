<?php

namespace Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bundles
 *
 * @ORM\Table(name="bundles", indexes={@ORM\Index(name="fk_bundles_scrapers1_idx", columns={"scrapers_id"})})
 * @ORM\Entity
 */
class Bundles
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
     * @ORM\Column(name="price", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var \Models\Scrapers
     *
     * @ORM\ManyToOne(targetEntity="Models\Scrapers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scrapers_id", referencedColumnName="id")
     * })
     */
    private $scrapers;


}
