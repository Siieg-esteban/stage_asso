<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Listecontributeur
 *
 * @ORM\Table(name="listecontributeur", indexes={@ORM\Index(name="user", columns={"user"}), @ORM\Index(name="jeu", columns={"jeu"}), @ORM\Index(name="proto", columns={"proto"})})
 * @ORM\Entity
 */
class Listecontributeur
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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
