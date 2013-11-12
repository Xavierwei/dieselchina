<?php

/**
 * Stores form base class.
 *
 * @method Stores getObject() Returns the current form's model object
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStoresForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'brand'            => new sfWidgetFormInputText(),
      'type'             => new sfWidgetFormInputText(),
      'model'            => new sfWidgetFormInputText(),
      'status'           => new sfWidgetFormInputText(),
      'area'             => new sfWidgetFormInputText(),
      'country'          => new sfWidgetFormInputText(),
      'city'             => new sfWidgetFormInputText(),
      'name'             => new sfWidgetFormInputText(),
      'address'          => new sfWidgetFormInputText(),
      'zip'              => new sfWidgetFormInputText(),
      'telf'             => new sfWidgetFormInputText(),
      'last_update'      => new sfWidgetFormInputText(),
      'product_line'     => new sfWidgetFormInputText(),
      'latitude'         => new sfWidgetFormInputText(),
      'longitude'        => new sfWidgetFormInputText(),
      'order'            => new sfWidgetFormInputText(),
      'user_id'          => new sfWidgetFormInputText(),
      'store_types_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'StoreType')),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'brand'            => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'type'             => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'model'            => new sfValidatorString(array('max_length' => 3, 'required' => false)),
      'status'           => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'area'             => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'country'          => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'city'             => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'name'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'address'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'zip'              => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'telf'             => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'last_update'      => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'product_line'     => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'latitude'         => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'longitude'        => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'order'            => new sfValidatorInteger(array('required' => false)),
      'user_id'          => new sfValidatorInteger(array('required' => false)),
      'store_types_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'StoreType', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('stores[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Stores';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['store_types_list']))
    {
      $this->setDefault('store_types_list', $this->object->StoreTypes->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveStoreTypesList($con);

    parent::doSave($con);
  }

  public function saveStoreTypesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['store_types_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->StoreTypes->getPrimaryKeys();
    $values = $this->getValue('store_types_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('StoreTypes', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('StoreTypes', array_values($link));
    }
  }

}
