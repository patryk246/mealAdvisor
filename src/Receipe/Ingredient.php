<?php


namespace App\Receipe;


class Ingredient
{

    private $name;
    private $amount;
    private $unit;
    private $receipe;
    private $recalculatedUnit;
    private $recalculatedAmount;

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

    // if amount of ingredient is given as non-metric (tsp, tsps, Tbsp, Tbsps, pinch, large, small, medium, servings, serving, small head, head)
    public function recalculateAmountAndUnit()
    {
        if($this->unit == 'g' || $this->unit == 'kg' || $this->unit == 'ml' || $this->unit == 'l' || $this->unit == '')
        {
            $this->recalculatedAmount = $this->amount;
            $this->recalculatedUnit = $this->recalculatedUnit;
        }
        else
        {
            if($this->unit == 'tsp' || $this->unit = 'tsps')
            {
                $this->recalculatedUnit = 'g';
                $this->recalculatedAmount = $this->amount * 5;
            }
            else
            {
                if($this->unit == 'Tbsp' || $this->unit == 'Tbsps')
                {
                    $this->recalculatedUnit = 'g';
                    $this->recalculatedAmount = $this->amount * 21.25;
                }
                else
                {
                    $this->recalculatedUnit = '';
                    $this->recalculatedAmount = $this->amount;
                }
            }
        }
    }

}