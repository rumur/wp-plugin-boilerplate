<?php

namespace WPApp\Service;

use Pimple\Container;

use WPApp\Admin\Service\NoticeAdmin;
use WPApp\Core\ServiceProvider;
use WPApp\Service\Notice\NoticeFront;

/**
 * Class NoticeService
 * @package WPApp\Service
 */
class NoticeService extends ServiceProvider
{
	function __construct()
	{
		if ( ! session_id() ) {
			session_start();
		}
	}

	/**
	 * @since 0.0.1
	 * @param Container $container
	 */
	public function register( Container $container )
	{
		$container['notice.admin'] = function () {
			return NoticeAdmin::register();
		};
		$container['notice.front'] = function () {
			return NoticeFront::register();
		};
	}
}
