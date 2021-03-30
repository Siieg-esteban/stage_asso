<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Listecompetence
 *
 * @ORM\Table(name="listecompetence", indexes={@ORM\Index(name="user", columns={"user"}), @ORM\Index(name="competence", columns={"competence"})})
 * @ORM\Entity
 */
class Listecompetence
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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $user;

    /**
     * @var \Competence
     *
     * @ORM\ManyToOne(targetEntity="Competence")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="competence", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $competence;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }


}
