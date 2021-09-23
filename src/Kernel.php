<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    
    public function getCacheDir()
    {
        if ($this->environment === 'prod') {
            return sys_get_temp_dir();
        }
        return parent::getCacheDir();
    }

    public function getLogDir()
    {
        if ($this->environment === 'prod') {
            return sys_get_temp_dir();
        }
        return parent::getLogDir();
    }
}

