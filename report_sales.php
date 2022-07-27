<?php
    include_once'db/connect_db.php';
    session_start();
    if($_SESSION['username']==""){
        header('location:index.php');
    }else{
        if($_SESSION['role']=="Admin"){
          include_once 'inc/header_all.php';
        }else{
            include_once 'inc/header_all_operator.php';
        }
    }
    error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
</head>
<body>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
          <form action="" method="POST" autocomplete="off">
            <div class="box-header with-border">
                <h3 class="box-title">Out of Date : <?php echo $_POST['date_1']?>
                </h3>
                <h3 class="box-title">Till date : <?php echo $_POST['date_2'] ?>
                </h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" id="datepicker_1" name="date_1" data-date-format="yyyy-mm-dd">
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" id="datepicker_2" name="date_2" data-date-format="yyyy-mm-dd">
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <input type="submit" name="date_filter" value="Check" class="btn btn-success btn-sm">
                </div>
                <br>
              </div>
                  <?php
                    $select = $pdo->prepare("SELECT sum(total) as total, count(invoice_id) as invoice FROM tbl_invoice
                    WHERE order_date BETWEEN :fromdate AND :todate");
                    $select->bindParam(':fromdate', $_POST['date_1']);
                    $select->bindParam(':todate', $_POST['date_2']);
                    $select->execute();

                    $row = $select->fetch(PDO::FETCH_OBJ);

                    $total = $row->total;

                    $invoice = $row->invoice;


                  ?>

              <div class="row">
                <div class="col-md-offset-2 col-md-4 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Total Transactions</span>
                      <span class="info-box-number"><?php echo $invoice; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-offset-1 col-md-5 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Total Income</span>
                      <span class="info-box-number">GH₵<?php echo number_format($total,0) ; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->


                <!-- /.col -->
              </div>

              <!--- Transaction Table -->
          <div style="container-fluid mt-5">
                  <table class="table table-striped container" id="mySalesReport" class="display" style="width:100%" style="margin-top:50px;">
                      <thead>
                          <tr>
                            <th>SalesPerson</th>
                            <th>Barber</th>
                            <th>Date</th>
                            <th>Fee(GHS)</th>
                          </tr>
                      </thead>
                      <tbody>
                      <?php
                            $select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate");
                            $select->bindParam(':fromdate', $_POST['date_1']);
                            $select->bindParam(':todate', $_POST['date_2']);

                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)){
                            ?>
                                <tr>
                                <td class="text-uppercase"><?php echo $row->cashier_name; ?></td>
                                <td class="text-uppercase"><?php echo $row->username; ?></td>
                                <td><?php echo $row->order_date; ?></td>
                                <td><?php echo number_format($row->total); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                      </tbody>
                  </table>
              </div>

              <!-- Transaction Graphic -->
              <?php
                  $select = $pdo->prepare("SELECT order_date, sum(total) as price FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate
                  GROUP BY order_date");
                  $select->bindParam(':fromdate', $_POST['date_1']);
                  $select->bindParam(':todate', $_POST['date_2']);
                  $select->execute();
                  $total=[];
                  $date=[];
                  while($row=$select->fetch(PDO::FETCH_ASSOC)){
                      extract($row);
                      $total[]=$price;
                      $date[]=$order_date;

                  }
                  // echo json_encode($total);
              ?>

              <?php
                  $select = $pdo->prepare("SELECT product_name, sum(qty) as q FROM tbl_invoice_detail WHERE order_date BETWEEN :fromdate AND :todate
                  GROUP BY product_id");
                  $select->bindParam(':fromdate', $_POST['date_1']);
                  $select->bindParam(':todate', $_POST['date_2']);
                  $select->execute();
                  $pname=[];
                  $qty=[];
                  while($row=$select->fetch(PDO::FETCH_ASSOC)){
                      extract($row);
                      $pname[]=$product_name;
                      $qty[]=$q;

                  }
                  // echo json_encode($total);
              ?>

          </div>

          </form>
        </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
                  
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script> 

     <!-- datepicker js -->
  <script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap datepicker -->
  <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>  
    
    
  <script>
    //Date picker
    $('#datepicker_1').datepicker({
      autoclose: true
    });
    //Date picker
    $('#datepicker_2').datepicker({
      autoclose: true
    });
  </script>


  <script type="text/javascript">           
    $(document).ready( function(){
      $('#mySalesReport').DataTable({
        dom: 'Bfrtip',
        buttons: [
                  'copy',
                  'excel',
                  'csv',
                  'pdf',
                   ],
      });
    });
  </script>
              
  
  <script>
      var ctx = document.getElementById('myChart');
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: <?php echo json_encode($date); ?>,
              datasets: [{
                  label: 'Total Income',
                  data: <?php echo json_encode($total); ?>,
                  backgroundColor: 'rgb(13, 192, 58)',
                  borderColor: 'rgb(32, 204, 75)',
                  borderWidth: 1
              }]
          },
          options: {}
      });
  </script>
  <style>
      .color{
          background-Color: rgb(120,102,102);
      }
  </style>


  <script>
      var ctx = document.getElementById('myBestSellItem');
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: <?php echo json_encode($pname); ?>,
              datasets: [{
                  label: 'Total Produk Terjual',
                  data: <?php echo json_encode($qty); ?>,
                  backgroundColor: 'rgb(120,112,175)',
                  borderColor: 'rgb(255,255,255)',
                  borderWidth: 1
              }]
          },
          options: {}
      });
  </script>

 <?php
    include_once 'inc/footer_all.php';
 ?>
</body>
</html>
  <!-- Content Wrapper. Contains page content -->