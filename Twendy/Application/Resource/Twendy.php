<?php
/**
 * Twendy_Application_Resource_Twendy
 */
class Twendy_Application_Resource_Twendy extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * @var array
	 */
	protected $_defaultOptions = array(
		'viewSuffix' => 'tpl'
	);

	/**
	 * Initialise twig
	 * @see Zend_Application_Resource_Resource::init()
	 */
	public function init()
	{
		// Get Twendy options
		$options = array_merge($this->_defaultOptions, $this->getOptions());

		// Initialise view object
		$view = new Twendy_View();

		// Initialise Twig engine
		$loader = new Twig_Loader_Filesystem(array());
		$twig = new Twendy_Environment($view, $loader, $options);

		// Add zend token parser
		$twig->addTokenParser(new Twendy_Extension_Zend_TokenParser());

		// Attach twig to Zend_View
		$view->setEngine($twig);

		// Assign to ViewRenderer
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view)
		             ->setViewSuffix($options['viewSuffix']);

		return $view;
	}
}
