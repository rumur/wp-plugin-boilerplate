<?php
namespace WPApp\Admin\Service\Options;

class Options implements IOptions {
	/**
	 * @var array
	 */
	private $options = [];

	/**
	 * @var string
	 */
	priate $option_name;

	/**
	 * Options constructor.
	 *
	 * @param array $options
	 */
	public function __construct( $option_name, array $options = [] )
	{
		$this->option_name = $option_name;
		$this->options = $options;
	}

	/**
	 * Load the Options instance from WP.
	 *
	 * @param string $option_name
	 *
	 * @return Options
	 */
	public static function load( $option_name )
	{
		$options = get_option( $option_name, [] );

		return new self( $option_name, $options );
	}

	/**
	 * @inheritdoc
	 * @param string $name
	 * @param null $default
	 *
	 * @return mixed|null
	 */
	public function get( $name, $default = null )
	{
		return $this->has( $name ) ? $this->options[ $name ] : $default;
	}

	/**
	 * @inheritdoc
	 * @param $name
	 *
	 * @return bool
	 */
	public function has( $name )
	{
		return isset( $this->options[ $name ] );
	}

	/**
	 * @inheritdoc
	 * @param $name
	 * @param $value
	 */
	public function set( $name, $value )
	{
		$this->options[ $name ] = $value;
	}
}
