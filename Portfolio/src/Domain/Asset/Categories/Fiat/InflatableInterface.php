<?php

namespace App\Domain\Asset\Categories\Fiat;

/*
 * self-explanatory
 * */
interface InflatableInterface
{
    public function getInflationRate();
}