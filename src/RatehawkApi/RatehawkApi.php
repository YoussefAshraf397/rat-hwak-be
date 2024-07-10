<?php

namespace App\RatehawkApi;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class RatehawkApi
{
    /**
     * @var HttpClient
     */
    private HttpClient $httpClient;

    private Logger $logger;

    private string $downLoadDirectory;

    /**
     * @param string $key
     * @param array $config Guzzle http client default request options
     * @throws InvalidAuthData
     *
     * @see \GuzzleHttp\RequestOptions for a list of available request options.
     */
    public function __construct(string $key, string $downLoadDirectory, array $config = [])
    {
        $logDir = dirname(__DIR__) . '/Logs/ApiLog.log';
        $handler = new RotatingFileHandler($logDir);

        $this->logger = new Logger('', [], [], new \DateTimeZone('Europe/Moscow'));
        $this->logger->pushHandler($handler);

        $this->downLoadDirectory = $downLoadDirectory;

        $config = RatehawkApi::_add_auth($config, getenv('RATEHAWK_KEY_ID'), getenv('RATEHAWK_API_KEY'));
        $config = RatehawkApi::_add_user_agent($config);
        $this->httpClient = new HttpClient($config);
    }

    /**
     * @param array $config
     * @param string $key
     * @return array
     */
    private static function _add_auth(array $config, string $keyId, string $apiKey): array
    {
        $config[RequestOptions::AUTH] = [$keyId, $apiKey];
        return $config;
    }

    /**
     * @param array $config
     * @return array
     */
    private static function _add_user_agent(array $config): array
    {
        $papiSdkVersion = Constants::NAME . '/' . Constants::VERSION;
        $httpClientVersion = Utils::defaultUserAgent();
        $phpVersion = 'php/' . PHP_VERSION;
        $headers = ['User-Agent' => $papiSdkVersion . ' ' . $httpClientVersion . ' (' . $phpVersion . ')'];

        if (!isset($config[RequestOptions::HEADERS])) {
            $config[RequestOptions::HEADERS] = $headers;
        } else {
            $config[RequestOptions::HEADERS] += $headers;
        }

        return $config;
    }

    public function getRegionDump(): string
    {
        $fileMetaData = $this->getFileMetaData(Endpoints::HOTEL_REGION_DUMP);

        return $this->getAndSaveFile($fileMetaData['data']['url']);
    }

    protected function getFileMetaData(string $endpoint, array $options = []): array
    {
        $response = json_decode(
            $this->httpClient
            ->post($endpoint, $options)
            ->getBody()
            ->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (empty($response['data']['url'])) {
            throw new \Exception('EMPTY HOTELS DUMP URL');
        }
        return $response;
    }

    protected function getAndSaveFile(string $url): string
    {
        $tempFileName = $this->downLoadDirectory . DIRECTORY_SEPARATOR . md5(microtime(true)) . '.zstd';

        $tempFile = fopen($tempFileName, 'wb');

        $httpClientWOBasic = new HttpClient();

        $httpClientWOBasic->get(
            $url,
            [
                RequestOptions::SINK => $tempFile,
            ]
        );

        return $tempFileName;
    }

    public function getHotelsDump(): string
    {
        $options['body'] = json_encode([
            'inventory' => 'all',
            'language' => 'en',
        ], JSON_THROW_ON_ERROR);

        $fileMetaData = $this->getFileMetaData(Endpoints::HOTEL_INFO_DUMP, $options);

        return $this->getAndSaveFile($fileMetaData['data']['url']);
    }

    public function getReviewsDump(): string
    {
        $options['body'] = json_encode([
            'language' => 'en',
        ], JSON_THROW_ON_ERROR);

        $fileMetaData = $this->getFileMetaData(Endpoints::HOTEL_REVIEW_DUMP, $options);

        return $this->getAndSaveFile($fileMetaData['data']['url']);
    }

    public function getHotelsIncremental(string $lastUpdate): ?array
    {
        $options['body'] = json_encode([
            'inventory' => 'all',
            'language' => 'en',
        ], JSON_THROW_ON_ERROR);

        $fileMetaData = $this->getFileMetaData(Endpoints::HOTEL_INCREMENTAL_DUMP, $options);

        if ($fileMetaData['data']['last_update'] === $lastUpdate) {
            return null;
        }

        return [
            'filename' => $this->getAndSaveFile($fileMetaData['data']['url']),
            'last_update' => $fileMetaData['data']['last_update'],
        ];
    }

    public function getSearchRegion(array $options = []): array
    {
        $response = json_decode(
            $this->httpClient
                ->post(Endpoints::HOTEL_SEARCH_REGION , $options)
                ->getBody()
                ->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        if ($response['status'] !== 'ok') {
            throw new \Exception('SEARCH REGION FAILED');
        }
        return [
            'hotels' => $response['data']['hotels'],
        ];
    }
}
