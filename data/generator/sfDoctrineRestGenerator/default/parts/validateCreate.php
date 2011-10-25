  /**
   * Applies the creation validators to the payload posted to the service
   *
   * @param   string   $payload  A payload string
   */
  public function validateCreate($payload)
  {
    $params = $this->parsePayload($payload);

    $this->unset_var_array($params, $this->getFilterPayloadCreate());

    $validators = $this->getCreateValidators();
    $this->validate($params, $validators);

    $postvalidators = $this->getCreatePostValidators();
    $this->postValidate($params, $postvalidators);
    return $params;
  }
