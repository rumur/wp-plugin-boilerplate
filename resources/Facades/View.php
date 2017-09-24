<?php
namespace WPApp\Facades;

/**
 * Class View
 * @package WPApp\Facades
 */
class View extends Facade {
	/**
	 * Return the igniter service key responsible for the Notice class.
	 * The key must be the same as the one used in the assigned
	 * igniter service.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'view';
	}
}