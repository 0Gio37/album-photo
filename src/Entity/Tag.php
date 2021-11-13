<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\ManyToMany(targetEntity=Photo::class, inversedBy="tags")
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity=LienTagPhoto::class, mappedBy="tag")
     */
    private $lienTagPhotos;


    public function __construct()
    {
        $this->photo = new ArrayCollection();
        $this->lienTagPhotos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photo->contains($photo)) {
            $this->photo[] = $photo;
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        $this->photo->removeElement($photo);

        return $this;
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
            $lienTagPhoto->setTag($this);
        }

        return $this;
    }

    public function removeLienTagPhoto(LienTagPhoto $lienTagPhoto): self
    {
        if ($this->lienTagPhotos->removeElement($lienTagPhoto)) {
            // set the owning side to null (unless already changed)
            if ($lienTagPhoto->getTag() === $this) {
                $lienTagPhoto->setTag(null);
            }
        }

        return $this;
    }

}
