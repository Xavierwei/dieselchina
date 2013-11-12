<?php

/**
 * SlStore form.
 *
 * @package    collections
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SlStoreForm extends BaseSlStoreForm
{
  public function configure()
  {
    unset($this['product_line_id'], $this['updated_at'], $this['created_at'], $this['slug'], $this['store_type_id']);
  }
}
