<?php
namespace WPApp\Admin\Service\Options;

interface IOptions {
	/**
	 * Gets the option for the given name.
	 * Returns default if there is no value.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get($name, $default = null);

	/**
	 * Checks if there is an option.
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function has($name);

	/**
	 * Sets an option.
	 * Overwrites the existing option if the name is already in use.
	 *
	 * @param $name
	 * @param $value
	 *
	 * @return mixed
	 */
	public function set($name, $value);
}