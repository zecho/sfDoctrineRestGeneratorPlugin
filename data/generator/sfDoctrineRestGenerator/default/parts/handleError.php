
  public function handleError(Exception $e)
  {
      $this->getResponse()->setStatusCode(406);
      try
      {
         $serializer = $this->getSerializer();
      }
      catch(Exception $e)
      {
         $serializer = $this->serializer;
      }

      $this->getResponse()->setContentType($serializer->getContentType());
      $error = $e->getMessage();

      // event filter to enable customisation of the error message.
      $result = $this->dispatcher->filter(
        new sfEvent($this, 'sfDoctrineRestGenerator.filter_error_output'),
        $error
      )->getReturnValue();

      if ($error === $result)
      {
        $error = array(array('code' => 406, 'description' => $error));
        $this->output = $serializer->serialize($error, 'error');
      }
      else
      {
        if(is_array($result) && isset($result['error']) && !isset($result['error']['code']))
        {
            $result['error']['code'] = 406;
        }
        $this->output = $serializer->serialize($result);
      }

      $this->setTemplate('index');
      return sfView::SUCCESS;
  }
