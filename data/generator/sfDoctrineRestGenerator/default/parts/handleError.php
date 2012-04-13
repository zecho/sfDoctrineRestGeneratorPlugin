
  public function handleError(Exception $e)
  {
      $response = $this->getResponse();
      $http_status_code = ($e instanceof sfDoctrineRestValidatorError) ? 400 : 406;

      $response->setStatusCode($http_status_code);
      try
      {
         $serializer = $this->getSerializer();
      }
      catch(Exception $e)
      {
         $serializer = $this->serializer;
      }

      $this->getResponse()->setContentType($serializer->getContentType());
      $error     = $e->getMessage();
      $parameter = ($e instanceof sfDoctrineRestValidatorError) ? $e->getParameter() : null;

      // event filter to enable customisation of the error message.
      $result = $this->dispatcher->filter(
        new sfEvent($this, 'sfDoctrineRestGenerator.filter_error_output'),
        $error
      )->getReturnValue();

      if ($error === $result)
      {
        $error = array(array(
            'code' => $response->getStatusCode(),
            'description' => $this->getContext()->getI18N()->__($error),
            'parameter'   => $parameter,
        ));
        $this->output = $serializer->serialize($error, 'error');
      }
      else
      {
        if(is_array($result) && isset($result['error']) && !isset($result['error']['code']))
        {
            $result['error']['code'] = $response->getStatusCode();
            $result['error']['parameter'] = $parameter;
            $result['error']['description'] = $this->getContext()->getI18N()->__($result['error']['description']);
        }
        $this->output = $serializer->serialize($result);
      }

      $this->setTemplate('index');
      return sfView::SUCCESS;
  }
