<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Com
 *
 * @ORM\Table(name="com", indexes={@ORM\Index(name="envoyer", columns={"envoyer"}), @ORM\Index(name="proto", columns={"proto"}), @ORM\Index(name="jeu", columns={"jeu"}), @ORM\Index(name="blog", columns={"blog"})})
 * @ORM\Entity
 */
class Com
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
     * @ORM\Column(name="contenue", type="text", length=0, nullable=false)
     */
    private $contenue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="text", length=65535, nullable=false, options={"comment"="jeu ou proto ou blog"})
     */
    private $type;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="envoyer", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $envoyer;

    /**
     * @var \Jeu
     *
     * @ORM\ManyToOne(targetEntity="Jeu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="jeu", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $jeu;

    /**
     * @var \Blog
     *
     * @ORM\ManyToOne(targetEntity="Blog")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="blog", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $blog;

    /**
     * @var \Proto
     *
     * @ORM\ManyToOne(targetEntity="Proto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proto", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $proto;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

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

    public function getEnvoyer(): ?User
    {
        return $this->envoyer;
    }

    public function setEnvoyer(?User $envoyer): self
    {
        $this->envoyer = $envoyer;

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

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

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
