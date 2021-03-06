[?php

/**
 * <?php echo $this->getModuleName() ?> module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: configuration.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class Base<?php echo ucfirst($this->getModuleName()) ?>GeneratorConfiguration extends sfDoctrineRestGeneratorConfiguration
{
  public function getAdditionalParams()
  {
    return <?php echo $this->asPhp(isset($this->config['get']['additional_params']) ? $this->config['get']['additional_params'] : array()) ?>;
<?php unset($this->config['get']['additional_params']) ?>
  }

  public function getDefaultFormat()
  {
    return <?php echo $this->asPhp(isset($this->config['get']['default_format']) ? $this->config['get']['default_format'] : 'json') ?>;
<?php unset($this->config['get']['default_format']) ?>
  }

  public function getDisplay($context = 'get')
  {
    if($context == 'show')
    {
        return <?php echo $this->asPhp(isset($this->config['show']['display']) ? $this->config['show']['display'] : array()) ?>;
<?php unset($this->config['show']['display']) ?>
    }
    else
    {
        return <?php echo $this->asPhp(isset($this->config['get']['display']) ? $this->config['get']['display'] : array()) ?>;
<?php unset($this->config['get']['display']) ?>
    }
  }

  public function getSelectEmbedRelationsFields($context = 'get')
  {
    if($context == 'show')
	{
		<?php
		$embed_relations = (isset($this->config['show']['embed_relations']) ? (array)$this->config['show']['embed_relations'] : array());
		$alias           = (isset($this->config['show']['embedded_relations_alias']) ? (array)$this->config['show']['embedded_relations_alias'] : array());
		$display = array();
		foreach($embed_relations as $relation)
		{
			$show = ((isset($this->config['show']['embedded_relations_fields']) && isset($this->config['show']['embedded_relations_fields'][$relation])) ? (array)$this->config['show']['embedded_relations_fields'][$relation] : array());
			$hide = ((isset($this->config['show']['embedded_relations_hide']) && isset($this->config['show']['embedded_relations_hide'][$relation])) ? (array)$this->config['show']['embedded_relations_hide'][$relation] : array());

			list($table, $model) = $this->getNestedTableAndRelationNamesFromRelationName($relation);
			if(!$table)
			{
				$all = array_keys(Doctrine_Core::getTable($this->getRealModelFromRelationName($model))->getColumns());
			}
			else
			{
				$all = array_keys(Doctrine_Core::getTable($table)->getRelation($this->getRealModelFromRelationName($model))->getTable()->getColumns());
			}

			$display[$relation] =  $this->getFilteredDisplayFields($all, $show,	$hide);

		}
		?>
		return <?php echo $this->asPhp($this->asFieldList($embed_relations, $display, $alias)) ?>;
	}
	else
	{
		<?php
		$embed_relations = (isset($this->config['get']['embed_relations']) ? (array)$this->config['get']['embed_relations'] : array());
		$alias           = (isset($this->config['get']['embedded_relations_alias']) ? (array)$this->config['get']['embedded_relations_alias'] : array());
		$display = array();
		foreach($embed_relations as $relation)
		{
			$show = ((isset($this->config['get']['embedded_relations_fields']) && isset($this->config['get']['embedded_relations_fields'][$relation])) ? (array)$this->config['get']['embedded_relations_fields'][$relation] : array());
			$hide = ((isset($this->config['get']['embedded_relations_hide']) && isset($this->config['get']['embedded_relations_hide'][$relation])) ? (array)$this->config['get']['embedded_relations_hide'][$relation] : array());

            list($table, $model) = $this->getNestedTableAndRelationNamesFromRelationName($relation);
			if(!$table)
			{
				$all = array_keys(Doctrine_Core::getTable($this->getRealModelFromRelationName($model))->getColumns());
			}
			else
			{
				$all = array_keys(Doctrine_Core::getTable($table)->getRelation($this->getRealModelFromRelationName($model))->getTable()->getColumns());
			}

			$display[$relation] =  $this->getFilteredDisplayFields($all, $show,	$hide);
		}
		?>
		return <?php echo $this->asPhp($this->asFieldList($embed_relations, $display, $alias)) ?>;
	}
  }

  public function getEmbedRelations($context = 'get')
  {
    if($context == 'show')
    {
        return <?php echo $this->asPhp(isset($this->config['show']['embed_relations']) ? $this->config['show']['embed_relations'] : array()) ?>;
<?php unset($this->config['show']['embed_relations']) ?>
    }
    else
    {
        return <?php echo $this->asPhp(isset($this->config['get']['embed_relations']) ? $this->config['get']['embed_relations'] : array()) ?>;
<?php unset($this->config['get']['embed_relations']) ?>
    }
  }

  public function getEmbeddedRelationsFields($context = 'get')
  {
    if($context == 'show')
    {
        $embedded_relations_fields = <?php echo $this->asPhp(isset($this->config['show']['embedded_relations_fields']) ? $this->config['show']['embedded_relations_fields'] : array()) ?>;
<?php unset($this->config['show']['embedded_relations_fields']) ?>
    }
    else
    {
        $embedded_relations_fields = <?php echo $this->asPhp(isset($this->config['get']['embedded_relations_fields']) ? $this->config['get']['embedded_relations_fields'] : array()) ?>;
<?php unset($this->config['get']['embedded_relations_fields']) ?>
    }

    foreach ($embedded_relations_fields as $relation_name => $display_fields)
    {
      $embedded_relations_fields[$relation_name] = array_flip($display_fields);
    }

    return $embedded_relations_fields;

  }

  public function getEmbeddedRelationsAlias($context = 'get')
  {
    if($context == 'show')
    {
        $embedded_relations_alias = <?php echo $this->asPhp(isset($this->config['show']['embedded_relations_alias']) ? $this->config['show']['embedded_relations_alias'] : array()) ?>;
<?php unset($this->config['show']['embedded_relations_alias']) ?>
    }
    else
    {
        $embedded_relations_alias = <?php echo $this->asPhp(isset($this->config['get']['embedded_relations_alias']) ? $this->config['get']['embedded_relations_alias'] : array()) ?>;
<?php unset($this->config['get']['embedded_relations_alias']) ?>
    }

    return $embedded_relations_alias;
  }

  public function getEmbeddedRelationsHide($context = 'get')
  {
    if($context == 'show')
    {
        $embedded_relations_hide = <?php echo $this->asPhp(isset($this->config['show']['embedded_relations_hide']) ? $this->config['show']['embedded_relations_hide'] : array()) ?>;
<?php unset($this->config['show']['embedded_relations_hide']) ?>
    }
    else
    {
        $embedded_relations_hide = <?php echo $this->asPhp(isset($this->config['get']['embedded_relations_hide']) ? $this->config['get']['embedded_relations_hide'] : array()) ?>;
<?php unset($this->config['get']['embedded_relations_hide']) ?>
    }

    foreach ($embedded_relations_hide as $relation_name => $hidden_fields)
    {
      $embedded_relations_hide[$relation_name] = array_flip($hidden_fields);
    }

    return $embedded_relations_hide;
  }

  public function getFormatsEnabled()
  {
    return <?php echo $this->asPhp(isset($this->config['default']['formats_enabled']) ? $this->config['default']['formats_enabled'] : array('json', 'xml', 'yaml')) ?>;
<?php unset($this->config['default']['formats_enabled']) ?>
  }

  public function getFormatsStrict()
  {
    return <?php echo $this->asPhp(isset($this->config['default']['formats_strict']) ? $this->config['default']['formats_strict'] : true) ?>;
<?php unset($this->config['default']['formats_strict']) ?>
  }

<?php include dirname(__FILE__).'/fieldsConfiguration.php' ?>

  public function getGlobalAdditionalFields()
  {
    return <?php echo $this->asPhp(isset($this->config['get']['global_additional_fields']) ? $this->config['get']['global_additional_fields'] : array()) ?>;
<?php unset($this->config['get']['global_additional_fields']) ?>
  }

  public function getHide($context = 'get')
  {
    if($context == 'show')
    {
        return <?php echo $this->asPhp(isset($this->config['show']['hide']) ? $this->config['show']['hide'] : array()) ?>;
<?php unset($this->config['show']['hide']) ?>
    }
    else
    {
        return <?php echo $this->asPhp(isset($this->config['get']['hide']) ? $this->config['get']['hide'] : array()) ?>;
<?php unset($this->config['get']['hide']) ?>
    }
  }

  public function getMaxItems()
  {
    return <?php echo $this->asPhp(isset($this->config['get']['max_items']) ? $this->config['get']['max_items'] : 0) ?>;
<?php unset($this->config['get']['max_items']) ?>
  }

  public function getObjectAdditionalFields($context = 'get')
  {
    if($context == 'show')
    {
        return <?php echo $this->asPhp(isset($this->config['show']['object_additional_fields']) ? $this->config['show']['object_additional_fields'] : array()) ?>;
<?php unset($this->config['show']['object_additional_fields']) ?>
    }
    else
    {
        return <?php echo $this->asPhp(isset($this->config['get']['object_additional_fields']) ? $this->config['get']['object_additional_fields'] : array()) ?>;
<?php unset($this->config['get']['object_additional_fields']) ?>
    }
  }

  public function getSeparator()
  {
    return <?php echo $this->asPhp(isset($this->config['default']['separator']) ? $this->config['default']['separator'] : ',') ?>;
<?php unset($this->config['default']['separator']) ?>
  }

<?php include dirname(__FILE__).'/paginationConfiguration.php' ?>

<?php include dirname(__FILE__).'/sortConfiguration.php' ?>
}
