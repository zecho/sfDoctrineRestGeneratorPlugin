  /**
   * Manages the visibility of fields in record collections and in relations.
   * This method will hide some fields, based on the configuration file
   *
   * @return  void
   */
  protected function setFieldVisibility($context = 'get')
  {
    if($context == 'show')
    {
        $this->setFieldVisibilityForShow();
    }
    else
    {
        $this->setFieldVisibilityForGet();
    }
  }
