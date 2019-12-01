<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumCategoryRepository")
 */
class ForumCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $relatedDiscordChannelId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumTopic", mappedBy="category")
     */
    private $forumTopics;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumPost", mappedBy="postCategory")
     */
    private $forumPosts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->forumTopics = new ArrayCollection();
        $this->forumPosts = new ArrayCollection();
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

    public function getRelatedDiscordChannelId(): ?string
    {
        return $this->relatedDiscordChannelId;
    }

    public function setRelatedDiscordChannelId(?string $relatedDiscordChannelId): self
    {
        $this->relatedDiscordChannelId = $relatedDiscordChannelId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|ForumTopic[]
     */
    public function getForumTopics(): Collection
    {
        return $this->forumTopics;
    }

    public function addForumTopic(ForumTopic $forumTopic): self
    {
        if (!$this->forumTopics->contains($forumTopic)) {
            $this->forumTopics[] = $forumTopic;
            $forumTopic->setCategory($this);
        }

        return $this;
    }

    public function removeForumTopic(ForumTopic $forumTopic): self
    {
        if ($this->forumTopics->contains($forumTopic)) {
            $this->forumTopics->removeElement($forumTopic);
            // set the owning side to null (unless already changed)
            if ($forumTopic->getCategory() === $this) {
                $forumTopic->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ForumPost[]
     */
    public function getForumPosts(): Collection
    {
        return $this->forumPosts;
    }

    public function addForumPost(ForumPost $forumPost): self
    {
        if (!$this->forumPosts->contains($forumPost)) {
            $this->forumPosts[] = $forumPost;
            $forumPost->setPostCategory($this);
        }

        return $this;
    }

    public function removeForumPost(ForumPost $forumPost): self
    {
        if ($this->forumPosts->contains($forumPost)) {
            $this->forumPosts->removeElement($forumPost);
            // set the owning side to null (unless already changed)
            if ($forumPost->getPostCategory() === $this) {
                $forumPost->setPostCategory(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
