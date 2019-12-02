<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumTopicRepository")
 */
class ForumTopic
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
     * @ORM\Column(type="text")
     */
    private $topicContent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forumTopics")
     */
    private $topicCreator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumCategory", inversedBy="forumTopics")
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumPost", mappedBy="postTopic")
     */
    private $forumPosts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumReply", mappedBy="topic")
     */
    private $forumReplies;

    public function __construct()
    {
        $this->forumPosts = new ArrayCollection();
        $this->forumReplies = new ArrayCollection();
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

    public function getTopicContent(): ?string
    {
        return $this->topicContent;
    }

    public function setTopicContent(string $topicContent): self
    {
        $this->topicContent = $topicContent;

        return $this;
    }

    public function getTopicCreator(): ?User
    {
        return $this->topicCreator;
    }

    public function setTopicCreator(?User $topicCreator): self
    {
        $this->topicCreator = $topicCreator;

        return $this;
    }

    public function getCategory(): ?ForumCategory
    {
        return $this->category;
    }

    public function setCategory(?ForumCategory $category): self
    {
        $this->category = $category;

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
            $forumPost->setPostTopic($this);
        }

        return $this;
    }

    public function removeForumPost(ForumPost $forumPost): self
    {
        if ($this->forumPosts->contains($forumPost)) {
            $this->forumPosts->removeElement($forumPost);
            // set the owning side to null (unless already changed)
            if ($forumPost->getPostTopic() === $this) {
                $forumPost->setPostTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ForumReply[]
     */
    public function getForumReplies(): Collection
    {
        return $this->forumReplies;
    }

    public function addForumReply(ForumReply $forumReply): self
    {
        if (!$this->forumReplies->contains($forumReply)) {
            $this->forumReplies[] = $forumReply;
            $forumReply->setTopic($this);
        }

        return $this;
    }

    public function removeForumReply(ForumReply $forumReply): self
    {
        if ($this->forumReplies->contains($forumReply)) {
            $this->forumReplies->removeElement($forumReply);
            // set the owning side to null (unless already changed)
            if ($forumReply->getTopic() === $this) {
                $forumReply->setTopic(null);
            }
        }

        return $this;
    }
}
