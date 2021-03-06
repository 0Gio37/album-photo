<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Photo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Album::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false, onDelete = "CASCADE")
     */
    private $album;


    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="photo")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="photo")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createAt;

    /**
     * @ORM\OneToMany(targetEntity=LienTagPhoto::class, mappedBy="photo")
     * @ORM\JoinColumn(onDelete = "CASCADE")
     */
    private $lienTagPhotos;

    /**
     * @ORM\Column(type="string")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": "0000"})
     */
    private $annee;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lieu;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="photo_id")
     */
    private $commentaires;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->lienTagPhotos = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function __toString():string
    {
        return $this->getAuteur()->getNom();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }


    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPhoto($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removePhoto($this);
        }

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
     * @return Collection|LienTagPhoto[]
     */
    public function getLienTagPhotos(): Collection
    {
        return $this->lienTagPhotos;
    }

    public function addLienTagPhoto(LienTagPhoto $lienTagPhoto): self
    {
        if (!$this->lienTagPhotos->contains($lienTagPhoto)) {
            $this->lienTagPhotos[] = $lienTagPhoto;
            $lienTagPhoto->setPhoto($this);
        }

        return $this;
    }

    public function removeLienTagPhoto(LienTagPhoto $lienTagPhoto): self
    {
        if ($this->lienTagPhotos->removeElement($lienTagPhoto)) {
            // set the owning side to null (unless already changed)
            if ($lienTagPhoto->getPhoto() === $this) {
                $lienTagPhoto->setPhoto(null);
            }
        }

        return $this;
    }
    

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(?string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setPhotoId($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPhotoId() === $this) {
                $commentaire->setPhotoId(null);
            }
        }

        return $this;
    }

}
