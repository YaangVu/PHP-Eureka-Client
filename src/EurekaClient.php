<?php

namespace YaangVu\EurekaClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use YaangVu\EurekaClient\Instance\Instance;

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

    private $app;

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
        $this->app        = $instance->get('app');
        $this->data       = $instance->export();
        $this->instanceId = $instance->get('instanceId');
    }

    /**
     * @return string
     */
    public function getEurekaUri(): string
    {
        return $this->eurekaUri ?: $this->host . ':' . $this->port . '/' . $this->context;
    }

    /**
     * @param $uri
     *
     * @return EurekaClient
     * ≈*/
    public function setEurekaUri($uri): EurekaClient
    {
        $this->eurekaUri = $uri;

        return $this;
    }

    public function setEurekaHost($host): EurekaClient
    {
        $this->host = $host;

        return $this;
    }

    public function setEurekaPort($port): EurekaClient
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Register app in eureka.
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function register(): ResponseInterface
    {
        Log::info("Register to Eureka: " . $this->getEurekaUri(), ['instance' => $this->data]);

        return $this->client->request('POST', $this->getEurekaUri() . '/apps/' . $this->app, [
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
                                      $this->getEurekaUri() . '/apps/' . $this->app . '/' . $this->instanceId);
    }

    /**
     * Send app heartbeat.
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function heartBeat(): ResponseInterface
    {
        Log::info("Send heartbeat to Eureka: " . $this->getEurekaUri() . " with instance: $this->app/$this->instanceId");

        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $this->app . '/' . $this->instanceId);
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
     * @param string $app
     *
     * @return array
     * @throws GuzzleException
     *
     */
    public function getApp(string $app = ''): array
    {
        if (!$app)
            $app = $this->app;

        $response = $this->client->request('GET', $this->getEurekaUri() . '/apps/' . $app, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get application Instance.
     *
     * @param string $app
     * @param string $instanceId
     *
     * @return array
     * @throws GuzzleException
     *
     */
    public function getAppInstance(string $app = '', string $instanceId = ''): array
    {
        if (!$app)
            $app = $this->app;
        if (!$instanceId)
            $instanceId = $this->instanceId;

        $response = $this->client->request('GET', $this->getEurekaUri() . '/apps/' . $app . '/' . $instanceId, [
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
     * @param string $app
     * @param string $instanceId
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     */
    public function takeInstanceOut(string $app = '', string $instanceId = ''): ResponseInterface
    {
        if (!$app)
            $app = $this->app;
        if (!$instanceId)
            $instanceId = $this->instanceId;

        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $app . '/' . $instanceId . '/status',
                                      [
                                          'query' => [
                                              'value' => 'OUT_OF_SERVICE'
                                          ]
                                      ]);
    }

    /**
     * Put Instance back into the service.
     *
     * @param string $app
     * @param string $instanceId
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     */
    public function putInstanceBack(string $app = '', string $instanceId = ''): ResponseInterface
    {
        if (!$app)
            $app = $this->app;
        if (!$instanceId)
            $instanceId = $this->instanceId;

        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $app . '/' . $instanceId . '/status',
                                      [
                                          'query' => [
                                              'value' => 'UP'
                                          ]
                                      ]);
    }

    /**
     * Update app Instance metadata.
     *
     * @param string $app
     * @param string $instanceId
     * @param array  $metadata
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     */
    public function updateAppInstanceMetadata(string $app, string $instanceId, array $metadata): ResponseInterface
    {
        return $this->client->request('PUT',
                                      $this->getEurekaUri() . '/apps/' . $app . '/' . $instanceId . '/metadata', [
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
