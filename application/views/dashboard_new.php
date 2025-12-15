<?php
    //$rows = $query->row();
    //$rowApp = $query_app->row();
    $day = date('d');
    $month = date('M');
    $year = date('Y');
    $periode = "01 - ".$day." / ".$month." / ".$year;
    $username = $this->session->userdata('username');
    $query = $this->db->query("SELECT `date` FROM user_logs WHERE username = '$username' ORDER BY log_id DESC")->row();
?>

<style>
.carousel .carousel-inner .detail a img{
    width:100%;
    height:70vh;
}

@media (max-width:1024px){
    .carousel .carousel-inner .detail a img{
      height:30vh;
  }
}
</style>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->

<?php 

  if ($this->session->flashdata('poster') ) { ?>
    
      <script type="text/javascript">
        $(document).ready(function () {
          $('#posterModal').modal({
            show: 'true',
            backdrop: 'static',
            keyboard: false,
          });
        });
      </script>
      <script type="text/javascript">
          $(document).ready(function() {  

            $.getJSON( "<?php echo site_url('dashboard/get_veryfied') ?>", function( data ) {
            
                  if(data.is_checked == 0){
                    $("#ClosePoster").click(function() {
                      $('#verification_status').modal('show');  
                      $('#verification_status').click(function(){
                        window.location.href = "<?php echo site_url('addendum') ?>"
                      });         
                    });
                  } else {
                    $("#ClosePoster").click(function() {
                      $('#posterModal').modal('hide');
                    });
                  }
            });

          });
      </script>

<?php 
  } else if (empty($this->session->flashdata('poster'))) { ?>
      <script type="text/javascript">
          $(document).ready(function() {  

            $.getJSON( "<?php echo site_url('dashboard/get_veryfied') ?>", function( data ) {
            
                  if(data.is_checked == 0){
                    
                      $('#verification_status').modal('show');  
                      $('#verification_status').click(function(){
                        window.location.href = "<?php echo site_url('addendum') ?>"
                             
                    });
                  } 
            });

          });
      </script>

<?php } else if ($this->session->flashdata('poster')) { ?>
  <script type="text/javascript">
        $(document).ready(function () {
          $('#posterModal').modal({
            show: 'true',
            backdrop: 'static',
            keyboard: false,
          });
        });
  </script>
<?php } else { ?>
  <script type="text/javascript">
          $(document).ready(function() {  

            $.getJSON( "<?php echo site_url('dashboard/get_veryfied') ?>", function( data ) {
            
                  if(data.is_checked == 0){
                    if(data.sales_code == ''){
                      window.location.href = "<?= site_url('dashboard') ?>"
                    }
 
                  } 
            });

          });
      </script>
<?php } ?>


<section>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <!--<li class="active">Top Navigation</li>-->
  </ol>
</section>
<!-- <script type="text/javascript">
	$(document).ready(function() {  

    if($this->session->flashdata('poster')){
      $('#posterModal').modal({
      show: 'true',
      backdrop: 'static',
      keyboard: false,
    });
    if(EMPTY($get_poster)){
      
    }
    }else if(data.is_checked == 0){
      $("#ClosePoster").click(function() {
              $('#verification_status').modal('show');  
              $('#verification_status').click(function(){
                window.location.href = "<?php echo site_url('addendum') ?>"
              });         
            });
    }

	});
</script> -->

 <!-- cek addendum -->

<!-- <script type="text/javascript">
	$(document).ready(function() {  

    $.getJSON( "<?php echo site_url('dashboard/get_veryfied') ?>", function( data ) {
     
          if(data.is_checked == 0){
            $("#ClosePoster").click(function() {
              $('#verification_status').modal('show');  
              $('#verification_status').click(function(){
                window.location.href = "<?php echo site_url('addendum') ?>"
              });         
            });
          } else {
            $("#ClosePoster").click(function() {
              $('#posterModal').modal('hide');
            });
          }
    });

	});
</script> -->

<!-- <div class="callout callout-info">
	<h5 class="text-center"><i class="icon fa fa-bell"></i> Welcome <?php echo $this->session->userdata('realname'); ?>.</h5>
  <p class="text-center">Anda terakhir login pada tanggal <?= date("d-m-Y", strtotime($query->date)) ?>, jam <?= date("H:i:s", strtotime($query->date)) ?></p>
</div> -->

<div class="box box-info" id="box-information">
  <div class="box-header with-border">
    <p class="box-title text-center"><i class="icon fa fa-bell"></i> Welcome
      <?php echo $this->session->userdata('realname'); ?>.</p>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse">
        <i class="fa fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="box-body">
    <p class="text-center">Anda terakhir login pada tanggal <?= date("d-m-Y", strtotime($query->date)) ?>, jam
      <?= date("H:i:s", strtotime($query->date)) ?></p>
  </div>
</div>

