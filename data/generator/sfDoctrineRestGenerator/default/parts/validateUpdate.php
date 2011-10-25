  /**
   * Applies the update validators to the payload posted to the service
   *
   * @param   string   $payload  A payload string
   */
  public function validateUpdate($payload)
  {
    $params = $this->parsePayload($payload);

    $this->unset_var_array($params, $this->getFilterPayloadUpdate());

    $validators = $this->getUpdateValidators();
    $this->validate($params, $validators);

    $postvalidators = $this->getUpdatePostValidators();
    $this->postValidate($params, $postvalidators);
    return $params;
  }
