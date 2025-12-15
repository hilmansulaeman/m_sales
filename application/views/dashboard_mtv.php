<?php
    //$rows = $query->row();
    //$rowApp = $query_app->row();
    $day = date('d');
    $month = date('M');
    $year = date('Y');
    $periode = "01 - ".$day." / ".$month." / ".$year;
?>
<section>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <!--<li class="active">Top Navigation</li>-->
  </ol>
</section>
<div class="callout callout-info">
	<h5 class="text-center"><i class="icon fa fa-bell"></i> Welcome <?php echo $this->session->userdata('realname'); ?>.</h5>
</div>
<?php
	$flag = $this->session->userdata('FL_pass');
	if($flag == 1)
	{
		$style_form = "style='display:none;'";
	}
	else
	{
		$style_form = "";
	}
?>
<div class="box box-primary" <?php echo $style_form; ?>>
	<div class="box-header with-border">
        <h4>FORM UBAH PASSWORD<br><small class="text-red">Segera Ubah password anda, untuk keamanan data anda!!</small></h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
	<div class="box-body">
		<form method="post" action="<?php echo site_url('dashboard/update_password'); ?>">
		    <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
			<div class="form-group has-feedback">
				<label>Password :</label>
				<input type="password" name="password" class="form-control" placeholder="Password">
				<span class="glyphicon glyphicon-user form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<label>Retype Password :</label>
				<input type="password" name="retype_password" class="form-control" placeholder="Retype Password">
				<span class="glyphicon glyphicon-user form-control-feedback"></span>
			</div>
			<button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
		</form>
	</div>
</div>
<!--<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Dashboard Setoran Aplikasi <small><?php echo $periode; ?> </small></h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
        <div class="col-lg-4 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo number_format($rows->cc); ?></h3>

              <p>CC</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo number_format($rows->edc); ?></h3>

              <p>EDC</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo number_format($rows->sc); ?></h3>

              <p>SC</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo number_format($rows->pl); ?></h3>

              <p>PL</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-xs-12">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo number_format($rows->corp); ?></h3>

              <p>CORP</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div class="box-footer clearfix"></div>
</div>

<div class="box box-success">
    <div class="box-header with-border">
        <h4>Dashboard NOA <small><?php echo $periode; ?> </small></h4>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
        <div class="col-lg-4 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo number_format($rowApp->app_cc); ?></h3>

              <p>CC</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo number_format($rowApp->app_edc); ?></h3>

              <p>EDC</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo number_format($rowApp->app_sc); ?></h3>

              <p>SC</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo number_format($rowApp->app_pl); ?></h3>

              <p>PL</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-xs-12">
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo number_format($rowApp->app_corp); ?></h3>

              <p>CORP</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div class="box-footer clearfix"></div>
</div>-->