<?php
namespace WPApp\Service\View;

interface IView {
	/**
	 * Include the view by name and share the args among the view.
	 *
	 * @param $name
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function get($name, $args = []);

	/**
	 * Echoing the view.
	 *
	 * @return mixed
	 */
	public function render();
}