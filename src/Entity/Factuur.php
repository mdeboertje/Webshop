<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactuurRepository")
 */
class Factuur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Klant", mappedBy="factuur")
     */
    private $klant_nummer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $factuur_datum;

    /**
     * @ORM\Column(type="datetime")
     */
    private $verval_datum;

    /**
     * @ORM\Column(type="boolean")
     */
    private $in_zake_opdracht;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $korting;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Factuurregel", mappedBy="factuur_nummer")
     */
    private $factuurregels;

    public function __construct()
    {
        $this->klant_nummer = new ArrayCollection();
        $this->factuurregels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Klant[]
     */
    public function getKlantNummer(): Collection
    {
        return $this->klant_nummer;
    }

    public function addKlantNummer(Klant $klantNummer): self
    {
        if (!$this->klant_nummer->contains($klantNummer)) {
            $this->klant_nummer[] = $klantNummer;
            $klantNummer->setFactuur($this);
        }

        return $this;
    }

    public function removeKlantNummer(Klant $klantNummer): self
    {
        if ($this->klant_nummer->contains($klantNummer)) {
            $this->klant_nummer->removeElement($klantNummer);
            // set the owning side to null (unless already changed)
            if ($klantNummer->getFactuur() === $this) {
                $klantNummer->setFactuur(null);
            }
        }

        return $this;
    }

    public function getFactuurDatum(): ?\DateTimeInterface
    {
        return $this->factuur_datum;
    }

    public function setFactuurDatum(\DateTimeInterface $factuur_datum): self
    {
        $this->factuur_datum = $factuur_datum;

        return $this;
    }

    public function getVervalDatum(): ?\DateTimeInterface
    {
        return $this->verval_datum;
    }

    public function setVervalDatum(\DateTimeInterface $verval_datum): self
    {
        $this->verval_datum = $verval_datum;

        return $this;
    }

    public function getInZakeOpdracht(): ?bool
    {
        return $this->in_zake_opdracht;
    }

    public function setInZakeOpdracht(bool $in_zake_opdracht): self
    {
        $this->in_zake_opdracht = $in_zake_opdracht;

        return $this;
    }

    public function getKorting(): ?int
    {
        return $this->korting;
    }

    public function setKorting(?int $korting): self
    {
        $this->korting = $korting;

        return $this;
    }

    /**
     * @return Collection|Factuurregel[]
     */
    public function getFactuurregels(): Collection
    {
        return $this->factuurregels;
    }

    public function addFactuurregel(Factuurregel $factuurregel): self
    {
        if (!$this->factuurregels->contains($factuurregel)) {
            $this->factuurregels[] = $factuurregel;
            $factuurregel->setFactuurNummer($this);
        }

        return $this;
    }

    public function removeFactuurregel(Factuurregel $factuurregel): self
    {
        if ($this->factuurregels->contains($factuurregel)) {
            $this->factuurregels->removeElement($factuurregel);
            // set the owning side to null (unless already changed)
            if ($factuurregel->getFactuurNummer() === $this) {
                $factuurregel->setFactuurNummer(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getId().' ';
    }
}