<!-- Carousel -->
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <!-- <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li> -->
    <?php 
      $noInd = 1;
      
      foreach ($getRefCA->result() as $ind) { 
      if ($noInd == 1) {
        $classsInd = 'active';
      }else{
        $classsInd = '';
      }
      $noInd++;
    ?>
      <li data-target="#carousel-example-generic" data-slide-to="<?= $noInd ?>" class="<?= $classsInd ?>"></li>
    <?php } ?>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <?php 
      $active = 1;
      foreach ($getRefCA->result() as $rows) { 
        if ($active == 1) {
          $classs = 'active';
        }else{
          $classs = '';
        }
        $active = 0;
      ?>
      <div class="item <?= $classs ?> detail">
        <a href="<?= site_url("dashboard/carouselDetail/".$rows->Category_ID) ?>">
            <img src="<?= base_url('admin/upload/article/'.$rows->Image_Name) ?>">
        </a>
        <div class="carousel-caption">
          <h1><?= $rows->Category ?></h1>
        </div>
      </div>
    <?php } ?>

    <!-- <div class="item">
      <a href="<?= site_url("dashboard/carouselIndex/2") ?>">
        <img src="<?= base_url('public/images/desk (2).jpg') ?>" style="width: 100%; height: 80vh;">
      </a>
      <div class="carousel-caption">
        <h1>The Best Sales Perofrmance</h1>
      </div>
    </div>
    <div class="item">
      <a href="<?= site_url("dashboard/carouselIndex/3") ?>">
        <img src="<?= base_url('public/images/desk (3).jpg') ?>" style="width: 100%; height: 80vh;">
      </a>
      <div class="carousel-caption">
        <h1>Larangan Sales</h1>
      </div>
    </div>
    <div class="item">
      <a href="<?= site_url("dashboard/carouselIndex/4") ?>">
        <img src="<?= base_url('public/images/desk (4).jpg') ?>" style="width: 100%; height: 80vh;">
      </a>
      <div class="carousel-caption">
        <h1>Sales Tips & Trick</h1>
      </div>
    </div> -->

  </div>
  <!-- <p style="text-align:justify; padding-top:10px;">
    Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio animi unde deserunt, molestias, cum nam aliquid
    ratione quo hic mollitia impedit praesentium iure voluptates laborum nobis assumenda voluptatibus. Suscipit, quidem.
  </p> -->

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
<!-- Carousel -->

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

<!-- Modal -->
<!-- <div class="modal fade" id="posterModal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<img src="<?php echo base_url('upload/program/'.$this->session->userdata('poster_file')) ?>" height="100%" width="100%">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="ClosePoster">Close</button>
			</div>
		</div>
	</div>
</div> -->
<!-- <div class="modal fade" id="posterModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
          <?php foreach ($get_poster->result() as $row) { 
            $active = ($row->is_active == 1) ? 'active' : '';
          ?>
            <div class="carousel-item <?= $active ?>">
              <img src="<?php echo $url_poster.'upload/program/'.$row->poster ?>" height="100%" width="100%">
            </div>
          <?php } ?>
          </div>

          <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </a>
          <a class="carousel-control-next" href="#myCarousel" data-slide="next">
            <span class="carousel-control-next-icon"></span>
          </a>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="ClosePoster">Close</button>
      </div>
    </div>
  </div>
</div> -->

<div class="modal fade" id="posterModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="carousel-example-generic-poster" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner" role="listbox">
            <?php 
              $active = 1;
              foreach ($get_poster->result() as $rows) { 
                $classs = ($active == 1) ? 'active' : '';
                $active = 0;
              ?>
              <div class="item <?= $classs ?>">
                <img src="<?= $url_poster.'upload/program/'.$rows->poster ?>" alt="..." height="100%" width="100%">
              </div>
            <?php } ?>

          </div>
          <a class="left carousel-control" href="#carousel-example-generic-poster" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#carousel-example-generic-poster" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="ClosePoster">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="verification_status" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
      <p>Data Addendum anda sudah tersedia. Silahkan klik lanjut untuk melihat</p>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="Closeverification_status">Lanjut</button>
			</div>
		</div>
	</div>
</div>
<!--<div class="box box-primary">
    <div class="box-header with-border">
        <h4>Dashboard Setoran Aplikasi <small><?php //echo $periode; ?> </small></h4>
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
              <h3><?php //echo number_format($rows->cc); ?></h3>

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
              <h3><?php //echo number_format($rows->edc); ?></h3>

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
              <h3><?php //echo number_format($rows->sc); ?></h3>

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
              <h3><?php //echo number_format($rows->pl); ?></h3>

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
              <h3><?php //echo number_format($rows->corp); ?></h3>

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
        <h4>Dashboard NOA <small><?php //echo $periode; ?> </small></h4>
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
              <h3><?php //echo number_format($rowApp->app_cc); ?></h3>

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
              <h3><?php //echo number_format($rowApp->app_edc); ?></h3>

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
              <h3><?php //echo number_format($rowApp->app_sc); ?></h3>

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
              <h3><?php //echo number_format($rowApp->app_pl); ?></h3>

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
              <h3><?php //echo number_format($rowApp->app_corp); ?></h3>

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
