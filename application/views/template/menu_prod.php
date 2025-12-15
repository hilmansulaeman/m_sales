<?php
  $sales_code = $this->session->userdata('sl_code');
  $posisi = $this->session->userdata('position');
  $filename="";
  if($posisi == "DSR" OR $posisi == "SALES")
  {
    $filename = "dsr";
  }elseif ($posisi == "SPV" ) {
    $filename = "spv";
  }elseif ($posisi == "ASM" ) {
    $filename = "asm";
  }elseif ($posisi == "RSM" ) {
    $filename = "rsm";
  }elseif ($posisi == "BSH" ) {
    $filename = "bsh";
  }
  
//cek absen welma
date_default_timezone_set('Asia/Jakarta');
$bulan = date("m");
// $hari = date("N");
$tanggal = date("d");
$tahun = date("Y");
// $waktu = date("H:i");
$cektglabs = $tahun . '-' . $bulan . '-' . $tanggal;
//start cek absen
//absen masuk
$querydb2 = $this->db->select('count(*) jumlahabsmsk')->where(array(
             'sales_code'    => $sales_code,
             'kategori'    => 'masuk',
 ))->like('created_date',$cektglabs)->get('data_absen');
$hasiljmlabs = $querydb2->row();
$jmlabsmsk = $hasiljmlabs->jumlahabsmsk;
//absen keluar
$querydb3 = $this->db->select('count(*) jumlahabsplg')->where(array(
             'sales_code'    => $sales_code,
             'kategori'    => 'pulang',
 ))->like('created_date',$cektglabs)->get('data_absen');
$hasiljmlabsplg = $querydb3->row();
$jmlabsplg = $hasiljmlabsplg->jumlahabsplg;
//end cek absen

