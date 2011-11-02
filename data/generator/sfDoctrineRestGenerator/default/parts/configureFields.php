  /**
   * Allows to change configure some fields of the response, based on the
   * generator.yml configuration file. Supported configuration directives are
   * "date_format" and "tag_name"
   *
   * @return  void
   */
protected function configureFields()
{
 <?php
      function configureObjectFields($fields, $configuration = array())
   {
      if (is_array($fields))
	 foreach ($fields as $field => $value)
	 {
	    $configuration = array_merge($configuration, array($field));
	    if (isset($value['date_format']) || isset($value['tag_name']))
	    {
	       $levelRelation = 1;
	       echo 'if (isset($object';

	       for ($i=0; $i < sizeof($configuration);)
	       {
		  for ($i2=0;$i2 < $levelRelation;$i2++)
		     echo "['".$configuration[$i++]."']";
		  if ($i < sizeof($configuration))
		  {
		     $levelRelation++;
		     $i = 0;
		     echo ') && isset($object';
		  }
                    else
                       echo "))\n{\n";
	       }
               if (isset($value['date_format']))
                  echo ' $object'."['".join("']['", $configuration)."'] = date("."'".$value['date_format']."'".', strtotime($object'."['".join("']['", $configuration)."']));\n";
               if (isset($value['tag_name']))
                  echo '        $object['."'".$value['tag_name']."'".'] = $object['."'".join("']['", $configuration)."'];\n";
               echo "}\n\n";
               unset($configuration[sizeof($configuration) -1]);
            }
            else
               $configuration = configureObjectFields($value, $configuration);
         }
      unset($configuration[sizeof($configuration) -1]);
      return $configuration;
   }
?>

     foreach ($this->objects as $i => $object)
     {
     	<?php $fields = $this->configuration->getValue('default.fields'); ?>
     	<?php configureObjectFields($fields) ?>
     	$this->objects[$i] = $object;
     }
  }
