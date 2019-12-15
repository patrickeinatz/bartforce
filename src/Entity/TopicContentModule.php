<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicContentModuleRepository")
 */
class TopicContentModule
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
     * @ORM\Column(type="string", length=50)
     */
    private $icon;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLinkedContend;

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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIsLinkedContend(): ?bool
    {
        return $this->isLinkedContend;
    }

    public function setIsLinkedContend(bool $isLinkedContend): self
    {
        $this->isLinkedContend = $isLinkedContend;

        return $this;
    }
}
