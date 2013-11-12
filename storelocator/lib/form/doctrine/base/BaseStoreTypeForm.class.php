<?php

/**
 * StoreType form base class.
 *
 * @method StoreType getObject() Returns the current form's model object
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStoreTypeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'type'       => new sfWidgetFormTextarea(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
      'store_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Stores')),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'type'       => new sfValidatorString(array('max_length' => 1000)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
      'store_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Stores', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('store_type[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StoreType';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['store_list']))
    {
      $this->setDefault('store_list', $this->object->Store->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveStoreList($con);

    parent::doSave($con);
  }

  public function saveStoreList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['store_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Store->getPrimaryKeys();
    $values = $this->getValue('store_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Store', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Store', array_values($link));
    }
  }

}
