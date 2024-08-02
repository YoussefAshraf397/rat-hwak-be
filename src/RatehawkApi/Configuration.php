<?php

namespace App\RatehawkApi;


class Configuration
{
    public function getKeyId(): string
    {
        return getenv('RATEHAWK_KEY_ID');
    }

    public function getApiKey(): string
    {
        return getenv('RATEHAWK_API_KEY');
    }
}