$checkTTD = check_ttd();
?>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url() ?>public/images/users.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('realname'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> <?php echo $posisi; ?></a>
        </div>
      </div>
      <!-- search form -->
      <!--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENU</li>
        <li class="<?= activate_menu('dashboard'); ?>"><a href="<?php echo site_url() ?>"><i class="fa fa-home"></i><span>Home </span></a></li>
				<?php if ($checkTTD['status']) { ?>
				<li><a href="<?php echo site_url('addendum') ?>"><i class="fa fa-bitbucket"></i><span>Data Addendum </span></a></li>
			<?php } else { ?>
				<!-- Input -->
				<li class="treeview <?= activate_directory('input'); ?>" style="display:<?= show_menu('input'); ?>">
					<a href="#"><i class="fa fa-pencil"></i> <span>Input Data</span> <!-- input -->
							<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
					<ul class="treeview-menu">
						<li class="<?= activate_menu('pemol'); ?>" style="display:<?= show_menu('input/pemol'); ?>">
						<a href="<?= site_url('input/pemol') ?>"><i class="fa fa-circle-thin"></i>Pemol</a>
						</li>
						<li class="<?= activate_menu('ebranch'); ?>" style="display:<?= show_menu('input/ebranch'); ?>">
						<a href="<?= site_url('input/ebranch/add') ?>"><i class="fa fa-circle-thin"></i>E-branch/Acco</a>
						</li>
						<li class="<?= activate_menu('edc'); ?>" style="display:none;">
						<a href="<?= site_url('input/edc/index/new_merchant') ?>"><i class="fa fa-circle-thin"></i>EDC</a>
						</li>
						<li class="treeview <?= activate_menu('qris'); ?>" style="display:none; ?>">
						<a href="#"><i class="fa fa-circle-thin"></i> <span>QRIS</span>
							<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
							</span>
							</a>
						<ul class="treeview-menu">
						<li>
							<a href="<?= site_url('input/qris/index/new_merchant') ?>"><i class="fa fa-circle-thin"></i>New Merchant</a>
						</li>
						<li>
							<a href="<?= site_url('input/qris/index/returned') ?>"><i class="fa fa-circle-thin"></i>Merchant Return</a>
						</li>
						</ul>
						</li>
						<li class="<?= activate_menu('welma'); ?>" style="display:<?= show_menu('input/welma'); ?>">
						<a href="<?= site_url('input/welma') ?>"><i class="fa fa-circle-thin"></i>Welma</a>
						</li>
					</ul>
				</li>
				<!-- Incoming -->
				<li class="treeview <?= activate_menu('incoming'); ?>">
					<a href="#"><i class="fa fa-link"></i> <span>Setoran Aplikasi</span> <!-- input -->
							<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
					<ul class="treeview-menu">
						<li class="<?= activate_submenu('incoming','pemol'); ?>" style="display:<?= show_menu('incoming/pemol'); ?>">
						<a href="<?= site_url('incoming/pemol') ?>"><i class="fa fa-info"></i>Pemol</a>
						</li>
						<li class="<?= activate_submenu('incoming','merchant'); ?>">
						<a href="<?= site_url('incoming/merchant') ?>"><i class="fa fa-info"></i>Merchant</a>
						</li>
						<li style="display:none;">
						<a href="<?php echo site_url('incoming/edc_new') ?>"><i class="fa fa-info"></i><span>EDC </span></a>
						</li>
						<li>
						<a href="<?php echo site_url('incoming/cc_reg') ?>"><i class="fa fa-info"></i><span>CC Reg</span></a>
						</li>
						<li>
						<a href="<?php echo site_url('incoming/cc_ms') ?>"><i class="fa fa-info"></i><span>CC MS </span></a>
						</li>
						<li>
						<a href="<?php echo site_url('incoming/cc_corp') ?>"><i class="fa fa-info"></i><span>Corp </span></a>
						</li>
						<li>
						<a href="<?php echo site_url('incoming/sc') ?>"><i class="fa fa-info"></i><span>SC </span></a>
						</li>
						<li>
						<a href="<?php echo site_url('incoming/pl') ?>"><i class="fa fa-info"></i><span>PL </span></a>
						</li>
						<li>
						<a href="<?php echo site_url('incoming/welma') ?>"><i class="fa fa-info"></i><span>WELMA </span></a>
						</li>
						<li>
								<a href="<?php echo site_url('incoming/bcas') ?>"><i class="fa fa-info"></i><span>BCA SYARIAH </span></a>
							</li>
					</ul>
				</li>
						<li style="display:none;"><a href="<?php echo site_url('incoming') ?>"><i class="fa fa-info"></i><span>Setoran Aplikasi </span></a></li>
				<!-- DATA DECISION -->
				<li class="treeview">
					<a href="#"><i class="fa fa-font"></i> <span>Data Decision</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li>
						<a href="<?php echo site_url('decision/pemol') ?>"><i class="fa fa-info"></i>Pemol</a>
						</li>
						<li>
						<a href="<?php echo site_url('decision/merchant') ?>"><i class="fa fa-info"></i><span>Merchant </span></a>
						</li>
						<li style="display:none;">
						<a href="<?php echo site_url('decision/edc') ?>"><i class="fa fa-info"></i><span>EDC </span></a>
						</li>
						<li>
						<a href="<?php echo site_url('decision/cc') ?>"><i class="fa fa-info"></i><span>CC </span></a>
						</li>
						<li>
						<a href="<?php echo site_url('decision/corp') ?>"><i class="fa fa-info"></i><span>Corp </span></a>
						</li>
						<li>
						<a href="<?php echo site_url('decision/sc') ?>"><i class="fa fa-info"></i><span>SC </span></a>
						</li>
						<li>
						<a href="<?php echo site_url('decision/pl') ?>"><i class="fa fa-info"></i><span>PL </span></a>
						</li>
					</ul>
				</li>
				
				<!-- CEK APLIKASI -->
					<li class="treeview">
						<a href="#"><i class="fa fa-search"></i> <span>Cek Aplikasi</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>
						<ul class="treeview-menu">
					<li>
								<a href="<?php echo site_url('cek_aplikasi/pemol') ?>"><i class="fa fa-info"></i><span>Pemol </span></a>
							</li>
					<li>
								<a href="<?php echo site_url('cek_aplikasi/merchant') ?>"><i class="fa fa-info"></i><span>Merchant </span></a>
							</li>
							<li>
								<a href="<?php echo site_url('cek_aplikasi/cc') ?>"><i class="fa fa-info"></i><span>CC </span></a>
							</li>
					<li>
								<a href="<?php echo site_url('cek_aplikasi/corp') ?>"><i class="fa fa-info"></i><span>Corp </span></a>
							</li>
							<li>
								<a href="<?php echo site_url('cek_aplikasi/sc') ?>"><i class="fa fa-info"></i><span>SC </span></a>
							</li>
							<li>
								<a href="<?php echo site_url('cek_aplikasi/pl') ?>"><i class="fa fa-info"></i><span>PL </span></a>
							</li>
						</ul>
					</li>
				
						<li style="display:none;"><a href="<?php echo site_url('decision') ?>"><i class="fa fa-font"></i><span>Data Decision </span></a></li>
						<li><a href="<?php echo site_url('my_performance') ?>"><i class="fa fa-bar-chart"></i> <span>My Performance</span></a></li>
				
				<!-- ABSEN -->
				<li class="treeview" style="display:<?= show_menu('absen'); ?>">
					<a href="#"><i class="fa fa-search"></i> <span>Absen</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<?php
						if ($jmlabsmsk == 0 && $jmlabsplg == 0) {
						?>
						<li style="display:<?= show_menu('absen'); ?>">
							<a href="<?php echo site_url('absen/masuk') ?>"><i class="fa fa-info"></i><span>Absen Masuk</span></a>
						</li>
						<?php
						}
						?>
						<?php
						if ($jmlabsplg == 0 && $jmlabsmsk == 1) {
						?>
						<li style="display:<?= show_menu('absen'); ?>">
							<a href="<?php echo site_url('absen/pulang') ?>"><i class="fa fa-info"></i><span>Absen Pulang </span></a>
						</li>
						<?php
						}
						?>

						<li style="display:<?= show_menu('absen'); ?>">
						<a href="<?php echo site_url('absen/izin') ?>"><i class="fa fa-info"></i><span>Absen Izin </span></a>
						</li>
						<li style="display:<?= show_menu('absen'); ?>">
						<a href="<?php echo site_url('absen/sakit') ?>"><i class="fa fa-info"></i><span>Absen Sakit </span></a>
						</li>
						<li style="display:<?= show_menu('absen'); ?>">
						<a href="<?php echo site_url('absen/off') ?>"><i class="fa fa-info"></i><span>Absen Off </span></a>
						</li>

					</ul>
				</li>
				<li><a href="<?php echo site_url('addendum') ?>"><i class="fa fa-bitbucket"></i><span>Data Addendum </span></a></li>
				<li style="display:none;"><a href="<?php echo site_url('payroll_weekly') ?>"><i class="fa fa-usd"></i> <span>Payroll Weekly </span></a></li>
				<li style="display:<?= show_menu('slipincentive'); ?>"><a href="<?php echo site_url('slipincentive') ?>"><i class="fa fa-file-archive-o"></i> <span>Slip Incentive </span></a></li>
				<li style="display:<?= show_menu('team_performance'); ?>"><a href="<?php echo site_url('team_performance/pos_bsh') ?>"><i class="fa fa-users"></i> <span>Team Performance </span></a></li>
				<li style="display:<?= show_menu('sales'); ?>"><a href="<?php echo site_url('sales') ?>"><i class="fa fa-user"></i> <span>Data Sales </span></a></li>
				
				<li class="treeview" style="display:<?php echo show_menu('request'); ?>">
				<!--<li class="treeview" style="display:none;">-->
					<a href="#"><i class="fa fa-book"></i> <span>Request to HRD</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li style="display:<?= show_menu('request/exit_form'); ?>"><a href="<?php echo site_url('request/exit_form') ?>"><i class="fa fa-user"></i> <span>Exit </span></a></li>
						<li ><a href="<?php echo site_url('request/restruct_form') ?>"><i class="fa fa-user"></i> <span>Restruct </span></a></li>
						<li <?= show_menu('request/level_form'); ?>"><a href="<?php echo site_url('request/level_form') ?>"><i class="fa fa-user"></i> <span>Level</span></a></li>
						<li style="display:none;"><a href="<?php echo site_url('request/deviasi_form') ?>"><i class="fa fa-user"></i> <span>Deviasi</span></a></li>
						<li ><a href="<?php echo site_url('request/reaktif_form') ?>"><i class="fa fa-user"></i> <span>Reaktif</span></a></li>
					</ul>
				</li>
				<li class="treeview" style="display:<?php echo show_menu('approval_request'); ?>">
				<!--<li class="treeview" style="display:none;">-->
					<a href="#"><i class="fa fa-list"></i> <span>Approval</span>
						<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li style="display:<?= show_menu('approval_request/restruct_form'); ?>;"><a href="<?php echo site_url('approval_request/restruct_form') ?>"><i class="fa fa-user"></i> <span>Restruct </span></a></li>
						<li style="display:<?= show_menu('approval_request/reaktif_form'); ?>;"><a href="<?php echo site_url('approval_request/reaktif_form') ?>"><i class="fa fa-user"></i> <span>Reaktif </span></a></li>
						<li style="display:<?= show_menu('approval_request/level_form'); ?>"><a href="<?php echo site_url('approval_request/level_form') ?>"><i class="fa fa-user"></i> <span>Promosi</span></a></li>
					</ul>
				</li>
				
				<li style="display:none;"><a href="<?php echo site_url('search') ?>"><i class="fa fa-search"></i> <span>Search</span></a></li>
				<li><a href="<?php echo site_url('kode_pos') ?>"><i class="fa fa-location-arrow"></i> <span>Cek Kode Pos</span></a></li>
				<li><a href="<?php echo site_url('duplicate_cc/cek_duplicate') ?>"><i class="fa fa-file-excel-o"></i> <span>Cek Duplicate</span></a></li>
						<li style="display:<?= show_menu('profile/idcard'); ?>"><a href="<?php echo site_url('profile/idcard') ?>"><i class="fa fa-file-photo-o"></i> <span>ID Card</span></a></li>
				<?php
					if($posisi == "SPV")
					{
				?>
					<li><a href="<?php echo site_url('spv') ?>"><i class="fa fa-tv"></i> <span>Monitoring</span></a></li>
				<?php
					}elseif($posisi == "ASM")
					{
				?>
					<li><a href="<?php echo site_url('asm') ?>"><i class="fa fa-tv"></i> <span>Monitoring</span></a></li>
				<?php
					}elseif($posisi == "RSM")
					{
				?>
					<li><a href="<?php echo site_url('rsm') ?>"><i class="fa fa-tv"></i> <span>Monitoring</span></a></li>
				<?php
					}elseif($posisi == "BSH")
					{
				?>
					<li><a href="<?php echo site_url('bsh') ?>"><i class="fa fa-tv"></i> <span>Monitoring</span></a></li>
				<?php
					}
				?>
				<li style="display:none;"><a href="<?php echo site_url('approval/approval') ?>"><i class="fa fa-user"></i> <span>Approval Team</span></a></li>
		<?php } ?>
		<li><a href="<?php echo site_url('logout') ?>"><i class="fa fa-sign-out"></i> <span>Sign out</span> </a></li>
      </ul>
	  
    </section>
    <!-- /.sidebar -->
</aside>
  