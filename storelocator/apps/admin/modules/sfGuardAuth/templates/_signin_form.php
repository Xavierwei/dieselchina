<?php use_helper('I18N') ?>

<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post" id="sl_login" class="admin_form">
  <?php echo $sf_user->getFlash('notice'); ?>
  <?php foreach ($form as $widget): ?>
    <div>
      <?php echo $widget->renderLabel(); ?>
      <?php echo $widget->render(); ?>
      <?php echo $widget->renderError(); ?>
    </div>
  <?php endforeach ?>

  <?php echo $form->renderHiddenFields(); ?>
  <input type="submit" value="<?php echo __('Signin', null, 'sf_guard') ?>" />
  <a href="<?php echo url_for('@sf_guard_forgot_password'); ?>">Forgot Password</a>

  <?php $routes = $sf_context->getRouting()->getRoutes() ?>
</form>

<style>
  #cnt {
    width: 218px;
    margin: 100px auto 0 auto;
    padding: 25px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 12px;
    box-shadow: 0 0 35px #888;
  }

  #cnt h1 {
    font-variant: small-caps;
    font-size: 21px;
    line-height: 40px;
    color: #333333;
    border-bottom: 1px solid #CCCCCC;
  }

  #cnt a {
    float: right;
    margin: 6px 0 0 0;
    font-size: 16px;
    line-height: 20px;
  }
</style>