<?php
/**
 * Twendy_Environment
 * Interfaces between Zend_View and Twig_Environment
 */
class Twendy_Environment extends Twig_Environment
{
	/**
	 * @var Twendy_View
	 */
	protected $_view;

	/**
	 * @var array
	 */
	protected $_extensions = array();

	/**
	 * Constructor
	 * @param Twendy_View_Interface $view
	 * @param Twig_LoaderInterface $loader
	 * @param array $options
	 */
	public function __construct(Twendy_View_Interface $view, Twig_LoaderInterface $loader = null, $options = array())
	{
		parent::__construct($loader, $options);

		$this->_view = $view;
		$this->setOptions($options);
	}

	/**
	 * Set options
	 * @param array $options
	 * @return Twendy_Environment
	 */
	public function setOptions($options)
	{
		if(array_key_exists('extensions', $options)) {
			$this->registerExtensions($options['extensions']);
		}

		if(array_key_exists('layoutPath', $options)) {
			$this->_view->setLayoutPath($options['layoutPath']);
		}

		if(array_key_exists('layout', $options)) {
			$this->_view->setLayout($options['layout']);
		}

		if(array_key_exists('helperPath', $options)) {
			$this->registerHelperPaths($options['helperPath']);
		}

		return $this;
	}

	/**
	 * Register extensions
	 * @param array $extensions
	 * @return Twendy_Environment
	 */
	public function registerExtensions($extensions)
	{
		if(!is_array($extensions)) {
			$extensions = (array) $extensions;
		}

		foreach($extensions as $class) {
			if(class_exists($class)) {
				$this->addExtension(new $class);
			}
		}

		return $this;
	}

	/**
	 * Register view helper paths
	 * @param array $helperPaths
	 * @return Twendy_Environment
	 */
	public function registerHelperPaths($helperPaths)
	{
		if(!is_array($helperPaths)) {
			$helperPaths = array('Zend_View_Helper_' => $helperPaths);
		}

		foreach($helperPaths as $classPrefix => $paths) {
			$this->_view->addHelperPath($paths, $classPrefix);
		}

		return $this;
	}

	/**
	 * Get Zend_View instance
	 * @return Zend_View_Interface
	 */
	public function getView()
	{
		return $this->_view;
	}
}
