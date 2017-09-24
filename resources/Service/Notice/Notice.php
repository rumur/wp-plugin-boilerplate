<?php
namespace WPApp\Service\Notice;

use WPApp\Plugin;

use WP_Error;

/**
 * Class Notice
 * @package WPApp\Service\Notice
 */
abstract class Notice implements INotice
{
	/**
	 * Notifications session key
	 *
	 * @var string
	 *
	 * @since 0.0.1
	 */
	protected $sessionKey;

	/**
	 * List of notifications
	 *
	 * @var array
	 *
	 * @since 0.0.1
	 */
	protected $notifications = [
		'info'    => [], // blue
		'error'   => [], // red
		'warning' => [], // yellow/orange
		'success' => [], // green
	];


	/**
	 * Notice constructor.
	 *
	 * @since 0.0.1
	 */
	public function __construct() {
		$this->ensure( session_id(), __( 'Session is not started.', Plugin::TEXT_DOMAIN ) );

		// Get notifications session key
		$sessionKey = $this->getSessionKey();

		// Get session by key
		$session = isset( $_SESSION[ $sessionKey ] ) && is_array( $_SESSION[ $sessionKey ] ) ? $_SESSION[ $sessionKey ] : [];

		// Prepare session structure
		$_SESSION[ $sessionKey ] = array_merge( $this->notifications, $session );

		$this->notifications = & $_SESSION[ $sessionKey ];
	}

	/**
	 * Get current notifications session key
	 *
	 * @return string
	 * @throws NoticeException
	 *
	 * @since 0.0.1
	 */
	protected function getSessionKey()
	{
		$this->ensure( $this->sessionKey,
			__( 'Notifications session key is not defined for ' . get_class( $this ), Plugin::TEXT_DOMAIN ) );

		return $this->sessionKey;
	}

	/**
	 * Render the HTML of messages
	 *
	 * @param array $messages
	 * @param string $type
	 *
	 * @since 0.0.1
	 */
	abstract protected function render(array $messages = array() , $type = 'error' );

	/**
	 * Show all messages
	 *
	 * @since 0.0.1
	 */
	public function showNotifications()
	{
		$messages = $this->getNotifications();
		foreach ( (array) $messages as $code => $_messages ) {
			if ( empty( $_messages ) ) {
				continue;
			}
			$this->render( $_messages, $code );

			// Clear rendered messages
			$this->clearGroupNotifications( $code );
		}
	}

	/**
	 * Remove all messages from global notifications
	 * Remove messages by Group
	 * Remove single message by Group and Key
	 *
	 * @param string $group,
	 * @param null   $key
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function clearNotifications( $group = 'all', $key = null )
	{
		if ( 'all' == $group ) {
			foreach ( $this->notifications as $group => $_notifications ) {
				$this->clearGroupNotifications( $group );
			}
		} elseif ( array_key_exists( $group, $this->notifications ) ) {
			if ( $key ) {
				$this->clearSingleNotification( $key, $group );
			} else {
				$this->clearGroupNotifications( $group );
			}
		}
	}

	/**
	 * Remove group of messages from the global notifications
	 *
	 * @param string $group
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	protected function clearGroupNotifications( $group )
	{
		$this->notifications[ $group ] = [];
	}

	/**
	 * Remove single message from the global notifications by Key and Group
	 *
	 * @param $key
	 * @param $group
	 *
	 * @since 0.0.1
	 */
	protected function clearSingleNotification( $key, $group )
	{
		if ( isset( $this->notifications[ $group ][ $key ] ) ) {
			unset( $this->notifications[ $group ][ $key ] );
		}
	}

	/**
	 * Add message to the global notifications
	 *
	 * @param string $type
	 * @param string $message
	 *
	 * @since 0.0.1
	 *
	 * @uses force_balance_tags
	 *
	 * @return bool
	 */
	public function addNotification( $type = 'error', $message )
	{
		if ( $message ) {

			if ( ! array_key_exists( $type, $this->notifications ) ) {
				$type = 'error';
			}

			$key = md5( $message );
			$this->notifications[ $type ][ $key ] = force_balance_tags( $message );

			return true;
		}

		return false;
	}

