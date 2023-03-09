<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>    
    </ul>

 <ul class="navbar-nav ml-auto">
 
       <li class="nav-item dropdown">
            <a href="#" class="nav-link" data-toggle="dropdown">
              <span class="hidden-xs"><?php echo _l("Language")." : ".(($this->session->userdata('site_lang') != NULL) ? $this->session->userdata('site_lang') : "English"); ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
            
            <a class="dropdown-item" href="<?php echo site_url("languageswitcher/switchlang/english") ?>">English</a>
          <div class="dropdown-divider"></div>
           <a class="dropdown-item" href="<?php echo site_url("languageswitcher/switchlang/arabic") ?>">Arabic</a>
           
            </div>
       </li>
 

       <li class="nav-item dropdown user user-menu">
            <a href="#" class="nav-link" data-toggle="dropdown">
              <img src="<?php echo _get_current_user_image(); ?>" class="user-image" alt="<?php echo _get_current_user_fullname(); ?>">
              <span class="hidden-xs"><?php echo _get_current_user_fullname(); ?></span>
            </a>
          
           <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

            <li class="dropdown-item user-header">
                <img src="<?php echo _get_current_user_image(); ?>" class="img-circle" alt="<?php echo _get_current_user_fullname(); ?>">
                <p>
                  <?php echo _get_current_user_fullname(); ?>
                  <small><?php echo _get_current_user_email(); ?></small>
                </p>
              </li>
              
               <li><a class="nav-link" href="<?php echo site_url("profile"); ?>"><?php echo _l("Profile"); ?></a></li>                
               <li><a class="nav-link" href="<?php echo site_url("change_password"); ?>"><?php echo _l("Change Password"); ?></a></li>
               
              <!-- Menu Footer-->
              <li class="dropdown-item user-footer">
                <div class="float-right">
                  <a href="<?php echo site_url("login/logout"); ?>" class="btn btn-default btn-flat"><?php echo _l("Sign Out"); ?></a>
                </div>
              </li>
              
                </ul>
       </li>    
    
    </ul>

</nav>
