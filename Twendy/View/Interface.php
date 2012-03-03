<?php
/**
 * Twendy_View_Interface
 */
interface Twendy_View_Interface extends Zend_View_Interface
{
	/**
	 * Set view engine
	 * @param Twig_Environment $twig
	 * @return Twendy_View_Interface
	 */
	public function setEngine(Twig_Environment $twig);

	/**
	 * Set layout path
	 * @param string $layoutPath
	 * @return Twendy_View_Interface
	 */
	public function setLayoutPath($layoutPath);

	/**
	 * Set layout name
	 * @param string $layout
	 * @return Twendy_View_Interface
	 */
	public function setLayout($layout);

	/**
	 * Get layout name
	 * @return string
	 */
	public function getLayout();

	/**
	 * Get layout script
	 * @return string
	 */
	public function getLayoutScript();
}