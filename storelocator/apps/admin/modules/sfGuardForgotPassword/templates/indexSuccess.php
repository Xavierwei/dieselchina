<?php use_helper('I18N') ?>
<h1><?php echo __('Forgot your password?', null, 'sf_guard') ?></h1>

<p>
  <?php echo __('Do not worry, we can help you get back in to your account safely!', null, 'sf_guard') ?>
  <?php echo __('Fill out the form below to request an e-mail with information on how to reset your password.', null, 'sf_guard') ?>
  <br/>
</p>

<form action="<?php echo url_for('@sf_guard_forgot_password') ?>" method="post">
  <table>
    <tbody>
		<?php echo $form ?>
  		<p class="error"><?php echo $sf_user->getFlash('error'); ?></p>
    </tbody>
  </table>
  <input type="submit" name="change" value="<?php echo __('Request', null, 'sf_guard') ?>" />
  <a href="<?php echo url_for('@sf_guard_signin'); ?>">Sign in</a>
</form>



<style>
  #cnt {
    width: 270px;
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

  #cnt p {
  	margin: 15px 0 0 0;
    font-variant: small-caps;
    font-size: 16px;
    line-height: 20px;
    color: #333333;
  }

  #cnt a {
  	float: right;
  	margin: 15px 0 0 0;
    font-size: 16px;
    line-height: 20px;
  }

  #cnt form label{
	display: inline-block;
	margin: 0 0 5px 0;
	font-family: Arial, Helvetica, sans-serif;
	color: #333333;
	font-size: 14px;
	/*line-height: 14px;*/
	width: 80px;
	text-align: left;
  }

  #cnt form input[type=text]{
	color: #555555;
	border-radius: 4px 4px 4px 4px;
	display: inline-block;
	height: 20px;
	width: 165px;
	margin-bottom: 8px;
	padding: 4px 6px;
	vertical-align: middle;
	background-color: #FFFFFF;
	border: 1px solid #CCCCCC;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
  }

  #cnt form input[type=submit]{
  	clear: both;
  	font-size: 16px;
	background-color: #F5F5F5;
	background-image: linear-gradient(to bottom, #FFFFFF, #E6E6E6);
	background-repeat: repeat-x;
	border-radius: 4px 4px 4px 4px;
	border-style: solid;
	border-width: 1px;
	border-color: rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.25);
	box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
	color: #333333;
	cursor: pointer;
	display: inline-block;
	margin: 12px 0 0 0;
	padding: 4px 12px;
	text-align: center;
	text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
	vertical-align: middle;
  }
  #cnt form input[type=submit]:hover{
	background-color: #E6E6E6;
	background-position: 0 -15px;
  }
  #cnt form .error_list{
	list-style: disc;
	color: #DA2A28;
	margin: 0 0 5px 15px;
  }
  #cnt form p.error{
	color: #DA2A28;
	margin: 0 0 5px 0;
	padding: 0;
	font: 13px Helmet,Freesans,sans-serif;
  }


</style>