<?php
/**
 * Twendy_Extension_Zend_TokenParser
 *
 * Adds 'zend' tag to Twig for calling view helper methods which do not output HTML
 *
 * Examples:
 *     Doctype:
 *         Zend:
 *             $this->doctype('HTML5')
 *         Twig:
 *             {% zend doctype('HTML5') %}
 *     HeadScript:
 *         Zend:
 *             $this->headScript->appendFIle('script.js')
 *         Twig:
 *             {% zend headScript.appendFile('script.js') %}
 *     Placeholder:
 *         Zend:
 *             $this->placeholder('foo')->set('bar')
 *         Twig:
 *             {% zend placeholder('foo').set('bar') %}
 */
class Twendy_Extension_Zend_TokenParser extends Twig_TokenParser
{
	/**
	 * @var array View helper method calls
	 */
	protected $_methods = array();

	/**
	 * Parse token
	 * @see Twig_TokenParserInterface::parse()
	 */
	public function parse(Twig_Token $token)
	{
		$this->_methods = array();

		$name = $this->parser->getStream()->expect(Twig_Token::NAME_TYPE)->getValue();

		if($this->parser->getStream()->test(Twig_Token::PUNCTUATION_TYPE, '(')) {
			$this->_addMethod($name, $this->_getArguments());
		}

		$this->_getMethods();

		$this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

		return new Twendy_Extension_Zend_Node(array(), array('name' => $name, 'methods' => $this->_methods), $token->getLine(), $this->getTag());
	}

	/**
	 * Get chained method calls
	 */
	protected function _getMethods()
	{
		if($this->parser->getStream()->test(Twig_Token::PUNCTUATION_TYPE, '.')) {
			$this->parser->getStream()->next();
			$method = $this->parser->getStream()->expect(Twig_Token::NAME_TYPE)->getValue();
			$this->_addMethod($method, $this->_getArguments());
			$this->_getMethods();
		}
	}

	/**
	 * Add view helper method call
	 * @param string $method
	 * @param array|null $arguments
	 */
	protected function _addMethod($method, $arguments = null)
	{
		$this->_methods[] = array(
			'method'    => $method,
			'arguments' => $arguments
		);
	}

	/**
	 * Get arguments to method call
	 * @return array|null
	 */
	protected function _getArguments()
	{
		if($this->parser->getStream()->test(Twig_Token::PUNCTUATION_TYPE)) {
			return $this->parser->getExpressionParser()->parseArguments();
		}

		return null;
	}

	/**
	 * Get tag
	 * @see Twig_TokenParserInterface::getTag()
	 */
	public function getTag()
	{
		return 'zend';
	}
}