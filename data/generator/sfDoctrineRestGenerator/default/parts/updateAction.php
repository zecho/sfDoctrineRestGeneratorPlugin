  /**
   * Updates a <?php echo $this->getModelClass() ?> object
   * @param   sfWebRequest   $request a request object
   * @return  string
   */
  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::PUT));
    $content = $request->getContent();

    // Restores backward compatibility. Content can be the HTTP request full body, or a form encoded "content" var.
    if (strpos($content, 'content=') === 0)
    {
      $content = $request->getParameter('content');
    }

    $request->setRequestFormat('html');

    try
    {
       $content = $this->validateUpdate($content);
    }
    catch (Exception $e)
    {
      return $this->handleError($e);
    }

    // retrieve the object
<?php $primaryKey = Doctrine_Core::getTable($this->getModelClass())->getIdentifier() ?>
    $primaryKey = $request->getParameter('<?php echo $primaryKey ?>');
    $this->object = Doctrine_Core::getTable($this->model)->findOneBy<?php echo sfInflector::camelize($primaryKey) ?>($primaryKey);
    $this->forward404Unless($this->object);

    // update and save it
    $this->updateObjectFromRequest($content);
    return $this->doSave();
  }
