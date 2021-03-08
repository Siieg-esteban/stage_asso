<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Listecategorie
 *
 * @ORM\Table(name="listecategorie", indexes={@ORM\Index(name="categorie", columns={"categorie"}), @ORM\Index(name="jeu", columns={"jeu"}), @ORM\Index(name="proto", columns={"proto"})})
 * @ORM\Entity
 */
class Listecategorie
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
     * @ORM\Column(name="type", type="text", length=65535, nullable=false, options={"comment"="jeu ou proto"})
     */
    private $type;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie", referencedColumnName="id")
     * })
     */
    private $categorie;

    /**
     * @var \Jeu
     *
     * @ORM\ManyToOne(targetEntity="Jeu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="jeu", referencedColumnName="id")
     * })
     */
    private $jeu;

    /**
     * @var \Proto
     *
     * @ORM\ManyToOne(targetEntity="Proto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proto", referencedColumnName="id")
     * })
     */
    private $proto;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getJeu(): ?Jeu
    {
        return $this->jeu;
    }

    public function setJeu(?Jeu $jeu): self
    {
        $this->jeu = $jeu;

        return $this;
    }

    public function getProto(): ?Proto
    {
        return $this->proto;
    }

    public function setProto(?Proto $proto): self
    {
        $this->proto = $proto;

        return $this;
    }


}
