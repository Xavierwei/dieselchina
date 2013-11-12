<?php

/**
 * SlStoreProductLine form base class.
 *
 * @method SlStoreProductLine getObject() Returns the current form's model object
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSlStoreProductLineForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sl_store_id'        => new sfWidgetFormInputHidden(),
      'sl_product_line_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'sl_store_id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('sl_store_id')), 'empty_value' => $this->getObject()->get('sl_store_id'), 'required' => false)),
      'sl_product_line_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('sl_product_line_id')), 'empty_value' => $this->getObject()->get('sl_product_line_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sl_store_product_line[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SlStoreProductLine';
  }

}
