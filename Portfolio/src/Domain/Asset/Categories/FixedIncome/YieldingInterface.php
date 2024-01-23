<?php

namespace App\Domain\Asset\Categories\FixedIncome;

interface YieldingInterface
{
    public function getAnnualizedReturn();
}