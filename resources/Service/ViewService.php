<?php

namespace WPApp\Service;

use Pimple\Container;

use WPApp\Core\ServiceProvider;

/**
 * Class ViewService
 * @package WPApp\Service
 */
class ViewService extends ServiceProvider
{
	/**
	 * @since 0.0.1
	 * @param Container $container
	 */
	public function register( Container $container )
	{
		$container['view'] = $container->factory(function ($c) {
			return new View\View( $c['plugin']->getDirPath() );
		});
	}
}
