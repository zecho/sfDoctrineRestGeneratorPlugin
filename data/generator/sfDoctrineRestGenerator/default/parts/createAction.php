  /**
   * Creates a <?php echo $this->getModelClass() ?> object
   * @param   sfWebRequest   $request a request object
   * @return  string
   */
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $content = $request->getContent();

    // Restores backward compatibility. Content can be the HTTP request full body, or a form encoded "content" var.
    if (strpos($content, 'content=') === 0)
    {
      $content = $request->getParameter('content');
    }

    $request->setRequestFormat('html');

    try
    {
       $content = $this->validateCreate($content);
    }
    catch (Exception $e)
    {
      return $this->handleError($e);
    }

    $this->object = $this->createObject();
    $this->createObjectFromRequest($content);
    return $this->doSave();
  }
