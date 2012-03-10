<?php
/**
 * Extends Zend_View to use Twendy
 */
class Twendy_View extends Zend_View implements Twendy_View_Interface
{
	/**
	 * @var Twig_Environment
	 */
	protected $_twig;

	/**
	 * @var string
	 */
	protected $_layout;

	/**
	 * @var string
	 */
	protected $_layoutPath;

	/**
	 * Set view engine
	 * @param Twig_Environment $twig
	 */
	public function setEngine(Twig_Environment $twig)
	{
		$this->_twig = $twig;
		$this->_twig->addGlobal('zend', $this);

		return $this;
	}

	/**
	 * Set layout path
	 * @param string $layoutPath
	 */
	public function setLayoutPath($layoutPath)
	{
		$this->_layoutPath = (string) $layoutPath;

		return $this;
	}

	/**
	 * Set layout name
	 * @param string $layout
	 */
	public function setLayout($layout)
	{
		$this->_layout = (string) $layout;

		return $this;
	}

	/**
	 * Get layout name
	 * @return string
	 */
	public function getLayout()
	{
		return $this->_layout;
	}

	/**
	 * Get layout script
	 * @return string
	 */
	public function getLayoutScript()
	{
		if(null === $this->_layout) {
			$e = new Twendy_Exception('No layout set');
			$e->setView($this);
			throw new $e;
		}

		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$layoutScript = $this->_layout . '.' . $viewRenderer->getViewSuffix();

		return $layoutScript;
	}

	/**
	 * Get script paths
	 * @see Zend_View_Abstract::getScriptPaths()
	 */
	public function getScriptPaths()
	{
		$arrLayoutPaths = array();

		if(null !== $this->_layoutPath) {
			$arrLayoutPaths[] = $this->_layoutPath;
		}

		return array_merge(parent::getScriptPaths(), $arrLayoutPaths);
	}

	/**
	 * Render template
	 * @see Zend_View_Abstract::render()
	 */
	public function render($name)
	{
		$this->_twig->getLoader()->setPaths($this->getScriptPaths());
		$this->_twig->loadTemplate($name)->display(get_object_vars($this));
	}
}
