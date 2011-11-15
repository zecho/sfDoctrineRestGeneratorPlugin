  /**
   * Retrieves a <?php echo $this->getModelClass() ?> object
   * @param   sfWebRequest   $request a request object
   * @return  string
   */
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::GET));
    $params = $request->getParameterHolder()->getAll();

    // notify an event before the action's body starts
    $this->dispatcher->notify(new sfEvent($this, 'sfDoctrineRestGenerator.get.pre', array('params' => $params)));

    $request->setRequestFormat('html');
    $this->setTemplate('index');
    $params = $this->cleanupParameters($params);

    try
    {
      $format = $this->getFormat();
      $this->validateShow($params);
    }
    catch (Exception $e)
    {
      return $this->handleError($e);
    }

    $this->queryFetchOne($params);
    $this->forward404Unless(is_array($this->objects[0]));

<?php foreach ((array)$this->configuration->getValue('show.object_additional_fields') as $field): ?>
    $this->embedAdditional<?php echo $field ?>(0, $params);
<?php endforeach; ?>
<?php foreach ((array)$this->configuration->getValue('show.global_additional_fields') as $field): ?>
    $this->embedGlobalAdditional<?php echo $field ?>($params);
<?php endforeach; ?>

    $this->configureFields();

    $serializer = $this->getSerializer();
    $this->getResponse()->setContentType($serializer->getContentType());
    $this->output = $serializer->serialize($this->objects[0], $this->model, false);
    unset($this->objects);
  }
