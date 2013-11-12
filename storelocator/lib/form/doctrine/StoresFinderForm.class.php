<?php

/**
 * Store form.
 *
 * @package    store
 * @subpackage form
 * @author     dtorresan
 */
class StoresFinderForm extends StoresForm
{
  public function configure()
  {
    parent::configure();

    $storeTypes = SlProductLineTable::getInstance()->getProdLineArray();
    
    $this->setWidgets(array(
      'country'  => new sfWidgetFormChoice(array('choices' => SlCountryTable::getInstance()->getCountries(true))),
      'city'     => new sfWidgetFormChoice(array('choices'=>array(""=>"All Cities"))),
      'type'    => new sfWidgetFormChoice(array('choices' => $storeTypes, 'multiple' => false, 'expanded' => false)),
    ));
    
    $this->setValidators(array(
      'type' =>  new sfValidatorChoice(array('required' => 'false', 'choices' => array_keys($storeTypes)))
    ));
    
  }
}
