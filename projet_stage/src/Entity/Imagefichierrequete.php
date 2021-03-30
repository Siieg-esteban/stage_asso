<?php

namespace App\Entity;

use App\Repository\ImagefichierrequeteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagefichierrequeteRepository::class)
 */
class Imagefichierrequete
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lien;

    /**
     * @ORM\Column(type="text")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=RequeteContributeur::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $requete;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(?string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRequete(): ?RequeteContributeur
    {
        return $this->requete;
    }

    public function setRequete(?RequeteContributeur $requete): self
    {
        $this->requete = $requete;

        return $this;
    }
}
