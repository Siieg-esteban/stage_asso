<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messagerie
 *
 * @ORM\Table(name="messagerie", indexes={@ORM\Index(name="envoyer", columns={"envoyer"}), @ORM\Index(name="receveur", columns={"receveur"})})
 * @ORM\Entity
 */
class Messagerie
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
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * @var string
     *
     * @ORM\Column(name="contenue", type="text", length=0, nullable=false)
     */
    private $contenue;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="envoyer", referencedColumnName="id")
     * })
     */
    private $envoyer;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="receveur", referencedColumnName="id")
     * })
     */
    private $receveur;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(string $contenue): self
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getEnvoyer(): ?User
    {
        return $this->envoyer;
    }

    public function setEnvoyer(?User $envoyer): self
    {
        $this->envoyer = $envoyer;

        return $this;
    }

    public function getReceveur(): ?User
    {
        return $this->receveur;
    }

    public function setReceveur(?User $receveur): self
    {
        $this->receveur = $receveur;

        return $this;
    }


}
