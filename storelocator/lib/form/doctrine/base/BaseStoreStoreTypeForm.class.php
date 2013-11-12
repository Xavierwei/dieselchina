<?php

/**
 * StoreStoreType form base class.
 *
 * @method StoreStoreType getObject() Returns the current form's model object
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStoreStoreTypeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'stores_id'     => new sfWidgetFormInputHidden(),
      'store_type_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'stores_id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('stores_id')), 'empty_value' => $this->getObject()->get('stores_id'), 'required' => false)),
      'store_type_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('store_type_id')), 'empty_value' => $this->getObject()->get('store_type_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('store_store_type[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StoreStoreType';
  }

}
