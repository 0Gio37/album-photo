<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $texte;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentaire"
     * @ORM\JoinColumn(onDelete = "CASCADE")
     */
    private $auteur_id;

    /**
     * @ORM\ManyToOne(targetEntity=Photo::class, inversedBy="commentaires")
     * @ORM\JoinColumn(onDelete = "CASCADE")
     */
    private $photo_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getAuteurId(): ?User
    {
        return $this->auteur_id;
    }

    public function setAuteurId(?User $auteur_id): self
    {
        $this->auteur_id = $auteur_id;

        return $this;
    }

    public function getPhotoId(): ?Photo
    {
        return $this->photo_id;
    }

    public function setPhotoId(?Photo $photo_id): self
    {
        $this->photo_id = $photo_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
    /**
     * @ORM\PrePersist
     */
    public function seTimesStamps(){
        $this->setCreatedAt(new \DateTime());
    }
}
