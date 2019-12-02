<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumReplyRepository")
 */
class ForumReply
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
    private $replyContent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forumReplies")
     */
    private $replyCreator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumTopic", inversedBy="forumReplies")
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumPost", inversedBy="forumReplies")
     */
    private $post;

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

    public function getReplyContent(): ?string
    {
        return $this->replyContent;
    }

    public function setReplyContent(string $replyContent): self
    {
        $this->replyContent = $replyContent;

        return $this;
    }

    public function getReplyCreator(): ?User
    {
        return $this->replyCreator;
    }

    public function setReplyCreator(?User $replyCreator): self
    {
        $this->replyCreator = $replyCreator;

        return $this;
    }

    public function getTopic(): ?ForumTopic
    {
        return $this->topic;
    }

    public function setTopic(?ForumTopic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getPost(): ?ForumPost
    {
        return $this->post;
    }

    public function setPost(?ForumPost $post): self
    {
        $this->post = $post;

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
