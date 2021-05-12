<?php

namespace EurekaClient\Instance;

/**
 * Class Instance
 *
 * @package EurekaClient\Instance
 */
class Instance extends Parameters
{
    public function __construct()
    {
        $this->setInstanceId("Unknown")
             ->setHostName('Unknown')
             ->setApp("Unknown")
             ->setIpAddr('127.0.0.1')
             ->setPort(8000)
             ->setSecurePort(433)
             ->setHomePageUrl('http://localhost')
             ->setStatusPageUrl('http://localhost/status')
             ->setHealthCheckUrl('http://localhost/health-check')
             ->setSecureHealthCheckUrl('https://localhost/health-check')
             ->setVipAddress('unknown_vip_address')
             ->setSecureVipAddress('unknown_secure_vip_address')
             ->setMetadata(new Metadata())
             ->setDataCenterInfo(new DataCenterInfo());
    }

    /**
     * @param string $instanceId
     *
     * @return $this
     */
    public function setInstanceId($instanceId)
    {
        return $this->set('instanceId', $instanceId);
    }

    /**
     * @param string $hostName
     *
     * @return $this
     */
    public function setHostName($hostName)
    {
        return $this->set('hostName', $hostName);
    }

    /**
     * @param string $app
     *
     * @return $this
     */
    public function setApp($app)
    {
        return $this->set('app', $app);
    }

    /**
     * @param string $ipAddr
     *
     * @return $this
     */
    public function setIpAddr($ipAddr)
    {
        return $this->set('ipAddr', $ipAddr);
    }

    /**
     * @param int  $port
     * @param bool $enabled
     *
     * @return $this
     */
    public function setPort($port, $enabled = true)
    {
        return $this->set('port', [
            '$'        => $port,
            '@enabled' => ($enabled) ? 'true' : 'false'
        ]);
    }

    /**
     * @param string $port
     * @param bool   $enabled
     *
     * @return $this
     */
    public function setSecurePort($port, $enabled = true)
    {
        return $this->set('securePort', [
            '$'        => $port,
            '@enabled' => ($enabled) ? 'true' : 'false'
        ]);
    }

    /**
     * @param string $homePageUrl
     *
     * @return $this
     */
    public function setHomePageUrl($homePageUrl)
    {
        return $this->set('homePageUrl', $homePageUrl);
    }

    /**
     * @param string $statusPageUrl
     *
     * @return $this
     */
    public function setStatusPageUrl($statusPageUrl)
    {
        return $this->set('statusPageUrl', $statusPageUrl);
    }

    /**
     * @param string $healthCheckUrl
     *
     * @return $this
     */
    public function setHealthCheckUrl($healthCheckUrl)
    {
        return $this->set('healthCheckUrl', $healthCheckUrl);
    }

    /**
     * @param string $secureHealthCheckUrl
     *
     * @return $this
     */
    public function setSecureHealthCheckUrl($secureHealthCheckUrl)
    {
        return $this->set('secureHealthCheckUrl', $secureHealthCheckUrl);
    }

    /**
     * @param string $vipAddress
     *
     * @return $this
     */
    public function setVipAddress($vipAddress)
    {
        return $this->set('vipAddress', $vipAddress);
    }

    /**
     * @param string $secureVipAddress
     *
     * @return $this
     */
    public function setSecureVipAddress($secureVipAddress)
    {
        return $this->set('secureVipAddress', $secureVipAddress);
    }

    /**
     * @param Metadata $metadata
     *
     * @return $this
     */
    public function setMetadata(Metadata $metadata)
    {
        return $this->set('metadata', $metadata->export());
    }

    /**
     * @param DataCenterInfo $dataCenterInfo
     *
     * @return $this
     */
    public function setDataCenterInfo(DataCenterInfo $dataCenterInfo)
    {
        return $this->set('dataCenterInfo', $dataCenterInfo->export());
    }
}
