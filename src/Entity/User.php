<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Count;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
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
    private $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $growth;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="smallint")
     */
    private $isActive=0;

    /**
     * @ORM\OneToMany(targetEntity=UserProduct::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     * @Count(min = 1, minMessage = "At least one item must be selected")
     */
    private $userProducts;

    /**
     * @ORM\OneToMany(targetEntity=UserViewedReceipe::class, mappedBy="user", orphanRemoval=true)
     * @ORM\OrderBy({"lastView" = "DESC"})
     */
    private $userViewedReceipes;

    public function __construct()
    {
        $this->userProducts = new ArrayCollection();
        $this->userViewedReceipes = new ArrayCollection();
        $this->userFavouriteReceipes = new ArrayCollection();
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getGrowth(): ?int
    {
        return $this->growth;
    }

    public function setGrowth(?int $growth): self
    {
        $this->growth = $growth;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getIsActive(): ?int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|UserProduct[]
     */
    public function getUserProducts(): Collection
    {
        return $this->userProducts;
    }

    public function addUserProduct(UserProduct $userProduct): self
    {
        if (!$this->userProducts->contains($userProduct)) {
            foreach ($this->userProducts as $userProductTemp)
            {
                if($userProduct->getProduct()->getName() == $userProductTemp->getProduct()->getName())
                {
                    throw new Exception('You already have this product!');
                }
            }
            $this->userProducts->add($userProduct);
            $userProduct->setUser($this);
        }
        return $this;
    }

    public function removeUserProduct(UserProduct $userProduct): self
    {
        if ($this->userProducts->removeElement($userProduct)) {
            // set the owning side to null (unless already changed)
            if ($userProduct->getUser() === $this) {
                $userProduct->setUser(null);
            }
        }
        else{
            throw new Exception('You can not delete product which you do not have!');
        }

        return $this;
    }

    /**
     * @return Collection|UserViewedReceipe[]
     */
    public function getUserViewedReceipes(): Collection
    {
        return $this->userViewedReceipes;
    }

    public function addUserViewedReceipe(UserViewedReceipe $userViewedReceipe): self
    {
        if (!$this->userViewedReceipes->contains($userViewedReceipe)) {
            $this->userViewedReceipes[] = $userViewedReceipe;
            $userViewedReceipe->setUserId($this);
        }

        return $this;
    }

    public function removeUserViewedReceipe(UserViewedReceipe $userViewedReceipe): self
    {
        if ($this->userViewedReceipes->removeElement($userViewedReceipe)) {
            // set the owning side to null (unless already changed)
            if ($userViewedReceipe->getUserId() === $this) {
                $userViewedReceipe->setUserId(null);
            }
        }

        return $this;
    }

}
