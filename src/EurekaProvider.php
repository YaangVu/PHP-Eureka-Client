<?php

namespace YaangVu\EurekaClient;

use Illuminate\Support\ServiceProvider;
use YaangVu\EurekaClient\Instance\Instance;

class EurekaProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public static $client;

    /**
     * Register the application services.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function register()
    {
        $appName              = env('APP_NAME', 'UNKNOWN');
        $host                 = env('APP_LOCAL_IP', '127.0.0.1') . ':' . env('APP_PORT', 8000);
        $instanceId           = env('APP_NAME', 'UNKNOWN') . ':' . $host;
        $homeUrl              = "http://$host";
        $statusPageUrl        = "$homeUrl/status";
        $healthCheckUrl       = "$homeUrl/health-check";
        $secureHealthCheckUrl = "https://$host/health-check";

        $instance = new Instance();
        $instance->setInstanceId($instanceId)
                 ->setApp($appName)
                 ->setHostName(env('APP_HOST_NAME', $appName))
                 ->setHomePageUrl($homeUrl)
                 ->setStatusPageUrl($statusPageUrl)
                 ->setHealthCheckUrl($healthCheckUrl)
                 ->setSecureHealthCheckUrl($secureHealthCheckUrl);

        $eurekaUri    = env('EUREKA_URL');
        $eurekaClient = new EurekaClient($instance);
        $eurekaClient->setEurekaUri($eurekaUri);
        $eurekaClient->register();
        $eurekaClient->heartBeat();
    }
}
