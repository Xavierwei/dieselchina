<header>
    
  <a class="logo" href="<?php echo url_for('@homepage'); ?>">DIESEL</a>
  
  <nav>
    <ul class="sprite-menu">
      <li><a class="home <?php echo $sf_context->getActionName() == 'index'? 'current' : '' ?>" href="<?php echo url_for('@homepage'); ?>"><span>Home</span></a></li>
<?php /*      <li><a class="help <?php echo $sf_context->getActionName() == 'prehelpfaq'? 'current' : '' ?>" href="<?php echo url_for('@help'); ?>"><span>Help</span></a></li> */ ?>
      <li><a class="faq <?php echo $sf_context->getActionName() == 'faq'? 'current' : '' ?>" href="<?php echo url_for('@faq'); ?>"><span>Faq</span></a></li>
      <li><a class="support <?php echo $sf_context->getActionName() == 'support'? 'current' : '' ?>" href="<?php echo url_for('@support'); ?>"><span>Support</span></a></li>
      <li><a class="logout" href="<?php echo url_for('@sf_guard_signout'); ?>"><span>Logout</span></a></li>
    </ul>
      
   <?php /*   <a class="is-stupid" href="#">is stupid</a>
      <strong class="user">Alberto Speggiorin</strong>
      <ul class="sprite-logout-profile">
          <li><a class="logout" href="#"><span>logout</span></a></li>
          <li><a class="profile" href="#"><span>profile</span></a></li>
      </ul> */?>
  </nav>
    
</header>

<script type="text/javascript">
<!--
  function checkuser() {
    if ( !$.cookie('diesel_sso') ) {
      window.location.reload();
    }//if
  }//checkuser
//-->
</script>