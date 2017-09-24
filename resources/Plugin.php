<?php
namespace WPApp;

use WPApp\Admin\Admin;

use WPApp\Facades\Facade;

use WPApp\Core\Container;

use WPApp\Facades\NoticeAdmin;

use WPApp\Service\Compat;
use WPApp\Service\ViewService;
use WPApp\Service\NoticeService;

/**
 * Class Plugin
 * @package Rumur
 */
class Plugin {
	/**
	 * @since 0.0.1
	 *
	 * @var string
	 */
	const VERSION = '0.1.0';
	/**
	 * @since 0.0.1
	 *
	 * @var string
	 */
	const NAME = 'WP Plugin Boilerplate';
	/**
	 * @since 0.0.1
	 *
	 * @var string
	 */
	const WP_VERSION_MIN = '4.0';
	/**
	 * @since 0.0.1
	 *
	 * @var string
	 */
	const PHP_VERSION_MIN = '5.5.9';
	/**
	 * @since 0.0.1
	 */
	const TEXT_DOMAIN = 'rumur';

	/**
	 * The DI Container.
	 *
	 * @since 0.0.1
	 *
	 * @var Container.
	 */
	private $container;
	/**
	 * @since 0.0.1
	 *
	 * @var string
	 */
	private $path;
	/**
	 * @since 0.0.1
	 *
	 * @var string
	 */
	private $dir_path;
	/**
	 * Whether the plugin is compatible or not.
	 *
	 * @since 0.0.1
	 *
	 * @var bool
	 */
	private $is_compatible = true;

	/**
	 * Plugin constructor.
	 *
	 * @param $file
	 */
	public function __construct( $file )
	{
		$this->setContainer( new Container() );
		$this->setPath( $file );
		$this->setDirPath();
	}

	/**
	 * Starting the Plugin.
	 *
	 * @since 0.0.1
	 *
	 * @param string $file ( __FILE__ )
	 *
	 * @return mixed|bool|Plugin
	 */
	public static function run( $file )
	{
		$self = new self( $file );

		/**
		 *  Map Service Providers.
		 *
		 * @since 0.0.1
		 */
		$providers = apply_filters( Plugin::NAME . '_service_providers', [
			ViewService::class,
			NoticeService::class,
		]);

		/**
		 * Put the link to its self.
		 */
		$self->container['plugin'] = $self;

		/**
		 * Instantiate Service Providers.
		 *
		 * @since 0.0.1
		 */
		array_walk( $providers, function ( $file ) use ( $self ) {
			$self->container->register( new $file );
		} );
		/**
		 * Setup the facade.
		 *
		 * @since 0.0.1
		 */
		Facade::setFacadeApplication( $self->container );
		/**
		 * Check for minimum PHP version.
		 *
		 * @since 0.0.1
		 */
		if (!Compat::checkPHP(Plugin::PHP_VERSION_MIN)) {
			// Add warning notice
			NoticeAdmin::addWarning(
				sprintf(
					__('Minimal PHP version is required for %1$s plugin: <b>%2$s</b>.', Plugin::TEXT_DOMAIN),
					Plugin::NAME, Plugin::PHP_VERSION_MIN
				)
			);
			$self->is_compatible = false;
		}
		/**
		 * Check for minimum WordPress version.
		 *
		 * @since 0.0.1
		 */
		if (!Compat::checkWordPress(Plugin::WP_VERSION_MIN)) {
			// Add warning notice
			NoticeAdmin::addWarning(
				sprintf(
					__('Minimal WP version is required for %1$s plugin: <b>%2$s</b>.', Plugin::TEXT_DOMAIN),
					Plugin::NAME, Plugin::WP_VERSION_MIN
				)
			);
			$self->is_compatible = false;
		}
		/**
		 * If there is no ignition with this env just do nothing.
		 *
		 * @since 0.0.1
		 */
		if ( ! $self->is_compatible ) {
			return false;
		}
		/**
		 * Activation.
		 *
		 * @since 0.0.1
		 */
		register_activation_hook( $self->getPath(), [ 'WPApp\Activation', 'run' ] );
		/**
		 * Uninstall.
		 *
		 * @since 0.0.1
		 */
		register_uninstall_hook( $self->getPath(), [ 'WPApp\Uninstall', 'run' ] );
		/**
		 * Run admin hooks only.
		 *
		 * @since 0.0.1
		 */
		if ( is_admin() ) {
			Admin::run();
		}

		return $self;
	}

	/**
	 * @since 0.0.1
	 *
	 * @return Container
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * @since 0.0.1
	 *
	 * @param Container $container
	 */
	public function setContainer( Container $container )
	{
		$this->container = $container;
	}

	/**
	 * @since 0.0.1
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @since 0.0.1
	 *
	 * @param string $path
	 */
	public function setPath( $path )
	{
		$this->path = $path;
	}

	/**
	 * Dir Path e.g. .../wp-content/plugins/plugin.
	 *
	 * @since 0.0.1
	 *
	 * @return string
	 */
	public function getDirPath()
	{
		return $this->dir_path;
	}

	/**
	 * @since 0.0.1
	 */
	public function setDirPath()
	{
		$this->dir_path = plugin_dir_path( $this->getPath() );
	}
}
