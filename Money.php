<?php

namespace App\ValueObjects\Smc;

use InvalidArgumentException;

class Money
{
    private int $amount;

    /**
     * @param int $amount
     * @throws InvalidArgumentException
     *
     */
    public function __construct(int $amount)
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Money is not valid');
        }
        $this->amount = $amount;
    }

    public function amount(): string
    {
        return $this->amount;
    }
}
