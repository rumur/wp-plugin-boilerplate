<?php

namespace WPApp\Service;

use Pimple\Container;

use WPApp\Core\ServiceProvider;

/**
 * Class LoggerService
 * @package WPApp\Service
 */
class LoggerService extends ServiceProvider
{
    /**
     * @since 0.0.1
     * @param Container $container
     */
    public function register( Container $container )
    {
        $container['logger'] = $container->factory(function ($c) {
            // @TODO retrun the Instance of Logger.
        });
    }

}
