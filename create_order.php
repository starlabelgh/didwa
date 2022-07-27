<?php
   include_once 'db/connect_db.php';
   session_start();
   if($_SESSION['username']==""){
     include_once 'inc/404.php';
   }else{
     if($_SESSION['role']=="Admin"){
       include_once 'inc/header_all.php';
     }else{
         include_once 'inc/header_all_operator.php';
     }
   }


    error_reporting(0);
    date_default_timezone_set('Africa/Accra');

    function fill_product($pdo){
      $output= '';

      $select = $pdo->prepare("SELECT * FROM tbl_product");
      $select->execute();
      $result = $select->fetchAll();

      foreach($result as $row){
        $output.='<option value="'.$row['product_id'].'">'.$row["product_code"].'</option>';
      }

      return $output;
    }

//Function to display records from the database in a dropdown list
      function fill_barber($pdo){
      $barberlist = '';

      $select = $pdo->prepare("SELECT username FROM tbl_user");
      $select->execute();
      $result = $select->fetchAll();

      foreach($result as $row){
        $barberlist.='<option>'.$row['username'].'</option>';
      }
      return $barberlist;
    }
  
    $username = '';
    if(isset($_POST['save_order'])){
      $cashier_name = $_POST['cashier_name'];
      $username = $_POST['username'];
      $order_date = date("Y-m-d",strtotime($_POST['orderdate']));
      $order_time = date("H:i", strtotime($_POST['timeorder']));
      $total = $_POST['total'];
      $paid = $_POST['paid'];
      $due = $_POST['due'];
      $delivery = $_POST['delivery'];


      $arr_product_id =  $_POST['productid'];
      $arr_product_code = $_POST['productcode'];
      $arr_product_name = $_POST['productname'];
      $arr_product_stock = $_POST['productstock'];
      $arr_product_qty = $_POST['quantity'];
      $arr_product_satuan = $_POST['productsatuan'];
      $arr_product_price = $_POST['productprice'];
      $arr_product_total =  $_POST['producttotal'];

      if($arr_product_code == ""){
        echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "Please Fill in the Transaction Form", "warning", {
              button: "Continue",
                  });
              });
              </script>';
      }else{


        $insert = $pdo->prepare("INSERT INTO tbl_invoice(cashier_name, username, order_date, time_order, total, paid, due, delivery)
        values(:name, :username, :orderdate, :timeorder, :total, :paid, :due, :delivery)");

        $insert->bindParam(':name', $cashier_name);
        $insert->bindParam(':username', $username);
        $insert->bindParam(':orderdate',  $order_date);
        $insert->bindParam(':timeorder',  $order_time);
        $insert->bindParam(':total', $total);
        $insert->bindParam(':paid', $paid);
        $insert->bindParam(':due', $due);
        $insert->bindParam(':delivery', $delivery);

        $insert->execute();


        $invoice_id = $pdo->lastInsertId();
        if($invoice_id!=null){
          for($i=0; $i<count($arr_product_id); $i++){

            $rem_qty = $arr_product_stock[$i] - $arr_product_qty[$i];

            if($rem_qty<0){
              echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Warning", "Enter Purchase Amount", "warning", {
                    button: "Continue",
                        });
                    });
                    </script>';
            }else{
              $update = $pdo->prepare("UPDATE tbl_product SET stock = '$rem_qty' WHERE product_id='".$arr_product_id[$i]."'");
              $update->execute();
            }

            $insert = $pdo->prepare("INSERT INTO tbl_invoice_detail(invoice_id, product_id, product_code, product_name, qty, product_satuan, price, total, order_date)
            values(:invid, :productid, :productcode, :productname, :qty, :productsatuan, :price, :total, :orderdate)");

            $insert->bindParam(':invid',  $invoice_id);
            $insert->bindParam(':productid',   $arr_product_id[$i]);
            $insert->bindParam(':productcode',   $arr_product_code[$i]);
            $insert->bindParam(':productname', $arr_product_name[$i]);
            $insert->bindParam(':qty', $arr_product_qty[$i]);
            $insert->bindParam(':productsatuan', $arr_product_satuan[$i]);
            $insert->bindParam(':price',  $arr_product_price[$i]);
            $insert->bindParam(':total',   $arr_product_total[$i]);
            $insert->bindParam(':orderdate',  $order_date);

            $insert->execute();

          }
          echo '<script>location.href="order.php";</script>';

        }
      }

    }

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Transaction
      </h1>
      <hr>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <div class="box box-success">
          <form action="" method="POST">

            <div class="box-body">
              <div class="col-md-3">
                <div class="form-group" style="margin-top:10px;">
                  <label>Sales Person</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-user"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="cashier_name" value="<?php echo $_SESSION['username']; ?>" readonly>
                  </div>
                  <!-- /.input group -->
                </div>
              </div>

            <div class="box-body">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Delivery Person</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-user"></i>
                    </div>
                    <div>
                    <select class="form-control pull-right" name="username" value="<?php echo $username;?>">
                      <?php
                      echo fill_barber($pdo);
                      ?>
                    </select>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label>Transaction date</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="orderdate" value="<?php echo date("d-m-Y");?>" readonly
                    data-date-format="yyyy-mm-dd">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Transaction Hours</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="timeorder" value="<?php echo date('H:i') ?>" readonly>
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
            </div>

            <div class="box-body">
              <div class="col-md-12" style="overflow-x:auto;">
                <table class="table table-border" id="myOrder">
                  <thead>
                      <tr>
                          <th></th>
                          <th>Code</th>
                          <th>Name</th>
                          <th>Stock</th>
                          <th>Price</th>
                          <th>Quantity</th> <!--Changed from Number of Persons to Quantity-->
                          <th>Unit</th>
                          <th>Total</th>
                          <th>
                            <button type="button" name="addOrder" class="btn btn-success btn-sm btn_addOrder" required><span>
                              <i class="fa fa-plus"></i>
                            </span></button>
                          </th>
                      </tr>

                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-offset-1 col-md-10">
                <div class="form-group">
                  <label>Total</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>GH₵</span>
                    </div>
                    <input type="text" class="form-control pull-right" name="total" id="total" required readonly>
                  </div>
                  <!-- /.input group -->
                </div>
                <div class="form-group">
                  <label>Money Received</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>GH₵</span>
                    </div>
                    <input type="text" class="form-control pull-right" name="paid" id="paid" required>
                  </div>
                  <!-- /.input group -->
                </div>
                <div class="form-group">
                  <label>Change money</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>GH₵ <?php echo $_SESSION['invoice_id']; ?></span>
                    </div>
                    <input type="text" class="form-control pull-right" name="due" id="due" required readonly>
                  </div>
                  <!-- /.input group -->
                </div>  
                    <!--Delivery fee-->
                <div class="form-group">
                  <label>Delivery Fee</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span>GH₵</span>
                    </div>
                    <input type="text" class="form-control pull-right" name="delivery" id="delivery" placeholder="Enter delivery fee" required>
                  </div>
                  <!-- /.end of delivery fee input group -->
                </div>
                  
                </div>
              </div>
            </div>

            <div class="box-footer" align="center">
              <input type="submit" name="save_order" value="Save Transaction" class="btn btn-success">
              <a href="order.php" class="btn btn-warning">Back</a>
            </div>
          </form>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script>
  //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })

    $(document).ready(function(){
      $(document).on('click','.btn_addOrder', function(){
        var html='';
        html+='<tr>';
        html+='<td><input type="hidden" class="form-control productcode" name="productcode[]" readonly></td>';
        html+='<td><select class="form-control productid" name="productid[]" style="width:15  0px;" required><option value="">--Select Product--</option><?php
        echo fill_product($pdo)?></select></td>';
        html+='<td><input type="text" class="form-control productname" style="width:200px;" name="productname[]" readonly></td>';
        html+='<td><input type="text" class="form-control productstock" style="width:50px;" name="productstock[]" readonly></td>';
        html+='<td><input type="text" class="form-control productprice" style="width:100px;" name="productprice[]" readonly></td>';
        html+='<td><input type="number" min="1" max="50" class="form-control quantity_product" style="width:100px;" name="quantity[]" required></td>';
        html+='<td><input type="text" class="form-control productsatuan" style="width:100px;" name="productsatuan[]" readonly></td>';
        html+='<td><input type="text" class="form-control producttotal" style="width:150px;" name="producttotal[]" readonly></td>';
        html+='<td><button type="button" name="remove" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-remove"></i></button></td>'

        $('#myOrder').append(html);

        $('.productid').on('change', function(e){
          var productid = this.value;
          var tr=$(this).parent().parent();
          $.ajax({
            url:"getproduct.php",
            method:"get",
            data:{id:productid},
            success:function(data){
              //console.log(data);
              tr.find(".productcode").val(data["product_code"]);
              tr.find(".productname").val(data["product_name"]);
              tr.find(".productstock").val(data["stock"]);
              tr.find(".productsatuan").val(data["product_satuan"]);
              tr.find(".productprice").val(data["sell_price"]);
              tr.find(".quantity_product").val(0);
              tr.find(".producttotal").val(tr.find(".quantity_product").val() * tr.find(".productprice").val());
              calculate(0,0);
            }
          })
        })

      })

      $(document).on('click','.btn-remove', function(){
        $(this).closest('tr').remove();
        calculate(0,0);
        $("#paid").val(0);
      })

      $("#myOrder").delegate(".quantity_product","keyup change", function(){
        var quantity = $(this);
        var tr=$(this).parent().parent();
        if((quantity.val()-0)>(tr.find(".productstock").val()-0)){
          swal("Warning","Persediaan Tidak Cukup","warning");
          quantity.val(1);
          tr.find(".producttotal").val(quantity.val() * tr.find(".productprice").val());
          calculate(0,0);
        }else{
          tr.find(".producttotal").val(quantity.val() * tr.find(".productprice").val());
          calculate(0,0);
        }
      })

      function calculate(paid){
        var net_total = 0;
        var paid = paid;

        $(".producttotal").each(function(){
          net_total = net_total + ($(this).val()*1);
        })

        due = net_total - paid;

        $("#total").val(net_total);
        $("#due").val(due);
      }


      $("#paid").keyup(function(){
        var paid = $(this).val();
        calculate(paid);
      })

    });
  </script>


 <?php
    include_once 'inc/footer_all.php';
 ?>