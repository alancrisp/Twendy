<?php
/**
 * Custom extensions to Twig
 */
class Twendy_Extension extends Twig_Extension
{
	/**
	 * Get extension name
	 * @see Twig_ExtensionInterface::getName()
	 */
	public function getName()
	{
		return 'Twendy';
	}

	/**
	 * Get filters
	 * @see Twig_Extension::getFilters()
	 */
	public function getFilters()
	{
		return array(
			'var_export' => new Twig_Filter_Function('var_export')
		);
	}
}