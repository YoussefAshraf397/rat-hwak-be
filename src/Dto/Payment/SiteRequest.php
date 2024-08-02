<?php

namespace App\Dto\Payment;

class SiteRequest
{
    public string $returnUrl;

    public int $amount;

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     * @return SiteRequest
     */
    public function setReturnUrl(string $returnUrl): SiteRequest
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return SiteRequest
     */
    public function setAmount(int $amount): SiteRequest
    {
        $this->amount = $amount;
        return $this;
    }


}
