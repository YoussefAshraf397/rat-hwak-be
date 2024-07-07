<?php

namespace App\Dto\Payment;

class InitPaymentPageResponse
{
    public int $errorCode = 0;

    public string $errorMessage = '';

    public string $orderId;

    public string $formUrl;

    /**
     * @return string
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     * @return InitPaymentPageResponse
     */
    public function setErrorCode(string $errorCode): InitPaymentPageResponse
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     * @return InitPaymentPageResponse
     */
    public function setErrorMessage(string $errorMessage): InitPaymentPageResponse
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     * @return InitPaymentPageResponse
     */
    public function setOrderId(string $orderId): InitPaymentPageResponse
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormUrl(): string
    {
        return $this->formUrl;
    }

    /**
     * @param string $formUrl
     * @return InitPaymentPageResponse
     */
    public function setFormUrl(string $formUrl): InitPaymentPageResponse
    {
        $this->formUrl = $formUrl;
        return $this;
    }


}
