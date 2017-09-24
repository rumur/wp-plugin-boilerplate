<?php
namespace WPApp\Facades;

/**
 * Class NoticeAdmin
 * @package WPApp\Facades
 */
class NoticeAdmin extends Facade {
	/**
	 * Return the igniter service key responsible for the Notice class.
	 * The key must be the same as the one used in the assigned
	 * igniter service.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'notice.admin';
	}
}