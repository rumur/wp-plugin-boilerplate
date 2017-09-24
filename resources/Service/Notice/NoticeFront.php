<?php
namespace WPApp\Service\Notice;

/**
 * Class NoticeFront
 * @package Rumur\Service\Notice
 */
class NoticeFront extends Notice {
	/**
	 * Session key for Front End Notifications.
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	protected $sessionKey = 'notice.front';

	/**
	 * @since 0.0.1
	 *
	 * @param array $messages
	 * @param string $type
	 */
	protected function render( array $messages = array(), $type = 'error' ) {
		// TODO: Implement render() method.
	}

	/**
	 * @since 0.0.1
	 *
	 * @return NoticeFront
	 */
	public static function register() {
		$self = new self();

		add_action( 'wp_footer', [ $self, 'showNotifications' ] );

		return $self;
	}
}