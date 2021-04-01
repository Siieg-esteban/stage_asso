<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jeu
 *
 * @ORM\Table(name="jeu", indexes={@ORM\Index(name="auteur", columns={"auteur"})})
 * @ORM\Entity
 */
class Jeu
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="text", length=65535, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenue", type="text", length=0, nullable=false)
     */
    private $contenue;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="text", length=65535, nullable=false)
     */
    private $etat;

    /**
     * @var int
     *
     * @ORM\Column(name="upvote", type="integer", nullable=false)
     */
    private $upvote = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="lien", type="text", length=65535, nullable=true)
     */
    private $lien;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="auteur", referencedColumnName="id")
     * })
     */
    private $auteur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lienWeb;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lienDl;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $nomdossier;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $longueur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $largeur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(string $contenue): self
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getUpvote(): ?int
    {
        return $this->upvote;
    }

    public function setUpvote(int $upvote): self
    {
        $this->upvote = $upvote;

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

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getLienWeb(): ?string
    {
        return $this->lienWeb;
    }

    public function setLienWeb(?string $lienWeb): self
    {
        $this->lienWeb = $lienWeb;

        return $this;
    }

    public function getLienDl(): ?string
    {
        return $this->lienDl;
    }

    public function setLienDl(?string $lienDl): self
    {
        $this->lienDl = $lienDl;

        return $this;
    }

    public function getNomdossier(): ?string
    {
        return $this->nomdossier;
    }

    public function setNomdossier(?string $nomdossier): self
    {
        $this->nomdossier = $nomdossier;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLongueur(): ?int
    {
        return $this->longueur;
    }

    public function setLongueur(?int $longueur): self
    {
        $this->longueur = $longueur;

        return $this;
    }

    public function getLargeur(): ?int
    {
        return $this->largeur;
    }

    public function setLargeur(?int $largeur): self
    {
        $this->largeur = $largeur;

        return $this;
    }


}
