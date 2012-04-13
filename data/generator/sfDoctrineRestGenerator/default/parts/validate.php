  /**
   * Applies a set of validators to an array of parameters
   *
   * @param array   $params      An array of parameters
   * @param array   $validators  An array of validators
   * @throw sfException
   */
  public function validate($params, $validators, $prefix = '')
  {
    $unused = array_keys($validators);

    foreach ($params as $name => $value)
    {
      if (!isset($validators[$name]))
      {
        throw new sfException($this->getContext()->getI18N()->__('Could not validate extra field "%field%"', array('%field%' => $prefix.$name)));
      }
      else
      {
        if (is_array($validators[$name]))
        {
          // validator for a related object
          $this->validate($value, $validators[$name], $prefix.$name.'.');
        }
        else
        {
            try {
                $validators[$name]->clean($value);
            }
            catch(sfValidatorError $e)
            {
				$error = new sfDoctrineRestValidatorError($e->getMessage(), 0, $e);
				$error->setParameter($name);
				throw $error;
            }
        }

        unset($unused[array_search($name, $unused, true)]);
      }
    }

    // are non given values required?
    foreach ($unused as $name)
        if (!is_array($validators[$name]))
          $validators[$name]->clean(null);
  }
