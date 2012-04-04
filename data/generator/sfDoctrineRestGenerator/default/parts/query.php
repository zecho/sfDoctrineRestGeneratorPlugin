
  protected function selectForQuery(Doctrine_Query $q)
  {
    switch($this->getActionName())
    {
        case "show":
<?php
$fields = $this->getFilteredDisplayFields(
    (array)array_keys($this->getDefaultFieldsConfiguration()),
    $this->configuration->getValue('show.display'),
    (array)$this->configuration->getValue('show.hide')
);
?>
<?php if(count($fields) > 0): ?>
            $q->select('<?php echo implode(', ', $fields); ?>');
<?php else: ?>
            $q->select($this->model.'.*');
<?php endif; ?>
            break;
        default:
<?php
$fields = $this->getFilteredDisplayFields(
    (array)array_keys($this->getDefaultFieldsConfiguration()),
    $this->configuration->getValue('get.display'),
    (array)$this->configuration->getValue('get.hide')
);
?>
<?php if(count($fields) > 0): ?>
            $q->select('<?php echo implode(', ', $fields); ?>');
<?php else: ?>
            $q->select($this->model.'.*');
<?php endif; ?>
            break;
    }

    return $q;
  }

  protected function selectRelationsForQuery(Doctrine_Query $q)
  {
    switch($this->getActionName())
    {
        case "show":
<?php $fields = $this->configuration->getSelectEmbedRelationsFields('show') ?>
<?php if(count($fields) > 0): ?>
            $q->addSelect('<?php echo implode(', ', $fields) ?>');
<?php endif; ?>
            break;
        default:
<?php $fields = $this->configuration->getSelectEmbedRelationsFields('get') ?>
<?php if(count($fields) > 0): ?>
            $q->addSelect('<?php echo implode(', ', $fields) ?>');
<?php endif; ?>
            break;
    }

    return $q;
  }

  protected function joinRelationsForQuery(Doctrine_Query $q)
  {
    switch($this->getActionName())
    {
        case "show":
<?php $embed_relations = $this->configuration->getvalue('show.embed_relations'); ?>
<?php $alias_relations = $this->configuration->getEmbeddedRelationsAlias('show'); ?>
<?php foreach ($embed_relations as $embed_relation): ?>
<?php if(!$this->isManyToManyRelation($embed_relation)): ?>
<?php if(false !== strpos($embed_relation, '.')):
    list($model, $relation) = $this->getNestedTableAndRelationNamesFromRelationName($embed_relation);
    $alias = $relation;
    if(isset($alias_relations[$embed_relation]))
    {
        $alias = $alias_relations[$embed_relation];
    }
?>
            $q->leftJoin('<?php echo $model ?>.<?php echo $relation ?> <?php echo $alias ?>');
<?php else: ?>
            $q->leftJoin($this->model.'.<?php echo $embed_relation ?> <?php echo $embed_relation ?>');
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
            break;
        default:
<?php $embed_relations = $this->configuration->getvalue('get.embed_relations'); ?>
<?php $alias_relations = $this->configuration->getEmbeddedRelationsAlias('get'); ?>
<?php foreach ($embed_relations as $embed_relation): ?>
<?php if(!$this->isManyToManyRelation($embed_relation)): ?>
<?php if(false !== strpos($embed_relation, '.')):
    list($model, $relation) = $this->getNestedTableAndRelationNamesFromRelationName($embed_relation);
    $alias = $relation;
    if(isset($alias_relations[$embed_relation]))
    {
        $alias = $alias_relations[$embed_relation];
    }
?>
            $q->leftJoin('<?php echo $model ?>.<?php echo $relation ?> <?php echo $alias ?>');
<?php else: ?>
            $q->leftJoin($this->model.'.<?php echo $embed_relation ?> <?php echo $embed_relation ?>');
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
            break;
    }
  }

 /**
   * Create the query for selecting objects, eventually along with related
   * objects
   *
   * @param   array   $params  an array of criterions for the selection
   */
  public function query($params)
  {
    $q = Doctrine_Query::create();
    $this->selectForQuery($q);
    $this->selectRelationsForQuery($q)
         ->from($this->model.' '.$this->model);
    $this->joinRelationsForQuery($q);

<?php $max_items = $this->configuration->getValue('get.max_items') ?>
<?php if ($max_items > 0): ?>
    $limit = <?php echo $max_items; ?>;

<?php endif; ?>
<?php
$pagination_custom_page_size = $this->configuration->getValue('get.pagination_custom_page_size');
$pagination_enabled = $this->configuration->getValue('get.pagination_enabled');
$pagination_page_size = $this->configuration->getValue('get.pagination_page_size'); ?>
<?php if ($pagination_enabled): ?>
    if (!isset($params['page']))
    {
      $params['page'] = 1;
    }

    $page_size = <?php echo $pagination_page_size; ?>;
<?php if ($pagination_custom_page_size): ?>

    if (isset($params['page_size']))
    {
      $page_size = $params['page_size'];
      unset($params['page_size']);
    }

<?php endif; ?>
<?php if ($max_items > 0): ?>
    $limit = min($page_size, $limit);
    $page_size = $limit;
<?php else: ?>
    $limit = $page_size;
<?php endif; ?>
    $q->offset(($params['page'] - 1) * $page_size);
    unset($params['page']);
<?php endif; ?>
<?php if ($max_items > 0 || $pagination_enabled): ?>
    $q->limit($limit);
<?php endif; ?>

<?php $sort_custom = $this->configuration->getValue('get.sort_custom'); ?>
<?php $sort_default = $this->configuration->getValue('get.sort_default'); ?>
<?php if ($sort_default && count($sort_default) == 2): ?>
    $sort = '<?php echo $sort_default[0] ?> <?php echo $sort_default[1] ?>';
<?php endif; ?>
<?php if ($sort_custom): ?>
    if (isset($params['sort_by']))
    {
      $sort = $params['sort_by'];
      unset($params['sort_by']);

      if (isset($params['sort_order']))
      {
        $sort .= ' '.$params['sort_order'];
        unset($params['sort_order']);
      }
    }
<?php endif; ?>

    if (isset($sort))
    {
      $q->orderBy($sort);
    }

<?php $primaryKeys = $this->getPrimaryKeys(); ?>
<?php foreach ($primaryKeys as $primaryKey): ?>
    if (isset($params['<?php echo $primaryKey ?>']))
    {
      $values = explode('<?php echo $this->configuration->getValue('default.separator', ',') ?>', $params['<?php echo $primaryKey ?>']);

      if (count($values) == 1)
      {
        $q->andWhere($this->model.'.<?php echo $primaryKey ?> = ?', $values[0]);
      }
      else
      {
        $q->whereIn($this->model.'.<?php echo $primaryKey ?>', $values);
      }

      unset($params['<?php echo $primaryKey ?>']);
    }

<?php endforeach; ?>
<?php $filters = $this->configuration->getFilters() ?>
<?php foreach ($filters as $name => $filter): ?>
<?php if (isset($filters[$name]['multiple']) && $filters[$name]['multiple']): ?>
    if (isset($params['<?php echo $name ?>']))
    {
      $values = explode('<?php echo $this->configuration->getValue('default.separator', ',') ?>', $params['<?php echo $name ?>']);

      if (count($values) == 1)
      {
        $q->andWhere($this->model.'.<?php echo $name ?> = ?', $values[0]);
      }
      else
      {
        $q->whereIn($this->model.'.<?php echo $name ?>', $values);
      }

      unset($params['<?php echo $name ?>']);
    }
<?php endif; ?>
<?php endforeach; ?>
    // DOS security
    if(count($params) < 100)
    {
        foreach ($params as $name => $value)
        {
          if($name != 'extra_params')
          {
              if(!is_array($value))
              {
                $q->andWhere($this->model.'.'.$name.' = ?', $value);
              }
              else
              {
                $table = Doctrine_Core::getTable($name);
                foreach($value as $model => $val)
                {
                    if($table->hasColumn($model))
                        $q->andWhere($name.'.'.$model.' = ?', $val);
                }
              }
          }
        }
    }

    return $q;
  }
