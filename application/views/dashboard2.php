<?php
    $db2 = $this->load->database('db2', TRUE);
    //$rows = $query->row();
    //$rowApp = $query_app->row();
    $day = date('d');
    $month = date('M');
    $smonth = date('m');
    $year = date('Y');
    $periode = "01 - ".$day." / ".$month." / ".$year;

    $dateNow = date('Y-m-d');

    $position = $this->session->userdata('position');
    $sl_code = $this->session->userdata('sl_code');
    $product = $this->session->userdata('product');

    // $tgl1 = date('Y-m-d', strtotime('-24 days', strtotime($dateNow)));
    // $tgl1 = date('Y-m-d', strtotime('-30 days', strtotime($dateNow)));
    // $tgl = date('Y-m-d', strtotime('-2 days', strtotime($dateNow)));

    $getDataSummary = $db2->query("SELECT * FROM `0701_data_summary` WHERE Code = '$sl_code' AND Posisi = '$position' AND Product = '$product' AND Created_Date = '2019-12-31'")->row();

    $getCalender = $this->db->query("SELECT * FROM data_calender WHERE `Date` = '2019-12-27'")->row();
    $getReference1 = $this->db->query("SELECT * FROM ref_reference WHERE ID = '1'")->row();
    $getReference2 = $this->db->query("SELECT * FROM ref_reference WHERE ID = '2'")->row();
    
    if ($getDataSummary == TRUE) {
      $A = (int)$getDataSummary->T_App_Leader; //A = TARGET APPROVAL LEADER
      $B = (int)$getDataSummary->T_App_DSR; //B = TARGET APPROVAL DSR
      $C = (int)$getDataSummary->Tot_DSR; //C = ACTUAL DSR ACTIVE (H-1)
      $H = (int)$getDataSummary->H; //H =  Jumlah sales dengan incoming 0 MTD dengan MOB Join > 1 minggu
      $I = (int)$getDataSummary->Avg_Apprate_Nas; //I = RATA-RATA APPROVE RATE NASIONAL
      $J = (int)$getDataSummary->Avg_Apprate_Leader; //J = RATA-RATA APPROVE RATE SPV
      $L = (int)$getCalender->Total_Working_Days; //L = JUMLAH/TOTAL HARI KERJA
      $M = (int)$getCalender->Running_Day; //M = HARI KERJA KE
      $Remaining_Days = (int)$getCalender->Remaining_Working_Days;
      $Total_setor = (int)$getDataSummary->Tot_Setor;
      $Reference1 = (int)$getReference1->reference;
      $Reference2 = (int)$getReference2->reference;

      if ($product == 'CC') {
        $K = (int)$getDataSummary->Inc_cc; //K = Incomming actual MTD
        $N = (int)$getDataSummary->Noa_cc; //N = Approval ACTUAL MTD
        $RTS = (int)$getDataSummary->Rts_cc;
        $DEC = (int)$getDataSummary->Dec_cc;
      }else if ($product == 'EDC') {
        $K = (int)$getDataSummary->Inc_edc; //K = Incomming actual MTD
        $N = (int)$getDataSummary->Noa_edc; //N = Approval ACTUAL MTD
        $RTS = (int)$getDataSummary->Rts_edc;
        $DEC = (int)$getDataSummary->Dec_edc;
      }else if ($product == 'SC') {
        $K = (int)$getDataSummary->Inc_sc; //K = Incomming actual MTD
        $N = (int)$getDataSummary->Noa_sc; //N = Approval ACTUAL MTD
        $RTS = (int)$getDataSummary->Rts_sc;
        $DEC = (int)$getDataSummary->Dec_sc;
      }else if ($product == 'CORP') {
        $K = (int)$getDataSummary->Inc_corp; //K = Incomming actual MTD
        $N = (int)$getDataSummary->Noa_corp; //N = Approval ACTUAL MTD
        $RTS = (int)$getDataSummary->Rts_corp;
        $DEC = (int)$getDataSummary->Dec_corp;
      }else if ($product == 'PL') {
        $K = (int)$getDataSummary->Inc_pl; //K = Incomming actual MTD
        $N = (int)$getDataSummary->Noa_pl; //N = Approval ACTUAL MTD
        $RTS = (int)$getDataSummary->Rts_pl;
        $DEC = (int)$getDataSummary->Dec_pl;
      }

      // BEGIN APPROVAL
          $E1 = $N;
          $E10 = round(($N/$M)*$L); //RUN RATE MTD

          $E2 = round($A);

          $colorApproval = $E2;
          $startColorYellowApproval = $colorApproval*(80/100);
          $startColorGreenApproval = $colorApproval*(100/100);
          $startColorLightBlueApproval = $colorApproval*(120/100);
          $startColorDarkBlueApproval = $colorApproval*(150/100);

          $endColorRedApproval = $startColorYellowApproval;
          $endColorYellowApproval = $startColorGreenApproval;
          $endColorGreenApproval = $startColorLightBlueApproval;
          $endColorLightBlueApproval = $startColorDarkBlueApproval;
          $endColorDarkBlueApproval = 2*$colorApproval;

          if ($E1 >= 0 && $E1 < $endColorRedApproval) {
            $bgColorApproval = '#d72828';
          }else if($E1 >= $startColorYellowApproval  && $E1 < $endColorYellowApproval) {
            $bgColorApproval = '#edf10e';
          }else if($E1 >= $startColorGreenApproval  && $E1 < $endColorGreenApproval) {
            $bgColorApproval = '#57c33c';
          }else if($E1 >= $startColorLightBlueApproval  && $E1 < $endColorLightBlueApproval) {
            $bgColorApproval = '#00bfff';
          }else if($E1 >= $startColorDarkBlueApproval) {
            $bgColorApproval = '#006080';
          }
      // END APPROVAL -- Cek Warning

      // BEGIN APPROVAL KUALITAS
          if ($N == 0 && $DEC == 0) {
            $E5 = 0;
          }else{
            $E5 = round($N/($N+$DEC));
          }

          $E6 = $I/100;

          $colorApprovalKualitas = $E6;
          $startColorYellowApprovalKualitas = $colorApprovalKualitas*(80/100);
          $startColorGreenApprovalKualitas = $colorApprovalKualitas*(100/100);
          $startColorLightBlueApprovalKualitas = $colorApprovalKualitas*(120/100);
          $startColorDarkBlueApprovalKualitas = $colorApprovalKualitas*(150/100);

          $endColorRedApprovalKualitas = $startColorYellowApprovalKualitas;
          $endColorYellowApprovalKualitas = $startColorGreenApprovalKualitas;
          $endColorGreenApprovalKualitas = $startColorLightBlueApprovalKualitas;
          $endColorLightBlueApprovalKualitas = $startColorDarkBlueApprovalKualitas;
          $endColorDarkBlueApprovalKualitas = 2*$colorApprovalKualitas;

          if ($E5 >= 0 && $E5 < $endColorRedApprovalKualitas) {
            $bgColorApprovalKualitas = '#d72828';
          }else if($E5 >= $startColorYellowApprovalKualitas  && $E5 < $endColorYellowApprovalKualitas) {
            $bgColorApprovalKualitas = '#edf10e';
          }else if($E5 >= $startColorGreenApprovalKualitas  && $E5 < $endColorGreenApprovalKualitas) {
            $bgColorApprovalKualitas = '#57c33c';
          }else if($E5 >= $startColorLightBlueApprovalKualitas  && $E5 < $endColorLightBlueApprovalKualitas) {
            $bgColorApprovalKualitas = '#00bfff';
          }else if($E5 >= $startColorDarkBlueApprovalKualitas) {
            $bgColorApprovalKualitas = '#006080';
          }
      // END APPROVAL KUALITAS -- Cek Warning

      // BEGIN PRODUKSI
          $C1 = round($K/$M/$C); //C1 = ACTUAL INC. P. DAY P. DSR

          //C2 = KEBUTUHAN INC
          if ($J == 0) {
            $C2 = round($A/($I/100));
          }else{
            $C2 = round($A/($J/100));
          }

          if ($Remaining_Days == 0) {
            $C3 = round(($C2 - $K) / $C);
          }else{
            $C3 = round(($C2 - $K) / $Remaining_Days / $C);
          }

          $colorProduksi = $C3;
          $startColorYellowProduksi = $colorProduksi*(80/100);
          $startColorGreenProduksi = $colorProduksi*(100/100);
          $startColorLightBlueProduksi = $colorProduksi*(120/100);
          $startColorDarkBlueProduksi = $colorProduksi*(150/100);

          $endColorRedProduksi = $startColorYellowProduksi;
          $endColorYellowProduksi = $startColorGreenProduksi;
          $endColorGreenProduksi = $startColorLightBlueProduksi;
          $endColorLightBlueProduksi = $startColorDarkBlueProduksi;
          $endColorDarkBlueProduksi = 2*$colorProduksi;

          if ($C1 >= 0 && $C1 < $endColorRedProduksi) {
            $bgColorProduksi = '#d72828';
          }else if($C1 >= $startColorYellowProduksi  && $C1 < $endColorYellowProduksi) {
            $bgColorProduksi = '#edf10e';
          }else if($C1 >= $startColorGreenProduksi  && $C1 < $endColorGreenProduksi) {
            $bgColorProduksi = '#57c33c';
          }else if($C1 >= $startColorLightBlueProduksi  && $C1 < $endColorLightBlueProduksi) {
            $bgColorProduksi = '#00bfff';
          }else if($C1 >= $startColorDarkBlueProduksi) {
            $bgColorProduksi = '#006080';
          }
      // END PRODUKSI -- Cek Success

      // BEGIN PRODUKSI KUALTIAS
          $D1 = round($RTS/$Total_setor/100);

          $D2 = 10;

          $colorProduksiKualitas = $D2;
          $startColorYellowProduksiKualitas = $colorProduksiKualitas*(80/100);
          $startColorGreenProduksiKualitas = $colorProduksiKualitas*(100/100);
          $startColorLightBlueProduksiKualitas = $colorProduksiKualitas*(120/100);
          $startColorDarkBlueProduksiKualitas = $colorProduksiKualitas*(150/100);

          $endColorRedProduksiKualitas = $startColorYellowProduksiKualitas;
          $endColorYellowProduksiKualitas = $startColorGreenProduksiKualitas;
          $endColorGreenProduksiKualitas = $startColorLightBlueProduksiKualitas;
          $endColorLightBlueProduksiKualitas = $startColorDarkBlueProduksiKualitas;
          $endColorDarkBlueProduksiKualitas = 2*$colorProduksiKualitas;

          if ($D1 >= 0 && $D1 < $endColorRedProduksiKualitas) {
            $bgColorProduksiKualitas = '#d72828';
          }else if($D1 >= $startColorYellowProduksiKualitas  && $D1 < $endColorYellowProduksiKualitas) {
            $bgColorProduksiKualitas = '#edf10e';
          }else if($D1 >= $startColorGreenProduksiKualitas  && $D1 < $endColorGreenProduksiKualitas) {
            $bgColorProduksiKualitas = '#57c33c';
          }else if($D1 >= $startColorLightBlueProduksiKualitas  && $D1 < $endColorLightBlueProduksiKualitas) {
            $bgColorProduksiKualitas = '#00bfff';
          }else if($D1 >= $startColorDarkBlueProduksiKualitas) {
            $bgColorProduksiKualitas = '#006080';
          }
      // END PRODUKSI KUALTIAS -- Cek Warning

      // BEGIN SPAN OF CONTROL
          $A1 = $C;
              
          $A2 = round(($A / $B) * (150 / 100)); // A2 = TARGET SOC

          $color = $A2;
          $startColorYellow = $color*(80/100);
          $startColorGreen = $color*(100/100);
          $startColorLightBlue = $color*(120/100);
          $startColorDarkBlue = $color*(150/100);

          $endColorRed = $startColorYellow;
          $endColorYellow = $startColorGreen;
          $endColorGreen = $startColorLightBlue;
          $endColorLightBlue = $startColorDarkBlue;
          $endColorDarkBlue = 2*$color;

          if ($A1 >= 0 && $A1 < $endColorRed) {
            $bgColor = '#d72828';
          }else if($A1 >= $startColorYellow  && $A1 < $endColorYellow) {
            $bgColor = '#edf10e';
          }else if($A1 >= $startColorGreen  && $A1 < $endColorGreen) {
            $bgColor = '#57c33c';
          }else if($A1 >= $startColorLightBlue  && $A1 < $endColorLightBlue) {
            $bgColor = '#00bfff';
          }else if($A1 >= $startColorDarkBlue) {
            $bgColor = '#006080';
          }
      // END SPAN OF CONTROL -- Cek Success

      // BEGIN SPAN OF CONTROL KUALITAS
          $B1 = $H;

          $B2 = 0;

          $colorSOCKualitas = $B2;
          $startColorYellowSOCKualitas = $colorSOCKualitas*(80/100);
          $startColorGreenSOCKualitas = $colorSOCKualitas*(100/100);
          $startColorLightBlueSOCKualitas = $colorSOCKualitas*(120/100);
          $startColorDarkBlueSOCKualitas = $colorSOCKualitas*(150/100);

          $endColorRedSOCKualitas = $startColorYellowSOCKualitas;
          $endColorYellowSOCKualitas = $startColorGreenSOCKualitas;
          $endColorGreenSOCKualitas = $startColorLightBlueSOCKualitas;
          $endColorLightBlueSOCKualitas = $startColorDarkBlueSOCKualitas;
          $endColorDarkBlueSOCKualitas = 2*$colorSOCKualitas;

          if($B1 == 0) {
            $bgColorSOCKualitas = '#edf10e';
          }else if($B1 > 0) {
            $bgColorSOCKualitas = '#57c33c';
          }else if($B1 < 0) {
            $bgColorSOCKualitas = '#d72828';
          }
      // END SPAN OF CONTROL KUALITAS -- Cek Warning

      if ($E1 >= $E2) {
        $notes = 'Anda sudah memenuhi target';
        $alertClass = 'alert-success';
      }else{
        if ($C1 >= $C3) {
          if ($J == 0) {
            $Check = $I/100;
          }else{
            $Check = $J/100;
          }

          if ($Check >= $E2) {
            $C1 = round(($C1*$Reference1)/100);
            $perHari = round($C/$C1/$Remaining_Days);
            $notes = 'Anda harus menambah <strong>'.$perHari.'</strong> Incoming, dengan cara menyetorkan <strong>'.$perHari.'</strong> incoming per hari per DSR';
            $alertClass = 'alert-warning';
          }else{
            $reduction = round($C3 - $C1);
            if ($reduction == 0) {
              $notes = 'Anda sudah memenuhi Target';
              $alertClass = 'alert-success';
            }else{
              $notes = 'Anda harus menambahkan <strong>'.$reduction.'</strong> Incomming';
              $alertClass = 'alert-warning';
            }
          }
        }else{
          if ($C1 < $Reference2/100) {
            $reduction = round($C3 - $C1);
            if ($reduction == 0) {
              $notes = 'Anda sudah memenuhi Target';
              $alertClass = 'alert-success';
            }else{
              $notes = 'Anda harus menambahkan <strong>'.$reduction.'</strong> Incomming';
              $alertClass = 'alert-warning';
            }
          }else{
            $C1 = round(($C1*$Reference1)/100);
            $perHari = round($C/$C1/$Remaining_Days);
            $notes = 'Anda harus menambah <strong>'.$perHari.'</strong> Incoming, dengan cara menyetorkan <strong>'.$perHari.'</strong> incoming per hari per DSR';
            $alertClass = 'alert-warning';
          }
        }
      }

    }else{
      //BEGIN APPROVAL
        $bgColorApproval = '#808080';
        $startColorYellowApproval = 0;
        $startColorGreenApproval = 0;
        $startColorLightBlueApproval = 0;
        $startColorDarkBlueApproval = 0;

        $endColorRedApproval = 0;
        $endColorYellowApproval = 0;
        $endColorGreenApproval = 0;
        $endColorLightBlueApproval = 0;
        $endColorDarkBlueApproval = 0;

        $E1 = 0;
        $E10 = 0;
      // END APPROVAL

      //BEGIN APPROVAL KUALITAS
        $bgColorApprovalKualitas = '#808080';
        $startColorYellowApprovalKualitas = 0;
        $startColorGreenApprovalKualitas = 0;
        $startColorLightBlueApprovalKualitas = 0;
        $startColorDarkBlueApprovalKualitas = 0;

        $endColorRedApprovalKualitas = 0;
        $endColorYellowApprovalKualitas = 0;
        $endColorGreenApprovalKualitas = 0;
        $endColorLightBlueApprovalKualitas = 0;
        $endColorDarkBlueApprovalKualitas = 0;

        $E5 = 0;
      // END APPROVAL KUALITAS

      //BEGIN PRODUKSI
        $bgColorProduksi = '#808080';
        $startColorYellowProduksi = 0;
        $startColorGreenProduksi = 0;
        $startColorLightBlueProduksi = 0;
        $startColorDarkBlueProduksi = 0;

        $endColorRedProduksi = 0;
        $endColorYellowProduksi = 0;
        $endColorGreenProduksi = 0;
        $endColorLightBlueProduksi = 0;
        $endColorDarkBlueProduksi = 0;

        $C1 = 0;
      // END PRODUKSI

      //BEGIN PRODUKSI KUALITAS
        $bgColorProduksiKualitas = '#808080';
        $startColorYellowProduksiKualitas = 0;
        $startColorGreenProduksiKualitas = 0;
        $startColorLightBlueProduksiKualitas = 0;
        $startColorDarkBlueProduksiKualitas = 0;

        $endColorRedProduksiKualitas = 0;
        $endColorYellowProduksiKualitas = 0;
        $endColorGreenProduksiKualitas = 0;
        $endColorLightBlueProduksiKualitas = 0;
        $endColorDarkBlueProduksiKualitas = 0;

        $D1 = 0;
      // END PRODUKSI KUALITAS

      //BEGIN SPAN OF CONTROL
        $bgColor = '#808080';
        $startColorYellow = 0;
        $startColorGreen = 0;
        $startColorLightBlue = 0;
        $startColorDarkBlue = 0;

        $endColorRed = 0;
        $endColorYellow = 0;
        $endColorGreen = 0;
        $endColorLightBlue = 0;
        $endColorDarkBlue = 0;

        $A1 = 0;
      // END SPAN OF CONTROL

      //BEGIN SPAN OF CONTROL KUALITAS
        $bgColorSOCKualitas = '#808080';
        $startColorYellowSOCKualitas = 0;
        $startColorGreenSOCKualitas = 0;
        $startColorLightBlueSOCKualitas = 0;
        $startColorDarkBlueSOCKualitas = 0;

        $endColorRedSOCKualitas = 0;
        $endColorYellowSOCKualitas = 0;
        $endColorGreenSOCKualitas = 0;
        $endColorLightBlueSOCKualitas = 0;
        $endColorDarkBlueSOCKualitas = 0;

        $B1 = 0;
      // END SPAN OF CONTROL KUALITAS

      $alertClass = 'alert-success';
      $notes = 'ya';
    }
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
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-inverse">
      <div class="panel-heading">
          Grafik Approval
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div id="grafikApproval"></div>
          </div>
        </div>
        <!-- <div class="row">
          <div class="col-md-12">
            <div class="alert alert-default">
              <div id="accordion">
                <div class="card">
                  <hr>
                  <div class="card-body">
                    <strong>Approval</strong> adalah Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-inverse">
      <div class="panel-heading">
          Grafik Approval Kualitas
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div id="grafikApprovalKualitas"></div>
          </div>
        </div>
        <!-- <div class="row">
          <div class="col-md-12">
            <div class="alert alert-default">
              <div id="accordion">
                <div class="card">
                  <hr>
                  <div class="card-body">
                    <strong>Approval Kualitas</strong> adalah Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam assumenda repellat minus neque eveniet voluptatum laboriosam praesentium saepe nostrum architecto.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-inverse">
      <div class="panel-heading">
          Grafik Produksi
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div id="grafikProduksi"></div>
          </div>
        </div>
        <!-- <div class="row">
          <div class="col-md-12">
            <div class="alert alert-default">
              <div id="accordion">
                <div class="card">
                  <hr>
                  <div class="card-body">
                    <strong>Produksi</strong> adalah Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-inverse">
      <div class="panel-heading">
          Grafik Produksi Kualitas
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div id="grafikProduksiKualitas"></div>
          </div>
        </div>
        <!-- <div class="row">
          <div class="col-md-12">
            <div class="alert alert-default">
              <div id="accordion">
                <div class="card">
                  <hr>
                  <div class="card-body">
                    <strong>Produksi Kualitas</strong> adalah Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugiat, atque delectus odit facilis neque quod nemo.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-inverse">
      <div class="panel-heading">
          Grafik Sales Force
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div id="grafik"></div>
          </div>
        </div>
        <!-- <div class="row">
          <div class="col-md-12">
            <div class="alert alert-default">
              <div id="accordion">
                <div class="card">
                  <hr>
                  <div class="card-body">
                    <strong>Sales Force</strong> adalah Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam praesentium dolores consequuntur quae recusandae inventore eum.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-inverse">
      <div class="panel-heading">
          Grafik 
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div id="grafikSOCKualitas"></div>
          </div>
        </div>
        <!-- <div class="row">
          <div class="col-md-12">
            <div class="alert alert-default">
              <div id="accordion">
                <div class="card">
                  <hr>
                  <div class="card-body">
                    <strong>Sales Force Kualitas</strong> adalah Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae quisquam quam laboriosam! Nostrum.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="alert <?= $alertClass ?>" role="alert">
      <div class="row">
        <div class="col-md-2" style="font-size:5em; text-align: center; border-right:1px solid lightgray">
          <?php if ($alertClass == 'alert-success') :?>
            <i class="fa fa-thumbs-up fa-10x"></i>
          <?php else: ?>
            <i class="fa fa-exclamation-triangle fa-10x"></i>
          <?php endif; ?>
        </div>
        <div class="col-md-10">
          <?php if ($alertClass == 'alert-success') :?>
            <h4 class="alert-heading">Selamat !</h4>
          <?php else: ?>
            <h4 class="alert-heading">Peringatan !</h4>
          <?php endif; ?>
          <p><?= $notes ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- <div class="row">
  <div class="col-md-12">
    <div class="alert alert-success" role="alert">
      <div class="row">
        <div class="col-md-2" style="font-size:5em; text-align: center; border-right:1px solid lightgray">
            <i class="fa fa-exclamation-triangle fa-10x"></i>
        </div>
        <div class="col-md-10">
            <h4 class="alert-heading">Logic</h4>
          <p>Jika actual approval melebihi target approval maka selesai,<br> jika tidak maka cek apakah actual incoming lebih besar dari target incoming jika ya maka cek kebutuhan incoming,<br> jika approval rate spv nya kosong maka approval rate yang di pakai adalah approval rate nasional setelah cek kebutuhan incoming maka cek apakah kebutuhan incoming lebih besar dari target approval</p>
        </div>
      </div>
    </div>
  </div>
