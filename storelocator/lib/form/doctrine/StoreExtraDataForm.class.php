<?php

/**
 * StoreExtraData form.
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StoreExtraDataForm extends BaseStoreExtraDataForm
{
  public function configure()
  {
    $this->widgetSchema['store_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['info']->setLabel('Store extra data');
  }
}
