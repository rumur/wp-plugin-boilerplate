<?php
namespace WPApp\Service\View;

class View implements IView {
	/**
	 * @var string
	 */
	private $view_path;
	/**
	 * @var string
	 */
	private $content;

	/**
	 * View constructor.
	 *
	 * @param $view_path
	 *
	 * @throws ViewException
	 */
	public function __construct( $view_path )
	{
		if ( ! is_dir( $view_path ) ) {
			throw new ViewException( "Dir \"{$view_path}\" was not found" );
		}

		$this->view_path = $view_path;
	}

	/**
	 * @inheritdoc
	 * @param $name
	 * @param array $args
	 */
	public function get( $name, $args = [] )
	{
		$filename = $this->nameToPath( $name );

		if ( $this->ensure( $filename ) ) {
			extract( $args );
			ob_start();
			include_once $filename;

			$this->setContent( ob_get_clean() );
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function render()
	{
		echo $this->getContent();
	}

	/**
	 * Checks whether the file exists or not.
	 *
	 * @param $filename
	 *
	 * @return bool
	 * @throws ViewException
	 */
	protected function ensure($filename)
	{
		if ( ! file_exists( $filename ) ) {
			throw new ViewException( "The {$filename} is not exists." );
		}

		return true;
	}

	/**
	 * Makes the path from the string divided by ".";
	 * Returns the path to the file.
	 *
	 * @param $name
	 *
	 * @return string
	 */
	protected function nameToPath( $name )
	{
		$raw_data = ( explode( '.', $name ) );

		$file = array_pop( $raw_data ) . '.php';
		$path = trailingslashit( join( DS, $raw_data ) );

		return $this->view_path . DS . $path . $file;
	}

	/**
	 * @param $content
	 */
	protected function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	protected function getContent()
	{
		return $this->content;
	}

}
