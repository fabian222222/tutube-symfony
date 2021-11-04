<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profile_image;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     */
    private $follower;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="user", orphanRemoval=true)
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=VideoSeen::class, mappedBy="user")
     */
    private $videoSeens;

    /**
     * @ORM\OneToMany(targetEntity=LikedVideo::class, mappedBy="user")
     */
    private $likedVideos;

    public function __construct()
    {
        $this->follower = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->videoSeens = new ArrayCollection();
        $this->likedVideos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profile_image;
    }

    public function setProfileImage(string $profile_image): self
    {
        $this->profile_image = $profile_image;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFollower(): Collection
    {
        return $this->follower;
    }

    public function addFollower(self $follower): self
    {
        if (!$this->follower->contains($follower)) {
            $this->follower[] = $follower;
        }

        return $this;
    }

    public function removeFollower(self $follower): self
    {
        $this->follower->removeElement($follower);

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setUser($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getUser() === $this) {
                $video->setUser(null);
            }
        }

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
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
            $videoSeen->setUser($this);
        }

        return $this;
    }

    public function removeVideoSeen(VideoSeen $videoSeen): self
    {
        if ($this->videoSeens->removeElement($videoSeen)) {
            // set the owning side to null (unless already changed)
            if ($videoSeen->getUser() === $this) {
                $videoSeen->setUser(null);
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
            $likedVideo->setUser($this);
        }

        return $this;
    }

    public function removeLikedVideo(LikedVideo $likedVideo): self
    {
        if ($this->likedVideos->removeElement($likedVideo)) {
            // set the owning side to null (unless already changed)
            if ($likedVideo->getUser() === $this) {
                $likedVideo->setUser(null);
            }
        }

        return $this;
    }
}
