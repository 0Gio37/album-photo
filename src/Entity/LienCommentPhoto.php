<?php

namespace App\Entity;

use App\Repository\LienCommentPhotoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LienCommentPhotoRepository::class)
 */
class LienCommentPhoto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="lienCommentPhotos")
     * @ORM\JoinColumn(onDelete = "CASCADE")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Photo::class, inversedBy="lienCommentPhotos")
     * @ORM\JoinColumn(onDelete = "CASCADE")
     */
    private $photo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
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
}
