<?php
namespace WPApp\Facades;

/**
 * Class NoticeFront
 * @package WPApp\Facades
 */
class NoticeFront extends Facade {
	/**
	 * Return the igniter service key responsible for the Notice class.
	 * The key must be the same as the one used in the assigned
	 * igniter service.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'notice.front';
	}
}