<?php

namespace Quote\Api\Infrastructure\Framework\Symfony;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    const PATH_CONFIG = __DIR__ . '/../../../../../config/';

    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(self::PATH_CONFIG . '{packages}/*.yaml');
        $container->import(self::PATH_CONFIG . '/{packages}/'.$this->environment.'/*.yaml');

        $container->import(self::PATH_CONFIG . '/services.yaml');
        $container->import(self::PATH_CONFIG . '{services}_'.$this->environment.'.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(self::PATH_CONFIG . '{routes}/'.$this->environment.'/*.yaml');
        $routes->import(self::PATH_CONFIG . '{routes}/*.yaml');

        $routes->import(self::PATH_CONFIG . '/routes.yaml');
    }
}
