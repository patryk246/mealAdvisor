<?php


namespace App\Receipe;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
use function Sodium\add;

class Receipe
{

    private $id;
    private $title;
    private $image;
    private $servings;
    private $readyInMinutes;
    private $sourceUrl;
    private $healthScore;
    private $analyzedInstructions;
    private $ingredients;
    private $summary;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return \http\Url
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param \http\Url $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getServings()
    {
        return $this->servings;
    }

    /**
     * @param int $servings
     */
    public function setServings($servings): void
    {
        $this->servings = $servings;
    }

    /**
     * @return int
     */
    public function getReadyInMinutes()
    {
        return $this->readyInMinutes;
    }

    /**
     * @param int $readyInMinutes
     */
    public function setReadyInMinutes($readyInMinutes): void
    {
        $this->readyInMinutes = $readyInMinutes;
    }

    /**
     * @return \http\Url
     */
    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    /**
     * @param \http\Url $sourceUrl
     */
    public function setSourceUrl($sourceUrl): void
    {
        $this->sourceUrl = $sourceUrl;
    }

    /**
     * @return float
     */
    public function getHealthScore()
    {
        return $this->healthScore;
    }

    /**
     * @param float $healthScore
     */
    public function setHealthScore($healthScore): void
    {
        $this->healthScore = $healthScore;
    }

    /**
     * @return Collection
     */
    public function getAnalyzedInstructions(): Collection
    {
        return $this->analyzedInstructions;
    }

    public function addAnalyzedInstruction(int $number, string $step): self
    {
        if(!$this->analyzedInstructions->containsKey($number))
        {
            $this->analyzedInstructions[$number] = $step;
        }
        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }


    public function addIngredient(Ingredient $ingredient): self
    {
        if(!$this->ingredients->contains($ingredient))
        {
            $this->ingredients[] = $ingredient;
            $ingredient.setReceipe($this);
        }
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setSummary($summary): void
    {
        $this->summary = $summary;
    }

}