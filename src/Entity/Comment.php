<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
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
     * @ORM\ManyToOne(targetEntity=Video::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $video;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $like_counter;

    /**
     * @ORM\Column(type="integer")
     */
    private $dislike_counter;

    public function __construct(){
        $this->createdAt = new \DateTimeImmutable('now');
        $this->like_counter = 0;
        $this->dislike_counter = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideo(): ?Video
    {
        return $this->video;
    }

    public function setVideo(?Video $video): self
    {
        $this->video = $video;

        return $this;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLikeCounter(): ?int
    {
        return $this->like_counter;
    }

    public function setLikeCounter(int $like_counter): self
    {
        $this->like_counter = $like_counter;

        return $this;
    }

    public function getDislikeCounter(): ?int
    {
        return $this->dislike_counter;
    }

    public function setDislikeCounter(int $dislike_counter): self
    {
        $this->dislike_counter = $dislike_counter;

        return $this;
    }

    public function __toString(){
        return $this->content;
    }
}
