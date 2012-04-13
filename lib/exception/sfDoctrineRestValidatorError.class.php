<?php

/**
 * sfDoctrineRestValidatorError
 *
 * @package
 * @version $id$
 * @author Sébastien HOUZÉ <sebastien.houze@gmail.com>
 */
class sfDoctrineRestValidatorError extends Exception
{
	protected $parameter = null;

	public function getParameter()
	{
		return $this->parameter;
	}

	public function setParameter($parameter)
	{
		$this->parameter = $parameter;
	}
}
