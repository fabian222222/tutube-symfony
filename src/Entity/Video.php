<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 */
class Video
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $video_url;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="video")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=VideoSeen::class, mappedBy="video")
     */
    private $videoSeens;

    /**
     * @ORM\OneToMany(targetEntity=LikedVideo::class, mappedBy="video")
     */
    private $likedVideos;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->videoSeens = new ArrayCollection();
        $this->likedVideos = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->video_url;
    }

    public function setVideoUrl(string $video_url): self
    {
        $this->video_url = $video_url;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setVideo($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getVideo() === $this) {
                $comment->setVideo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|VideoSeen[]
     */
    public function getVideoSeens(): Collection
    {
        return $this->videoSeens;
    }

    public function addVideoSeen(VideoSeen $videoSeen): self
    {
        if (!$this->videoSeens->contains($videoSeen)) {
            $this->videoSeens[] = $videoSeen;
            $videoSeen->setVideo($this);
        }

        return $this;
    }

    public function removeVideoSeen(VideoSeen $videoSeen): self
    {
        if ($this->videoSeens->removeElement($videoSeen)) {
            // set the owning side to null (unless already changed)
            if ($videoSeen->getVideo() === $this) {
                $videoSeen->setVideo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LikedVideo[]
     */
    public function getLikedVideos(): Collection
    {
        return $this->likedVideos;
    }

    public function addLikedVideo(LikedVideo $likedVideo): self
    {
        if (!$this->likedVideos->contains($likedVideo)) {
            $this->likedVideos[] = $likedVideo;
            $likedVideo->setVideo($this);
        }

        return $this;
    }

    public function removeLikedVideo(LikedVideo $likedVideo): self
    {
        if ($this->likedVideos->removeElement($likedVideo)) {
            // set the owning side to null (unless already changed)
            if ($likedVideo->getVideo() === $this) {
                $likedVideo->setVideo(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
