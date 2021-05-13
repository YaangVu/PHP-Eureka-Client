<?php

namespace EurekaClient;

use EurekaClient\Instance\Instance;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class EurekaClient
 *
 * @see https://github.com/Netflix/eureka/wiki/Eureka-REST-operations Eureka REST operations
 */
class EurekaClient
{
    /**
     * @var string host
     */
    private string $host;

    /**
     * @var int port
     */
    private int $port;

    /**
     * @var ClientInterface object
     */
    private $client;

    /**
     * @var Instance
     */
    private Instance $instance;

    private $appId;

    private $instanceId;

    private array $data;

    /**
     * @var string $context
     */
    private string $context = 'eureka';

    /**
     * @var string $eurekaUri
     */
    private string $eurekaUri;

    /**
     * EurekaClient constructor.
     */
    public function __construct(Instance $instance)
    {
        $this->client     = new Client();
        $this->instance   = $instance;
        $this->appId      = $instance->get('appId');
        $this->data       = $instance->export();
        $this->instanceId = $instance->get('instanceId');
    }

    /**
     * @return string
     */
    private function getEurekaUri(): string
    {
        return $this->eurekaUri ?: $this->host . ':' . $this->port . '/' . $this->context;
    }

    /**
     * @param $uri
     */
    private function setEurekaUri($uri)
    {
        $this->eurekaUri = $uri;
    }

    private function setEurekaHost($host)
    {
        $this->host = $host;
    }

    private function setEurekaPort($port)
    {
        $this->port = $port;
    }

    /**
     * Register app in eureka.
     *
     * @param string   $appId
     * @param Instance $data
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     */
    public function register(): ResponseInterface
    {
        return $this->client->request('POST', $this->getEurekaUri() . '/apps/' . $this->appId, [
            'json' => [
                'instance' => $this->data
            ]
        ]);
    }

    /**
     * De-register app from eureka.
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function deRegister(): ResponseInterface
    {
        return $this->client->request('DELETE',
                                      $this->getEurekaUri() . '/apps/' . $this->appId . '/' . $this->instanceId);
    }

    /**
     * Send app heartbeat.
     *
     * @param string $appId
     * @param string $instanceId
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     */
    public function heartBeat(): ResponseInterface
    {
        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $this->appId . '/' . $this->instanceId);
    }

    /**
     * Get all registered applications.
     *
     * @return array
     * @throws GuzzleException
     *
     */
    public function getAllApps(): array
    {
        $response = $this->client->request('GET', $this->getEurekaUri() . '/apps', [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get application.
     *
     * @param string $appId
     *
     * @return array
     * @throws GuzzleException
     *
     */
    public function getApp(string $appId = ''): array
    {
        if (!$appId)
            $appId = $this->appId;

        $response = $this->client->request('GET', $this->getEurekaUri() . '/apps/' . $appId, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get application Instance.
     *
     * @param string $appId
     * @param string $instanceId
     *
     * @return array
     * @throws GuzzleException
     *
     */
    public function getAppInstance(string $appId = '', string $instanceId = ''): array
    {
        if (!$appId)
            $appId = $this->appId;
        if (!$instanceId)
            $instanceId = $this->instanceId;

        $response = $this->client->request('GET', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get Instance.
     *
     * @param string $instanceId
     *
     * @return array
     * @throws GuzzleException
     *
     */
    public function getInstance(string $instanceId = ''): array
    {
        if (!$instanceId)
            $instanceId = $this->instanceId;

        $response = $this->client->request('GET', $this->getEurekaUri() . '/instances/' . $instanceId, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Take Instance out of the service.
     *
     * @param string $appId
     * @param string $instanceId
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     */
    public function takeInstanceOut(string $appId = '', string $instanceId = ''): ResponseInterface
    {
        if (!$appId)
            $appId = $this->appId;
        if (!$instanceId)
            $instanceId = $this->instanceId;

        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId . '/status',
                                      [
                                          'query' => [
                                              'value' => 'OUT_OF_SERVICE'
                                          ]
                                      ]);
    }

    /**
     * Put Instance back into the service.
     *
     * @param string $appId
     * @param string $instanceId
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     */
    public function putInstanceBack(string $appId = '', string $instanceId = ''): ResponseInterface
    {
        if (!$appId)
            $appId = $this->appId;
        if (!$instanceId)
            $instanceId = $this->instanceId;

        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId . '/status',
                                      [
                                          'query' => [
                                              'value' => 'UP'
                                          ]
                                      ]);
    }

    /**
     * Update app Instance metadata.
     *
     * @param string $appId
     * @param string $instanceId
     * @param array  $metadata
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     */
    public function updateAppInstanceMetadata(string $appId, string $instanceId, array $metadata): ResponseInterface
    {
        return $this->client->request('PUT',
                                      $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId . '/metadata', [
                                          'query' => $metadata
                                      ]);
    }

    /**
     * Get all instances by a vip address.
     *
     * @param string $vipAddress
     *
     * @return array
     * @throws GuzzleException
     *
     */
    public function getInstancesByVipAddress(string $vipAddress): array
    {
        $response = $this->client->request('GET', $this->getEurekaUri() . '/vips/' . $vipAddress, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get all instances by a secure vip address.
     *
     * @param string $secureVipAddress
     *
     * @return array
     * @throws GuzzleException
     *
     */
    public function getInstancesBySecureVipAddress(string $secureVipAddress): array
    {
        $response = $this->client->request('GET', $this->getEurekaUri() . '/svips/' . $secureVipAddress, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
