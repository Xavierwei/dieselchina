<?php

/**
 * SlStore form base class.
 *
 * @method SlStore getObject() Returns the current form's model object
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSlStoreForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'name'                  => new sfWidgetFormInputText(),
      'address'               => new sfWidgetFormInputText(),
      'zip'                   => new sfWidgetFormInputText(),
      'telf'                  => new sfWidgetFormInputText(),
      'email'                 => new sfWidgetFormInputText(),
      'latitude'              => new sfWidgetFormInputText(),
      'longitude'             => new sfWidgetFormInputText(),
      'position'              => new sfWidgetFormInputText(),
      'world_area_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlWorldArea'), 'add_empty' => true)),
      'country_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlCountry'), 'add_empty' => true)),
      'city_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlCity'), 'add_empty' => true)),
      'store_type_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlStoreType'), 'add_empty' => true)),
      'store_status_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SlStoreStatus'), 'add_empty' => true)),
      'product_line_id'       => new sfWidgetFormInputText(),
      'online'                => new sfWidgetFormInputCheckbox(),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
      'slug'                  => new sfWidgetFormInputText(),
      'sl_product_lines_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SlProductLine')),
      'sl_store_types_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'SlStoreType')),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'address'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'zip'                   => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'telf'                  => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'email'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'latitude'              => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'longitude'             => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'position'              => new sfValidatorInteger(array('required' => false)),
      'world_area_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SlWorldArea'), 'required' => false)),
      'country_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SlCountry'), 'required' => false)),
      'city_id'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SlCity'), 'required' => false)),
      'store_type_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SlStoreType'), 'required' => false)),
      'store_status_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SlStoreStatus'), 'required' => false)),
      'product_line_id'       => new sfValidatorInteger(array('required' => false)),
      'online'                => new sfValidatorBoolean(array('required' => false)),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
      'slug'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sl_product_lines_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'SlProductLine', 'required' => false)),
      'sl_store_types_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'SlStoreType', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'SlStore', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('sl_store[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SlStore';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['sl_product_lines_list']))
    {
      $this->setDefault('sl_product_lines_list', $this->object->SlProductLines->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['sl_store_types_list']))
    {
      $this->setDefault('sl_store_types_list', $this->object->SlStoreTypes->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveSlProductLinesList($con);
    $this->saveSlStoreTypesList($con);

    parent::doSave($con);
  }

  public function saveSlProductLinesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['sl_product_lines_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->SlProductLines->getPrimaryKeys();
    $values = $this->getValue('sl_product_lines_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('SlProductLines', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('SlProductLines', array_values($link));
    }
  }

  public function saveSlStoreTypesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['sl_store_types_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->SlStoreTypes->getPrimaryKeys();
    $values = $this->getValue('sl_store_types_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('SlStoreTypes', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('SlStoreTypes', array_values($link));
    }
  }

}
