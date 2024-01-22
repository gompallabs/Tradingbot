<?php

namespace App\Tests\Mock;

use Lin\Bitget\Api\ContractV2\Account;

class BitgetContractV2Mock extends Account
{
    public function auth(): array
    {
        $this->nonce();
        $this->signature();
        $this->headers();
        $this->options();

        return [
            'nonce' => $this->nonce,
            'signature' => $this->signature,
            'headers' => $this->headers,
            'options' => $this->options,
        ];
    }
}
