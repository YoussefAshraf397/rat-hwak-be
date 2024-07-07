<?php

namespace App\Dto\Payment;

class InitPaymentPageRequest
{
    public string $userName;

    public string $password;

    public string $orderNumber;

    public string $returnUrl;

    public string $amount;

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return InitPaymentPageRequest
     */
    public function setAmount(string $amount): InitPaymentPageRequest
    {
        $this->amount = $amount;
        return $this;
    }



    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     * @return InitPaymentPageRequest
     */
    public function setUserName(string $userName): InitPaymentPageRequest
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return InitPaymentPageRequest
     */
    public function setPassword(string $password): InitPaymentPageRequest
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     * @return InitPaymentPageRequest
     */
    public function setOrderNumber(string $orderNumber): InitPaymentPageRequest
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     * @return InitPaymentPageRequest
     */
    public function setReturnUrl(string $returnUrl): InitPaymentPageRequest
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }


}
