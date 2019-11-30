<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumPostRepository")
 */
class ForumPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $postContent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forumPosts")
     */
    private $postCreator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumTopic", inversedBy="forumPosts")
     */
    private $postTopic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumCategory", inversedBy="forumPosts")
     */
    private $postCategory;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostContent(): ?string
    {
        return $this->postContent;
    }

    public function setPostContent(string $postContent): self
    {
        $this->postContent = $postContent;

        return $this;
    }

    public function getPostCreator(): ?User
    {
        return $this->postCreator;
    }

    public function setPostCreator(?User $postCreator): self
    {
        $this->postCreator = $postCreator;

        return $this;
    }

    public function getPostTopic(): ?ForumTopic
    {
        return $this->postTopic;
    }

    public function setPostTopic(?ForumTopic $postTopic): self
    {
        $this->postTopic = $postTopic;

        return $this;
    }

    public function getPostCategory(): ?ForumCategory
    {
        return $this->postCategory;
    }

    public function setPostCategory(?ForumCategory $postCategory): self
    {
        $this->postCategory = $postCategory;

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
}
