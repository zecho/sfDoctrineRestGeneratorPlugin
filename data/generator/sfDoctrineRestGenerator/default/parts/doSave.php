  protected function doSave()
  {
    if($this->object->isNew())
    {
      // From Create action
      $this->object->save();

      <?php $primaryKeys = $this->getPrimaryKeys() ?>
      $params = array(
      <?php foreach($primaryKeys as $primaryKey): ?>
          '<?php echo $primaryKey ?>'  => $this->object-><?php echo $primaryKey ?>,
      <?php endforeach; ?>
          'sf_format' => $this->getRequest()->getParameter('sf_format'),
      );


      //$url = $this->generateUrl('customer_show', $params);
      //$this->redirect($url, 201);

      $this->getRequest()->getParameterHolder()->clear();
      $this->getRequest()->getParameterHolder()->add($params);
      $this->getRequest()->setMethod(sfWebRequest::GET);

      $this->getResponse()->setStatusCode(201);
      $this->forward('<?php echo strtolower($this->getModelClass()) ?>', 'show');
    }
    else
    {
      // From Update action
      $this->object->save();
      return sfView::NONE;
    }
  }
