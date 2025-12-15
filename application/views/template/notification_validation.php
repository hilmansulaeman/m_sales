<a href="#" class="dropdown-toggle" data-toggle="dropdown">
  <i class="fa fa-bell"></i>
  <span class="label label-warning"><?php echo $query->notif_all; ?></span>
</a>
<ul class="dropdown-menu">
  <li class="header">You have <?php echo $query->notif_all; ?> notifications</li>
  <li class="media">
    <!-- inner menu: contains the actual data -->

    <ul class="menu">
      <li>
        <a href="#">
          <i class="fa fa-users text-aqua"></i> <?php echo $query->notif_harimakan; ?> Kategori HARI MAKAN
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fa fa-users text-aqua"></i> <?php echo $query->notif_laporan; ?> Kategori LAPORAN
        </a>
      </li>
      <?php
      foreach ($media->result() as $m) { ?>
        <li>
          <a href="#">
            <div class="pull-left">
              <i class="fa fa-users text-aqua"></i> <b> Kategori <?php echo $m->Category;?></b>
              <p><?php echo $m->Announcement_Description;?></p>
            </div>
          </a>
        </li>

      <?php  } ?>


    </ul>
  </li>
  <li class="footer"><a href="#">View all</a></li>
</ul>