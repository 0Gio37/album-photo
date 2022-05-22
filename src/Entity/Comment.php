<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentaires")
     * @ORM\JoinColumn(onDelete = "CASCADE")
     */
    private $auteur;

    /**
     * @ORM\Column(type="text")
     */
    private $texte;

    /**
     * @ORM\Column(type="datetime" , options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createAt;

//    /**
//     * @ORM\OneToMany(targetEntity=LienCommentPhoto::class, mappedBy="comment")
//     * @ORM\JoinColumn(onDelete = "CASCADE")
//     */
//    private $photo;

    public function __construct()
    {
        $this->photo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }
    /**
     * @ORM\PrePersist
     */
    public function seTimesStamps(){
        $this->setCreateAt(new \DateTime());
    }

    /**
     * @return Collection|LienCommentPhoto[]
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function addPhoto(LienCommentPhoto $photo): self
    {
        if (!$this->photo->contains($photo)) {
            $this->photo[] = $photo;
            $photo->setComment($this);
        }

        return $this;
    }

    public function removePhoto(LienCommentPhoto $photo): self
    {
        if ($this->photo->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getComment() === $this) {
                $photo->setComment(null);
            }
        }

        return $this;
    }
}