</div> -->
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
    <form method="post" action="<?php echo site_url(); ?>dashboard/update_password/<?php echo $this->session->userdata('username'); ?>">
      <div class="form-group has-feedback">
        <label>Password :</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <label>Retype Password :</label>
        <input type="password" name="retype_password" class="form-control" placeholder="Retype Passwrod">
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

<script>
  Highcharts.chart('grafikApproval', {

    plotOptions: {
        gauge: {
            wrap: false,
            dial: {
              radius: '95%',
            }
        },
        series: {
          animation: {
            duration: 2500
          },
          dataLabels: {
              enabled: true,
              borderRadius: 5,
              backgroundColor: 'rgba(252, 255, 197, 0.7)',
              borderWidth: 1,
              borderColor: '#AAA'
          }
        }
    },

    chart: {
        type: 'gauge',
        height: 300,
        // width: 300,
        // plotBackgroundColor: null,
        // plotBackgroundImage: '<?= base_url('public/images/logo-dika1.png') ?>',
        plotBackgroundImage: null,
        plotBackgroundColor: '<?= $bgColorApproval ?>',
        plotBorderWidth: 0,
        plotShadow: false
    },

    title: {
        text: 'Approval'
    },

    pane: {
        startAngle: -150,
        endAngle: 150,
        background: [{
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#FFF'],
                    [1, '#333']
                ]
            },
            borderWidth: 5,
            outerRadius: '109%'
        }, {
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#333'],
                    [1, '#FFF']
                ]
            },
            borderWidth: 5,
            outerRadius: '107%'
        }, {
            // default background
        }, {
            backgroundColor: '#DDD',
            borderWidth: 0,
            outerRadius: '105%',
            innerRadius: '103%'
        }]
    },

    // the value axis
    yAxis: {
        min: 0,
        max: <?= json_encode($endColorDarkBlueApproval) ?>,

        minorTickInterval: 'auto',
        minorTickWidth: 1,
        minorTickLength: 10,
        minorTickPosition: 'outside',
        minorTickColor: '#666',

        tickPixelInterval: 30,
        tickWidth: 2,
        tickPosition: 'outside',
        tickLength: 10,
        tickColor: '#666',
        labels: {
            step: 2,
            rotation: 'auto'
        },
        title: {
            text: ''
        },
        plotBands: [{
            from: 0,
            to: <?= json_encode($endColorRedApproval) ?>,
            color: '#d72828' // Red
        }, {
            from: <?= json_encode($startColorYellowApproval) ?>,
            to: <?= json_encode($endColorYellowApproval) ?>,
            color: '#edf10e' // Yellow
        }, {
            from: <?= json_encode($startColorGreenApproval) ?>,
            to: <?= json_encode($endColorGreenApproval) ?>,
            color: '#57c33c' // Green
        }, {
            from: <?= json_encode($startColorLightBlueApproval) ?>,
            to: <?= json_encode($endColorLightBlueApproval) ?>,
            color: '#00bfff' // Light Blue/DeepSkyBlue
        }, {
            from: <?= json_encode($startColorDarkBlueApproval) ?>,
            to: <?= json_encode($endColorDarkBlueApproval) ?>,
            color: '#006080' // Dark Blue
        }]
    },

    series: [{
        name: 'Actual App.MTD',
        data: [<?php echo json_encode($E1) ?>],
        tooltip: {
            valueSuffix: ' '
        },
        dataLabels:{
          align:'left'
        },
        overshoot: 1
    },{
        name: 'Run Rate MTD',
        data: [<?php echo json_encode($E10) ?>],
        tooltip: {
            valueSuffix: ' '
        },
        dataLabels:{
          align:'right'
        },
        overshoot: 1
    }],
    credits: {
        enabled: false
    },

  });

  Highcharts.chart('grafikApprovalKualitas', {

    plotOptions: {
        gauge: {
            wrap: false,
            dial: {
              radius: '95%',
            }
        },
        series: {
          animation: {
            duration: 2500
          },
          dataLabels: {
              enabled: true,
              borderRadius: 5,
              backgroundColor: 'rgba(252, 255, 197, 0.7)',
              borderWidth: 1,
              borderColor: '#AAA'
          }
        }
    },

    chart: {
        type: 'gauge',
        height: 300,
        // width: 300,
        // plotBackgroundColor: null,
        // plotBackgroundImage: '<?= base_url('public/images/logo-dika1.png') ?>',
        plotBackgroundImage: null,
        plotBackgroundColor: '<?= $bgColorApprovalKualitas ?>',
        plotBorderWidth: 0,
        plotShadow: false
    },

    title: {
        text: 'Approval Kualitas'
    },

    pane: {
        startAngle: -150,
        endAngle: 150,
        background: [{
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#FFF'],
                    [1, '#333']
                ]
            },
            borderWidth: 5,
            outerRadius: '109%'
        }, {
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#333'],
                    [1, '#FFF']
                ]
            },
            borderWidth: 5,
            outerRadius: '107%'
        }, {
            // default background
        }, {
            backgroundColor: '#DDD',
            borderWidth: 0,
            outerRadius: '105%',
            innerRadius: '103%'
        }]
    },

    // the value axis
    yAxis: {
        min: 0,
        max: <?= json_encode($endColorDarkBlueApprovalKualitas) ?>,

        minorTickInterval: 'auto',
        minorTickWidth: 1,
        minorTickLength: 10,
        minorTickPosition: 'outside',
        minorTickColor: '#666',

        tickPixelInterval: 30,
        tickWidth: 2,
        tickPosition: 'outside',
        tickLength: 10,
        tickColor: '#666',
        labels: {
            step: 2,
            rotation: 'auto'
        },
        title: {
            text: ''
        },
        plotBands: [{
            from: 0,
            to: <?= json_encode($endColorRedApprovalKualitas) ?>,
            color: '#d72828' // Red
        }, {
            from: <?= json_encode($startColorYellowApprovalKualitas) ?>,
            to: <?= json_encode($endColorYellowApprovalKualitas) ?>,
            color: '#edf10e' // Yellow
        }, {
            from: <?= json_encode($startColorGreenApprovalKualitas) ?>,
            to: <?= json_encode($endColorGreenApprovalKualitas) ?>,
            color: '#57c33c' // Green
        }, {
            from: <?= json_encode($startColorLightBlueApprovalKualitas) ?>,
            to: <?= json_encode($endColorLightBlueApprovalKualitas) ?>,
            color: '#00bfff' // Light Blue/DeepSkyBlue
        }, {
            from: <?= json_encode($startColorDarkBlueApprovalKualitas) ?>,
            to: <?= json_encode($endColorDarkBlueApprovalKualitas) ?>,
            color: '#006080' // Dark Blue
        }]
    },

    series: [{
        name: 'Actual Approval Rate MTD',
        data: [<?php echo json_encode($E5) ?>],
        tooltip: {
            valueSuffix: ' '
        },
        overshoot: 1
    }],
    credits: {
        enabled: false
    },

  });

  Highcharts.chart('grafikProduksi', {

      plotOptions: {
          gauge: {
              wrap: false,
              dial: {
                radius: '95%',
              }
          },
          series: {
            animation: {
              duration: 2500
            },
            dataLabels: {
                enabled: true,
                borderRadius: 5,
                backgroundColor: 'rgba(252, 255, 197, 0.7)',
                borderWidth: 1,
                borderColor: '#AAA'
            }
          }
      },

      chart: {
          type: 'gauge',
          height: 300,
          // width: 300,
          // plotBackgroundColor: null,
          // plotBackgroundImage: '<?= base_url('public/images/logo-dika1.png') ?>',
          plotBackgroundImage: null,
          plotBackgroundColor: '<?= $bgColorProduksi ?>',
          plotBorderWidth: 0,
          plotShadow: false
      },

      title: {
          text: 'Produksi'
      },

      pane: {
          startAngle: -150,
          endAngle: 150,
          background: [{
              backgroundColor: {
                  linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                  stops: [
                      [0, '#FFF'],
                      [1, '#333']
                  ]
              },
              borderWidth: 5,
              outerRadius: '109%'
          }, {
              backgroundColor: {
                  linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                  stops: [
                      [0, '#333'],
                      [1, '#FFF']
                  ]
              },
              borderWidth: 5,
              outerRadius: '107%'
          }, {
              // default background
          }, {
              backgroundColor: '#DDD',
              borderWidth: 0,
              outerRadius: '105%',
              innerRadius: '103%'
          }]
      },

      // the value axis
      yAxis: {
          min: 0,
          max: <?= json_encode($endColorDarkBlueProduksi) ?>,

          minorTickInterval: 'auto',
          minorTickWidth: 1,
          minorTickLength: 10,
          minorTickPosition: 'outside',
          minorTickColor: '#666',

          tickPixelInterval: 30,
          tickWidth: 2,
          tickPosition: 'outside',
          tickLength: 10,
          tickColor: '#666',
          labels: {
              step: 2,
              rotation: 'auto'
          },
          title: {
              text: ''
          },
          plotBands: [{
              from: 0,
              to: <?= json_encode($endColorRedProduksi) ?>,
              color: '#d72828' // Red
          }, {
              from: <?= json_encode($startColorYellowProduksi) ?>,
              to: <?= json_encode($endColorYellowProduksi) ?>,
              color: '#edf10e' // Yellow
          }, {
              from: <?= json_encode($startColorGreenProduksi) ?>,
              to: <?= json_encode($endColorGreenProduksi) ?>,
              color: '#57c33c' // Green
          }, {
              from: <?= json_encode($startColorLightBlueProduksi) ?>,
              to: <?= json_encode($endColorLightBlueProduksi) ?>,
              color: '#00bfff' // Light Blue/DeepSkyBlue
          }, {
              from: <?= json_encode($startColorDarkBlueProduksi) ?>,
              to: <?= json_encode($endColorDarkBlueProduksi) ?>,
              color: '#006080' // Dark Blue
          }]
      },

      series: [{
          name: 'Actual Inc. p. Day p. DSR',
          data: [<?php echo json_encode($C1) ?>],
          tooltip: {
              valueSuffix: ' '
          },
          overshoot: 1
      }],
      credits: {
          enabled: false
      },

  });

  Highcharts.chart('grafikProduksiKualitas', {

    plotOptions: {
        gauge: {
            wrap: false,
            dial: {
              radius: '95%',
            }
        },
        series: {
          animation: {
            duration: 2500
          },
          dataLabels: {
              enabled: true,
              borderRadius: 5,
              backgroundColor: 'rgba(252, 255, 197, 0.7)',
              borderWidth: 1,
              borderColor: '#AAA'
          }
        }
    },

    chart: {
        type: 'gauge',
        height: 300,
        // width: 300,
        // plotBackgroundColor: null,
        // plotBackgroundImage: '<?= base_url('public/images/logo-dika1.png') ?>',
        plotBackgroundImage: null,
        plotBackgroundColor: '<?= $bgColorProduksiKualitas ?>',
        plotBorderWidth: 0,
        plotShadow: false
    },

    title: {
        text: 'Produksi Kualitas'
    },

    pane: {
        startAngle: -150,
        endAngle: 150,
        background: [{
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#FFF'],
                    [1, '#333']
                ]
            },
            borderWidth: 5,
            outerRadius: '109%'
        }, {
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#333'],
                    [1, '#FFF']
                ]
            },
            borderWidth: 5,
            outerRadius: '107%'
        }, {
            // default background
        }, {
            backgroundColor: '#DDD',
            borderWidth: 0,
            outerRadius: '105%',
            innerRadius: '103%'
        }]
    },

    // the value axis
    yAxis: {
        min: 0,
        max: <?= json_encode($endColorDarkBlueProduksiKualitas) ?>,

        minorTickInterval: 'auto',
        minorTickWidth: 1,
        minorTickLength: 10,
        minorTickPosition: 'outside',
        minorTickColor: '#666',

        tickPixelInterval: 30,
        tickWidth: 2,
        tickPosition: 'outside',
        tickLength: 10,
        tickColor: '#666',
        labels: {
            step: 2,
            rotation: 'auto'
        },
        title: {
            text: ''
        },
        plotBands: [{
            from: 0,
            to: <?= json_encode($endColorRedProduksiKualitas) ?>,
            color: '#d72828' // Red
        }, {
            from: <?= json_encode($startColorYellowProduksiKualitas) ?>,
            to: <?= json_encode($endColorYellowProduksiKualitas) ?>,
            color: '#edf10e' // Yellow
        }, {
            from: <?= json_encode($startColorGreenProduksiKualitas) ?>,
            to: <?= json_encode($endColorGreenProduksiKualitas) ?>,
            color: '#57c33c' // Green
        }, {
            from: <?= json_encode($startColorLightBlueProduksiKualitas) ?>,
            to: <?= json_encode($endColorLightBlueProduksiKualitas) ?>,
            color: '#00bfff' // Light Blue/DeepSkyBlue
        }, {
            from: <?= json_encode($startColorDarkBlueProduksiKualitas) ?>,
            to: <?= json_encode($endColorDarkBlueProduksiKualitas) ?>,
            color: '#006080' // Dark Blue
        }]
    },

    series: [{
        name: 'Data',
        data: [<?php echo json_encode($D1) ?>],
        tooltip: {
            valueSuffix: ' '
        },
        overshoot: 1
    }],
    credits: {
        enabled: false
    },

  });

  Highcharts.chart('grafik', {

    plotOptions: {
        gauge: {
            wrap: false,
            dial: {
              radius: '95%',
            }
        },
        series: {
          animation: {
            duration: 2500
          },
          dataLabels: {
              enabled: true,
              borderRadius: 5,
              backgroundColor: 'rgba(252, 255, 197, 0.7)',
              borderWidth: 1,
              borderColor: '#AAA'
          }
        }
    },

    chart: {
        type: 'gauge',
        height: 300,
        // width: 300,
        // plotBackgroundColor: null,
        // plotBackgroundImage: '<?= base_url('public/images/logo-dika1.png') ?>',
        plotBackgroundImage: null,
        plotBackgroundColor: '<?= $bgColor ?>',
        plotBorderWidth: 0,
        plotShadow: false
    },

    title: {
        text: 'Sales Force'
    },

    pane: {
        startAngle: -150,
        endAngle: 150,
        background: [{
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#FFF'],
                    [1, '#333']
                ]
            },
            borderWidth: 5,
            outerRadius: '109%'
        }, {
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#333'],
                    [1, '#FFF']
                ]
            },
            borderWidth: 5,
            outerRadius: '107%'
        }, {
            // default background
        }, {
            backgroundColor: '#DDD',
            borderWidth: 0,
            outerRadius: '105%',
            innerRadius: '103%'
        }]
    },

    // the value axis
    yAxis: {
        min: 0,
        max: <?= json_encode($endColorDarkBlue) ?>,

        minorTickInterval: 'auto',
        minorTickWidth: 1,
        minorTickLength: 10,
        minorTickPosition: 'outside',
        minorTickColor: '#666',

        tickPixelInterval: 30,
        tickWidth: 2,
        tickPosition: 'outside',
        tickLength: 10,
        tickColor: '#666',
        labels: {
            step: 2,
            rotation: 'auto'
        },
        title: {
            text: ''
        },
        plotBands: [{
            from: 0,
            to: <?= json_encode($endColorRed) ?>,
            color: '#d72828' // Red
        }, {
            from: <?= json_encode($startColorYellow) ?>,
            to: <?= json_encode($endColorYellow) ?>,
            color: '#edf10e' // Yellow
        }, {
            from: <?= json_encode($startColorGreen) ?>,
            to: <?= json_encode($endColorGreen) ?>,
            color: '#57c33c' // Green
        }, {
            from: <?= json_encode($startColorLightBlue) ?>,
            to: <?= json_encode($endColorLightBlue) ?>,
            color: '#00bfff' // Light Blue/DeepSkyBlue
        }, {
            from: <?= json_encode($startColorDarkBlue) ?>,
            to: <?= json_encode($endColorDarkBlue) ?>,
            color: '#006080' // Dark Blue
        }]
    },

    series: [{
        name: 'Actual DSR',
        data: [<?php echo json_encode($A1) ?>],
        tooltip: {
            valueSuffix: ' '
        },
        overshoot: 1
    }],
    credits: {
        enabled: false
    },

  });

  Highcharts.chart('grafikSOCKualitas', {

    plotOptions: {
      gauge: {
          wrap: false,
          dial: {
            radius: '95%',
          }
      },
      series: {
        animation: {
          duration: 2500
        },
        dataLabels: {
            enabled: true,
            borderRadius: 5,
            backgroundColor: 'rgba(252, 255, 197, 0.7)',
            borderWidth: 1,
            borderColor: '#AAA'
        }
      }
    },

    chart: {
      type: 'gauge',
      height: 300,
      // width: 300,
      // plotBackgroundColor: null,
      // plotBackgroundImage: '<?= base_url('public/images/logo-dika1.png') ?>',
      plotBackgroundImage: null,
      plotBackgroundColor: '<?= $bgColorSOCKualitas ?>',
      plotBorderWidth: 0,
      plotShadow: false
    },

    title: {
      text: 'Sales Force Kualitas'
    },

    pane: {
      startAngle: -150,
      endAngle: 150,
      background: [{
          backgroundColor: {
              linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
              stops: [
                  [0, '#FFF'],
                  [1, '#333']
              ]
          },
          borderWidth: 5,
          outerRadius: '109%'
      }, {
          backgroundColor: {
              linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
              stops: [
                  [0, '#333'],
                  [1, '#FFF']
              ]
          },
          borderWidth: 5,
          outerRadius: '107%'
      }, {
          // default background
      }, {
          backgroundColor: '#DDD',
          borderWidth: 0,
          outerRadius: '105%',
          innerRadius: '103%'
      }]
    },

    // the value axis
    yAxis: {
      min: 0,
      max: <?= json_encode($endColorDarkBlueSOCKualitas) ?>,

      minorTickInterval: 'auto',
      minorTickWidth: 1,
      minorTickLength: 10,
      minorTickPosition: 'outside',
      minorTickColor: '#666',

      tickPixelInterval: 30,
      tickWidth: 2,
      tickPosition: 'outside',
      tickLength: 10,
      tickColor: '#666',
      labels: {
          step: 2,
          rotation: 'auto'
      },
      title: {
          text: ''
      },
      plotBands: [{
          from: 0,
          to: <?= json_encode($endColorRedSOCKualitas) ?>,
          color: '#d72828' // Red
      }, {
          from: <?= json_encode($startColorYellowSOCKualitas) ?>,
          to: <?= json_encode($endColorYellowSOCKualitas) ?>,
          color: '#edf10e' // Yellow
      }, {
          from: <?= json_encode($startColorGreenSOCKualitas) ?>,
          to: <?= json_encode($endColorGreenSOCKualitas) ?>,
          color: '#57c33c' // Green
      }, {
          from: <?= json_encode($startColorLightBlueSOCKualitas) ?>,
          to: <?= json_encode($endColorLightBlueSOCKualitas) ?>,
          color: '#00bfff' // Light Blue/DeepSkyBlue
      }, {
          from: <?= json_encode($startColorDarkBlueSOCKualitas) ?>,
          to: <?= json_encode($endColorDarkBlueSOCKualitas) ?>,
          color: '#006080' // Dark Blue
      }]
    },

    series: [{
      name: 'Zero Inc.DSR',
      data: [<?php echo json_encode($B1) ?>],
      tooltip: {
          valueSuffix: ' '
      },
      overshoot: 1
    }],
    credits: {
      enabled: false
    },

  });
</script>