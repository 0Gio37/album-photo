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


}

