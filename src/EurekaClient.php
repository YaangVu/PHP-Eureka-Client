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
    private $host;

    /**
     * @var int port
     */
    private $port;

    /**
     * @var ClientInterface object
     */
    private $client;

    /**
     * @var string $context
     */
    private $context = 'eureka';


    /**
     * @var string $eurekaUri
     */
    private $eurekaUri;


    /**
     * EurekaClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @return string
     */
    private function getEurekaUri()
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
    public function register($appId, Instance $data)
    {
        return $this->client->request('POST', $this->getEurekaUri() . '/apps/' . $appId, [
            'json' => [
                'instance' => $data->export()
            ]
        ]);
    }

    /**
     * De-register app from eureka.
     *
     * @param string $appId
     * @param string $instanceId
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     */
    public function deRegister($appId, $instanceId)
    {
        return $this->client->request('DELETE', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId);
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
    public function heartBeat($appId, $instanceId)
    {
        return $this->client->request('PUT', $this->getEurekaUri() . '/apps/' . $appId . '/' . $instanceId);
    }

    /**
     * Get all registered applications.
     *
     * @return array
     * @throws GuzzleException
     *
     */
    public function getAllApps()
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
    public function getApp($appId)
    {
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
    public function getAppInstance($appId, $instanceId)
    {
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
    public function getInstance($instanceId)
    {
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
    public function takeInstanceOut($appId, $instanceId)
    {
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
    public function putInstanceBack($appId, $instanceId)
    {
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
    public function updateAppInstanceMetadata($appId, $instanceId, array $metadata)
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
    public function getInstancesByVipAddress($vipAddress)
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
    public function getInstancesBySecureVipAddress($secureVipAddress)
    {
        $response = $this->client->request('GET', $this->getEurekaUri() . '/svips/' . $secureVipAddress, [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
