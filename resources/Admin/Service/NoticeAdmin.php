<?php
namespace WPApp\Admin\Service;

use WPApp\Service\Notice\Notice;

use WP_Error;

class NoticeAdmin extends Notice
{
	/**
	 * Session key for Admin Notifications
	 *
	 * @var string
	 */
	protected $sessionKey = 'notice.admin';

	/**
	 * Run the Instance with WP hooks
	 *
	 * @since 0.0.1
	 *
	 * @return NoticeAdmin
	 */
	public static function register()
	{
		$self = new self();

		add_action( 'admin_notices', [ $self, 'showNotifications' ] );

		return $self;
	}

	/**
	 * Render the HTML of messages
	 *
	 * @param array $messages
	 * @param string $type
	 *
	 * @since 0.0.1
	 */
	protected function render( array $messages = array(), $type = 'error' )
	{
		$notice_class = array( 'notice' );

		array_push( $notice_class, 'notice-' . $type );

		$html = apply_filters( __NAMESPACE__ . '_admin_template', '<div class="%1$s"><p><strong>%2$s</strong></p></div>' );

		array_walk( $messages, function( $message ) use ( $notice_class, $html ) {
			$class = $notice_class;

			if ( $message['dismissible'] ) {
				array_push( $class, 'is-dismissible' );
			}

			printf( $html, join( ' ', $class ), $message['message'] );
		});
	}

	/**
	 * Add message to the global notifications
	 *
	 * @param string $type
	 * @param string $message
	 * @param bool $dismissible
	 *
	 * @since 0.0.1
	 *
	 * @uses force_balance_tags
	 *
	 * @return bool
	 */
	public function addNotification( $type = 'error', $message, $dismissible = false )
	{
		if ( $message ) {

			if ( ! array_key_exists( $type, $this->notifications ) ) {
				$type = 'error';
			}

			$key = md5( $message );
			$this->notifications[ $type ][ $key ]['message'] = force_balance_tags( $message );
			$this->notifications[ $type ][ $key ]['dismissible'] = $dismissible;

			return true;
		}

		return false;
	}

	/**
	 * Add an Info messages to the global notifications
	 *
	 * @param string $message
	 * @param bool $dismissible
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public function addInfo( $message, $dismissible = false )
	{
		return $this->addNotification( 'info', $message, $dismissible );
	}

	/**
	 * Add an Error messages to the global notifications
	 *
	 * @param string|\WP_Error $message
	 * @param bool $dismissible
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public function addError( $message, $dismissible = false )
	{
		if ( is_a( $message, '\WP_Error' ) ) {
			return $this->addWPError( $message, $dismissible );
		}

		return $this->addNotification( 'error', $message, $dismissible );
	}

	/**
	 * Add Warning messages to the global notifications
	 *
	 * @param string $message
	 * @param bool $dismissible
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public function addWarning( $message, $dismissible = false )
	{
		return $this->addNotification( 'warning', $message, $dismissible );
	}

	/**
	 * Add Success messages to the global notifications
	 *
	 * @param string $message
	 * @param bool $dismissible
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public function addSuccess( $message, $dismissible = false )
	{
		return $this->addNotification( 'success', $message, $dismissible );
	}

	/**
	 * Add a WP_Error messages to the global notifications
	 *
	 * @param \WP_Error $errors
	 * @param bool $dismissible
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public function addWPError( WP_Error $errors, $dismissible = false )
	{
		if ( $errors->get_error_code() ) {
			foreach ( $errors->errors as $code => $message ) {
				$this->addError( $errors->get_error_message( $code ), $dismissible );

				// Clearing the WP_Error object
				$errors->remove( $code );
			}

			return true;
		}

		return false;
	}
}