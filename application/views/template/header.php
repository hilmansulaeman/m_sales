<?php
	$fl_pass = $this->session->userdata('FL_pass');
	if($fl_pass == 0)
	{
		$style_btn = "style='display:none;'";
	}
	else
	{
		$style_btn = "";
	}
?>
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url(); ?>" class="logo">
      <span>DIKA SALES MONITORING</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" <?php echo $style_btn; ?>>
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">0</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 0 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo FOTO_URL.$this->session->userdata('foto'); ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $this->session->userdata('realname'); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo FOTO_URL.$this->session->userdata('foto'); ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $this->session->userdata('realname'); ?> | <?php echo $this->session->userdata('sl_code'); ?>
                  <small><?php echo $this->session->userdata('position'); ?></small>
                </p>
              </li>
              <!-- Menu Body -->
			  <?php
				$data = $this->global_query->get_count_team()->row();
			  ?>
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-3 text-center">
                    <a href="#">RSM (<?php echo 0+$data->RSM; ?>)</a>
                  </div>
                  <div class="col-xs-3 text-center">
                    <a href="#">ASM (<?php echo 0+$data->ASM; ?>)</a>
                  </div>
                  <div class="col-xs-3 text-center">
                    <a href="#">SPV (<?php echo 0+$data->SPV; ?>)</a>
                  </div>
				  <div class="col-xs-3 text-center">
                    <a href="#">DSR (<?php echo 0+$data->DSR; ?>)</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <!--<a href="#" class="btn btn-default btn-flat">Profile</a>-->
                </div>
                <div class="pull-right">
                  <a href="<?php echo site_url('logout') ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>