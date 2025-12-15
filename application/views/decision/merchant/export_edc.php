<?php
header("Content-type: application/ms-excel");
header("Content-Disposition: attachment; filename=Detail Achievement EDC.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<?php

?>

<table>
    <tr style="background-color:#193B58;">
        <th style="color:white; font-size:10pt; font-weight:100">Sales Code</th>
        <th style="color:white; font-size:10pt; font-weight:100">Sales Name</th>
        <th style="color:white; font-size:10pt; font-weight:100">SPV Code</th>
        <th style="color:white; font-size:10pt; font-weight:100">SPV Name</th>
        <th style="color:white; font-size:10pt; font-weight:100">ASM Code</th>
        <th style="color:white; font-size:10pt; font-weight:100">ASM Name</th>
        <th style="color:white; font-size:10pt; font-weight:100">RSM Code</th>
        <th style="color:white; font-size:10pt; font-weight:100">RSM Name</th>
        <th style="color:white; font-size:10pt; font-weight:100">BSH Code</th>
        <th style="color:white; font-size:10pt; font-weight:100">BSH Name</th>
        <th style="color:white; font-size:10pt; font-weight:100">Branch</th>
        <th style="color:white; font-size:10pt; font-weight:100">Merchant Name</th>
        <th style="color:white; font-size:10pt; font-weight:100">Jenis Approval BCA</th>
        <th style="color:white; font-size:10pt; font-weight:100">Week</th>
        <th style="color:white; font-size:10pt; font-weight:100">Group Fasilitas</th>
    </tr>
    <?php
    foreach ($query as $item) {
        $namaMerchant = $item->Merchant_Name;
        $explodeMerchant = explode(" ", $namaMerchant);
        $totalString = count($explodeMerchant);
        if ($totalString > 1) {

            $visibleString = substr("$explodeMerchant[1]", 0, 2);
            $totalMask = strlen($explodeMerchant[1]) - 2;
            $namaMerchant = $explodeMerchant[0] . " " . $visibleString . str_repeat("*", ($totalMask >= 0) ?  $totalMask : 0);
        } else if ($totalString == 1) {
            $visibleString = substr("$explodeMerchant[0]", 0, 2);
            $totalMask = strlen($explodeMerchant[0]) - 2;
            $namaMerchant =  $visibleString . str_repeat("*", ($totalMask >= 0) ?  $totalMask : 0);
        }
    ?>
    <tr>
        <td> <?php echo $item->Sales_Code; ?></td>
        <td> <?php echo $item->Sales_Name; ?></td>
        <td><?php echo $item->SPV_Code; ?></td>
        <td><?php echo $item->SPV_Name; ?></td>
        <td><?php echo $item->ASM_Code; ?></td>
        <td><?php echo $item->ASM_Name; ?></td>
        <td><?php echo $item->RSM_Code; ?></td>
        <td><?php echo $item->RSM_Name; ?></td>
        <td><?php echo $item->BSH_Code; ?></td>
        <td><?php echo $item->BSH_Name; ?></td>
        <td><?php echo $item->Branch; ?></td>
        <td><?php echo $namaMerchant; ?></td>
        <td><?php echo $item->Facilities_Type; ?></td>
        <td><?php echo $item->Week; ?></td>
        <td><?php echo $item->Facilities_Type2; ?></td>

    </tr>
    <?php } ?>
</table>