	/**
	 * Get all messages
	 * Get messages by type
	 *
	 * @param string $type
	 *
	 * @since 0.0.1
	 *
	 * @return array|mixed
	 */
	public function getNotifications( $type = 'all' )
	{
		switch ( $type ) {
			case 'error':
			case 'notice':
			case 'warning':
			case 'success':
				return $this->notifications[ $type ];
			default:
				return $this->notifications;
		}
	}

	/**
	 * Add Info messages to the global notifications
	 *
	 * @param $message
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function addInfo( $message )
	{
		$this->addNotification( 'info', $message );
	}

	/**
	 * Add Error messages to the global notifications
	 *
	 * @since 0.0.1
	 *
	 * @param string|WP_Error $message
	 */
	public function addError( $message )
	{
		if ( $message instanceof WP_Error ) {
			$this->addWPError( $message );
		} else {
			$this->addNotification( 'error', $message );
		}
	}

	/**
	 * Add Warning messages to the global notifications
	 *
	 * @since 0.0.1
	 *
	 * @param string $message
	 */
	public function addWarning( $message )
	{
		$this->addNotification( 'warning', $message );
	}

	/**
	 * Add Success messages to the global notifications
	 *
	 * @since 0.0.1
	 *
	 * @param $message
	 */
	public function addSuccess( $message )
	{
		$this->addNotification( 'success', $message );
	}

	/**
	 * Adding error from the WP_Error object
	 *
	 * @param \WP_Error $errors
	 *
	 * @since 0.0.1
	 */
	public function addWPError( WP_Error $errors )
	{
		if ( $errors->get_error_code() ) {
			foreach ( $errors->errors as $code => $message ) {
				$this->addError( $errors->get_error_message( $code ) );

				// Clearing the WP_Error object
				$errors->remove( $code );
			}
		}
	}

	/**
	 * Get Info messages
	 *
	 * @since 0.0.1
	 *
	 * @return array|mixed
	 */
	public function getInfo()
	{
		return $this->getNotifications( 'info' );
	}

	/**
	 * Get Error messages
	 *
	 * @since 0.0.1
	 *
	 * @return array|mixed
	 */
	public function getErrors()
	{
		return $this->getNotifications( 'error' );
	}

	/**
	 * Get Warning messages
	 *
	 * @since 0.0.1
	 *
	 * @return array|mixed
	 */
	public function getWarnings()
	{
		return $this->getNotifications( 'warning' );
	}

	/**
	 * Get Success messages
	 *
	 * @since 0.0.1
	 *
	 * @return array|mixed
	 */
	public function getSuccess()
	{
		return $this->getNotifications( 'success' );
	}

	/**
	 * Check whether Notifications Instance has Errors
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public function hasErrors()
	{
		return $this->hasItems( 'error' );
	}

	/**
	 * Check whether Notifications Instance has Notices
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public function hasNotices()
	{
		return $this->hasItems( 'notice' );
	}

	/**
	 * Check whether Notifications Instance has Warnings
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public function hasWarnings()
	{
		return $this->hasItems( 'warning' );
	}

	/**
	 * Check whether Notifications Instance has Success items
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public function hasSuccess()
	{
		return $this->hasItems( 'success' );
	}

	/**
	 * Whether Notifications has some items or not.
	 *
	 * @param string $type  type of the Items group
	 *
	 * @since 0.0.1
	 *
	 * @return bool
	 */
	public function hasItems( $type = 'all' )
	{
		return ! empty( $this->getNotifications( $type ) );
	}

	/**
	 * The Method serve for throw errors.
	 * Moved to one place.
	 *
	 * @param $expr
	 * @param string $message
	 *
	 * @since 0.0.1
	 *
	 * @throws NoticeException
	 */
	protected function ensure( $expr, $message )
	{
		if ( ! $expr ) {
			throw new NoticeException( $message );
		}
	}
}