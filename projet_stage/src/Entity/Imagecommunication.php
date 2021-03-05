<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Imagecommunication
 *
 * @ORM\Table(name="imagecommunication", indexes={@ORM\Index(name="tchat", columns={"tchat"}), @ORM\Index(name="com", columns={"com"}), @ORM\Index(name="messagerie", columns={"messagerie"})})
 * @ORM\Entity
 */
class Imagecommunication
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
     * @ORM\Column(name="type", type="text", length=65535, nullable=false, options={"comment"="com ou tchat ou messagerie"})
     */
    private $type;

    /**
     * @var \Com
     *
     * @ORM\ManyToOne(targetEntity="Com")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="com", referencedColumnName="id")
     * })
     */
    private $com;

    /**
     * @var \Messagerie
     *
     * @ORM\ManyToOne(targetEntity="Messagerie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="messagerie", referencedColumnName="id")
     * })
     */
    private $messagerie;

    /**
     * @var \Tchat
     *
     * @ORM\ManyToOne(targetEntity="Tchat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tchat", referencedColumnName="id")
     * })
     */
    private $tchat;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCom(): ?Com
    {
        return $this->com;
    }

    public function setCom(?Com $com): self
    {
        $this->com = $com;

        return $this;
    }

    public function getMessagerie(): ?Messagerie
    {
        return $this->messagerie;
    }

    public function setMessagerie(?Messagerie $messagerie): self
    {
        $this->messagerie = $messagerie;

        return $this;
    }

    public function getTchat(): ?Tchat
    {
        return $this->tchat;
    }

    public function setTchat(?Tchat $tchat): self
    {
        $this->tchat = $tchat;

        return $this;
    }


}
