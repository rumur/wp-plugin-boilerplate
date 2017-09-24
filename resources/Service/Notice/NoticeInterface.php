<?php
namespace WPApp\Service\Notice;

/**
 * Interface NoticeInterface
 * @package Rumur\Utils\Notice
 */
interface INotice {

	/**
	 * Run the Instance with WP hooks
	 *
	 * @since 0.0.1
	 *
	 * @return INotice
	 */
	public static function register();

	/**
	 * Add message to the notifications
	 *
	 * @since 0.0.1
	 *
	 * @param string $type
	 * @param string $message
	 *
	 * @return bool
	 */
	public function addNotification( $type = 'error', $message );

	/**
	 * Get all messages
	 * Get messages by type
	 *
	 * @since 0.0.1
	 *
	 * @param string $type
	 *
	 * @return array|mixed
	 */
	public function getNotifications( $type = 'all' );

	/**
	 * Remove all messages from global notifications
	 * Remove messages by Group
	 * Remove single message by Group and Key
	 *
	 * @since 0.0.1
	 *
	 * @param string $group,
	 * @param null   $key
	 *
	 * @return void
	 */
	public function clearNotifications( $group = 'all', $key = null );

	/**
	 * Show all messages
	 *
	 * @since 0.0.1
	 */
	public function showNotifications();
}