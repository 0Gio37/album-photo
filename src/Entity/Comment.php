<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Photo::class, inversedBy="comments")
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     */
    private $auteur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Texte;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $CreateAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto(): ?Photo
    {
        return $this->photo;
    }

    public function setPhoto(?Photo $photo): self
    {
        $this->photo = $photo;

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


    public function getTexte(): ?string
    {
        return $this->Texte;
    }

    public function setTexte(?string $Texte): self
    {
        $this->Texte = $Texte;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->CreateAt;
    }

    public function setCreateAt(\DateTimeImmutable $CreateAt): self
    {
        $this->CreateAt = $CreateAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function seTimesStamps(){
        $this->setCreateAt(new \DateTimeImmutable());
    }
}
