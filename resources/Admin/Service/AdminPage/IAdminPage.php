<?php
namespace WPApp\Admin\Service\AdminPage;

interface IAdminPage {
	/**
	 * Adds the admin page to the admin menu.
	 */
	public function addAdminPage();

	/**
	 * Configure the option page using the setting API.
	 */
	public function configure();

	/**
	 * Renders the admin page using the Settings API.
	 */
	public function render();
}