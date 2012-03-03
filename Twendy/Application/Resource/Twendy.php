<?php
/**
 * Twendy_Application_Resource_Twendy
 */
class Twendy_Application_Resource_Twendy extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * Initialise twig
	 * @see Zend_Application_Resource_Resource::init()
	 */
	public function init()
	{
		// Get Twendy options
		$options = $this->getOptions();

		// Initialise view object
		$view = new Twendy_View();

		// Initialise Twig engine
		$loader = new Twig_Loader_Filesystem(array());
		$twig = new Twendy_Environment($view, $loader, $this->getOptions());

		// Add zend token parser
		$twig->addTokenParser(new Twendy_Extension_Zend_TokenParser());

		// Attach twig to Zend_View
		$view->setEngine($twig);

		// Assign to ViewRenderer
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);

		if(!array_key_exists('viewSuffix', $options)) {
			$options['viewSuffix'] = 'tpl';
		}

		$viewRenderer->setViewSuffix($options['viewSuffix']);

		return $view;
	}
}
