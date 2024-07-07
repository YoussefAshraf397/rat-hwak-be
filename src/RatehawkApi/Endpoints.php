<?php

namespace App\RatehawkApi;

abstract class Endpoints
{
    private const BASE_PATH = 'https://api.worldota.net/';
    public const HOTEL_INFO_DUMP = Endpoints::BASE_PATH . "api/b2b/v3/hotel/info/dump/";
    public const HOTEL_INCREMENTAL_DUMP = Endpoints::BASE_PATH . "api/b2b/v3/hotel/info/incremental_dump/";
    public const HOTEL_REGION_DUMP = Endpoints::BASE_PATH . "api/b2b/v3/hotel/region/dump/";
    public const HOTEL_REVIEW_DUMP = Endpoints::BASE_PATH. "api/b2b/v3/hotel/reviews/dump/";
}
