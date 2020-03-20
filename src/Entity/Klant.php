<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KlantRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Klant implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naam;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $straat;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $plaats;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $btw_nummer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Factuur", mappedBy="klant_nummer")
     */
    private $factuurs;

    public function __construct()
    {
        $this->factuurs = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNaam(): ?string
    {
        return $this->naam;
    }

    public function setNaam(string $naam): self
    {
        $this->naam = $naam;

        return $this;
    }

    public function getStraat(): ?string
    {
        return $this->straat;
    }

    public function setStraat(string $straat): self
    {
        $this->straat = $straat;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getPlaats(): ?string
    {
        return $this->plaats;
    }

    public function setPlaats(string $plaats): self
    {
        $this->plaats = $plaats;

        return $this;
    }

    public function getBtwNummer(): ?string
    {
        return $this->btw_nummer;
    }

    public function setBtwNummer(?string $btw_nummer): self
    {
        $this->btw_nummer = $btw_nummer;

        return $this;
    }

    /**
     * @return Collection|Factuur[]
     */
    public function getFactuurs(): Collection
    {
        return $this->factuurs;
    }

    public function addFactuur(Factuur $factuur): self
    {
        if (!$this->factuurs->contains($factuur)) {
            $this->factuurs[] = $factuur;
            $factuur->setKlantNummer($this);
        }

        return $this;
    }

    public function removeFactuur(Factuur $factuur): self
    {
        if ($this->factuurs->contains($factuur)) {
            $this->factuurs->removeElement($factuur);
            // set the owning side to null (unless already changed)
            if ($factuur->getKlantNummer() === $this) {
                $factuur->setKlantNummer(null);
            }
        }

        return $this;
    }




}
