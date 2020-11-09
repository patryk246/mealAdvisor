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

    public function __construct($extendedIngredient)
    {
        $this->setId($extendedIngredient['id']);
        $this->setName($extendedIngredient['name']);
        $this->setAmount($extendedIngredient['amount']);
        $this->setUnit($extendedIngredient['unit']);
        $this->setRecalculatedAmount($extendedIngredient['measures']['metric']['amount']);
        $this->setRecalculatedUnit($extendedIngredient['measures']['metric']['unitShort']);
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

    /**
     * @return mixed
     */
    public function getRecalculatedUnit()
    {
        return $this->recalculatedUnit;
    }

    /**
     * @param mixed $recalculatedUnit
     */
    public function setRecalculatedUnit($recalculatedUnit): void
    {
        $this->recalculatedUnit = $recalculatedUnit;
    }

    /**
     * @return mixed
     */
    public function getRecalculatedAmount()
    {
        return $this->recalculatedAmount;
    }

    /**
     * @param mixed $recalculatedAmount
     */
    public function setRecalculatedAmount($recalculatedAmount): void
    {
        $this->recalculatedAmount = $recalculatedAmount;
    }



    // if amount of ingredient is given as non-metric (tsp, tsps, Tbsp, Tbsps, oz, pinch, large, small, medium, servings, serving, small head, head)
    public function recalculateAmountAndUnit($targetUnit): void
    {
        if($this->recalculatedUnit != $targetUnit)
        {
            $apiClient = new SpoonacularApiClient();
            $convertedIngredient = $apiClient->convertAmounts($this->getName(), $this->getAmount(), $this->getUnit(), $targetUnit);
            $this->recalculatedAmount = $convertedIngredient['targetAmount'];
            $this->recalculatedUnit = $convertedIngredient['targetUnit'];
        }
    }

}