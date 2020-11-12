<?php

namespace App\Entity;

use App\Repository\UserViewedReceipeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserViewedReceipeRepository::class)
 */
class UserViewedReceipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userViewedReceipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=ReceipeReference::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $receipeReference;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastView;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFavourite;

    public function __construct(?User $user, ?ReceipeReference $receipeReference)
    {
        $this->setUser($user);
        $this->setReceipeReference($receipeReference);
        $this->setLastView(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReceipeReference(): ?ReceipeReference
    {
        return $this->receipeReference;
    }

    public function setReceipeReference(?ReceipeReference $receipeReference): self
    {
        $this->receipeReference = $receipeReference;

        return $this;
    }

    public function getLastView(): ?\DateTimeInterface
    {
        return $this->lastView;
    }

    public function setLastView(\DateTimeInterface $lastView): self
    {
        $this->lastView = $lastView;

        return $this;
    }

    public function getIsFavourite(): ?bool
    {
        return $this->isFavourite;
    }

    public function setIsFavourite(?bool $isFavourite): self
    {
        $this->isFavourite = $isFavourite;

        return $this;
    }
}
