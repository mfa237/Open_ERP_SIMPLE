<?php
if(isset($_GET['purchase_id'])){
	$is_return=$_GET['is_return'];
	$purchase_id = $_GET['purchase_id'];
	$ref_no = $_GET['ref_no'];
	if($is_return==0){
		$update = DB::UPDATE(DB_PREFIX.$_SESSION['co_prefix'].'purchase',array(
								'is_return' => '0'), "purchase_id =%s", $purchase_id );
		$update = DB::UPDATE(DB_PREFIX.$_SESSION['co_prefix'].'journal_vouchers',array(
								'active' => '1'), "voucher_ref_no =%s", $ref_no );						
	}
}

?>


<?php
$text='"Are you sure to put this purchase to delevered?"';
//Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered data-table');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('Purchase ID', '', 'header');
$tbl->addCell('Creditor', '', 'header');
$tbl->addCell('Vehicle #', '', 'header');
$tbl->addCell('Party Name', '', 'header');
$tbl->addCell('Party Address', '', 'header');
$tbl->addCell('Order Date', '', 'header');
$tbl->addCell('Amount', '', 'header');
$tbl->addCell('Payment Paid', '', 'header');
$tbl->addCell('Actions', '', 'header');
$tbl->addTSection('tbody');
?>

<?php
$sql = 'SELECT p.*, pd.`customer_name`
FROM
    sa_test_purchase_detail pd
    INNER JOIN sa_test_purchase p 
        ON (pd.`purchase_id` = p.`purchase_id`)
        WHERE p.`is_return`=1
        GROUP BY p.`purchase_id` ORDER BY p.`purchase_id` ASC';
$voucher_expense = DB::query($sql);
foreach($voucher_expense as $list_voucher_expense) { 
$tbl->addRow();
$tbl->addCell($list_voucher_expense['purchase_id']);
$tbl->addCell($list_voucher_expense['customer_name']);
$tbl->addCell($list_voucher_expense['vehicle_no']);
$tbl->addCell($list_voucher_expense['mill_name']);
$tbl->addCell($list_voucher_expense['mill_address']);
$tbl->addCell($list_voucher_expense['order_date']);
$tbl->addCell($list_voucher_expense['total_amount']);
if($list_voucher_expense['payment_paid']==1){
$tbl->addCell("<span style='color:green;'>Yes<span>");	
} else {
	$tbl->addCell("<span style='color:red;'>NO<span>");	
}
$tbl->addCell("<a class='pull btn btn-default btn-xs' href ='?route=modules/purchase/view_purchase_detail&purchase_id=".$list_voucher_expense['purchase_id']."'>Detail&nbsp;<span class='glyphicon glyphicon-edit'></span></a>&nbsp;
<a class='pull btn btn-success btn-xs' href ='?route=modules/purchase/return_purchase&purchase_id=".$list_voucher_expense['purchase_id']."&is_return=0&ref_no=".$list_voucher_expense['ref_no']."' onclick='return confirm(".$text.");'>Push Delevered&nbsp;<span class='glyphicon glyphicon-edit'></span></a>  ");
}
			  

?>



 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Purchase Orders
            <small>List of All Purchase .</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">List OF All Return Purchase</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">All sale orders</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
				<?php  echo $tbl->display(); ?>
            </div><!-- /.box-body -->
            <div class="box-footer">
             
            </div>
          </div><!-- /.box -->
		</section> 
		
