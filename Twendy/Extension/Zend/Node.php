<?php
/**
 * Twendy_Extension_Zend_Node
 */
class Twendy_Extension_Zend_Node extends Twig_Node
{
	/**
	 * Compile Zend view helper method calls
	 * @see Twig_Node::compile()
	 */
	public function compile(Twig_Compiler $compiler)
	{
		$compiler->addDebugInfo($this)
		         ->write('$this->env->getView()->getHelper(\'' . $this->getAttribute('name') . '\')');

		foreach($this->getAttribute('methods') as $method) {
			$compiler->write('->' . $method['method'] . '(');

			if($method['arguments'] !== null) {
				foreach($method['arguments'] as $argument) {
					$compiler->subcompile($argument);
				}
			}

			$compiler->write(')');
		}

		$compiler->raw(";\n");
	}
}