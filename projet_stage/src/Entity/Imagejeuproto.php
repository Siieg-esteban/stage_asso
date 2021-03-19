<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Imagejeuproto
 *
 * @ORM\Table(name="imagejeuproto", indexes={@ORM\Index(name="jeu", columns={"jeu"}), @ORM\Index(name="proto", columns={"proto"}), @ORM\Index(name="blog", columns={"blog"})})
 * @ORM\Entity
 */
class Imagejeuproto
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
     * @ORM\Column(name="image", type="blob", length=0, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="text", length=65535, nullable=false, options={"comment"="jeu ou proto"})
     */
    private $type;

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
     * @var \Proto
     *
     * @ORM\ManyToOne(targetEntity="Proto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proto", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $proto;

    /**
     * @var \Blog
     *
     * @ORM\ManyToOne(targetEntity="Blog")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="blog", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $blog;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage()
    {
        return stream_get_contents($this->image);
    }

    public function setImage($image): self
    {
        $this->image = $image;

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

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }


}
