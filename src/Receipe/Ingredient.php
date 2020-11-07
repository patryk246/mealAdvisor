<?php


namespace App\Receipe;


use App\ApiClient\SpoonacularApiClient;

class Ingredient
{

    private $id;
    private $name;
    private $amount;
    private $unit;
    private $receipe;
    private $recalculatedUnit;
    private $recalculatedAmount;
    // array useful with recalculating unit - if ingredient is liquid then recalculatedUnit will be ml, etc.
    private $ingredientConsistency = ['liquid' => 'ml', 'solid' => 'g'];

    public function __construct($extendedIngredient)
    {
        $this->setId($extendedIngredient['id']);
        $this->setName($extendedIngredient['name']);
        $this->setAmount($extendedIngredient['measures']['metric']['amount']);
        $this->setUnit($extendedIngredient['measures']['metric']['unitShort']);
        $this->recalculateAmountAndUnit($extendedIngredient['consistency']);
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return Receipe
     */
    public function getReceipe()
    {
        return $this->receipe;
    }

    /**
     * @param Receipe $receipe
     */
    public function setReceipe(Receipe $receipe): void
    {
        $this->receipe = $receipe;
    }

    // if amount of ingredient is given as non-metric (tsp, tsps, Tbsp, Tbsps, oz, pinch, large, small, medium, servings, serving, small head, head)
    public function recalculateAmountAndUnit($consistency): void
    {
        if($this->unit == 'g' || $this->unit == 'kg' || $this->unit == 'ml' || $this->unit == 'l' || $this->unit == '')
        {
            $this->recalculatedAmount = $this->amount;
            $this->recalculatedUnit = $this->unit;
        }
        else
        {
            $apiClient = new SpoonacularApiClient();
            $targetUnit = $this->ingredientConsistency[$consistency];
            $convertedIngredient = $apiClient->convertAmounts($this->getName(), $this->getAmount(), $this->getUnit(), $targetUnit);
            $this->recalculatedAmount = $convertedIngredient['targetAmount'];
            $this->recalculatedUnit = $convertedIngredient['targetUnit'];
        }
    }

}