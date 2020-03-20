<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactuurregelRepository")
 */
class Factuurregel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Factuur", inversedBy="factuurregels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $factuur_nummer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product_code;

    /**
     * @ORM\Column(type="integer")
     */
    private $product_aantal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFactuurNummer(): ?factuur
    {
        return $this->factuur_nummer;
    }

    public function setFactuurNummer(?factuur $factuur_nummer): self
    {
        $this->factuur_nummer = $factuur_nummer;

        return $this;
    }

    public function getProductCode(): ?product
    {
        return $this->product_code;
    }

    public function setProductCode(?product $product_code): self
    {
        $this->product_code = $product_code;

        return $this;
    }

    public function getProductAantal(): ?int
    {
        return $this->product_aantal;
    }

    public function setProductAantal(int $product_aantal): self
    {
        $this->product_aantal = $product_aantal;

        return $this;
    }
}
