<?php

namespace App\Entity;

use App\Repository\ReceipeReferenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReceipeReferenceRepository::class)
 */
class ReceipeReference
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="integer")
     */
    private $receipeId;

    public function __construct(string $title, string $imageUrl, int $receipeId)
    {
        $this->setTitle($title);
        $this->setImageUrl($imageUrl);
        $this->setReceipeId($receipeId);
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

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getReceipeId(): ?int
    {
        return $this->receipeId;
    }

    public function setReceipeId(int $receipeId): self
    {
        $this->receipeId = $receipeId;

        return $this;
    }
}
