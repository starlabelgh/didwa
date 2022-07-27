<?php
  include_once'db/connect_db.php';
  session_start();
  if($_SESSION['role']!=="Admin"){
    header('location:index.php');
  }
  include_once'inc/header_all.php';

  if(isset($_POST['submit'])){

    $asset = $_POST['asset'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $serial = $_POST['serial'];
    $code = $_POST['code'];
    $date = $_POST['date'];
    $working = $_POST['working'];
    $amount = $_POST['amount'];

    if(isset($_POST['asset'])){

      $select = $pdo->prepare("SELECT asset_name FROM tbl_asset WHERE asset_name='$asset'");
      $select->execute();

      if($select->rowCount() > 0 ){
          echo'<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "Asset Already Exists", "warning", {
              button: "Continue",
                  });
              });
              </script>';
          }else{
            $insert = $pdo->prepare("INSERT INTO tbl_asset(asset_name, asset_category, asset_brand, asset_sn, asset_code, asset_pd, asset_working, asset_amount) VALUES(:asset, :category, :brand, :serial, :code, :date, :working, :amount)");

            $insert->bindParam(':asset', $asset);
            $insert->bindParam(':category', $category);
            $insert->bindParam(':brand', $brand);
            $insert->bindParam(':serial', $serial);
            $insert->bindParam(':code', $code);
            $insert->bindParam(':date', $date);
            $insert->bindParam(':working', $working);
            $insert->bindParam(':amount', $amount);

            if($insert->execute()){
              echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Success", "New Expense Added", "success", {
              button: "Continue",
                  });
              });
              </script>';
            }
          }
    }
  }
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
       <!-- Category Form-->
      <div class="col-md-4">
            <div class="box box-success">
                <!-- /.box-header -->
                <!-- form start -->
                <form action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="asset">Asset Name</label>
                      <input type="text" class="form-control" name="asset" placeholder="Enter Asset Name">
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="category">Asset Category</label>
                      <input type="text" class="form-control" name="category" placeholder="Enter Asset Category">
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="brand">Asset Brand</label>
                      <input type="text" class="form-control" name="brand" placeholder="Enter Asset Brand">
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="serial">Asset SN</label>
                      <input type="text" class="form-control" name="serial" placeholder="Enter Asset Serial Number">
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="code">Asset Code</label>
                      <input type="text" class="form-control" name="code" placeholder="Enter Asset Code">
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="date">Asset Purchase Date</label>
                      <input type="date" class="form-control" name="date" placeholder="Enter Asset Purchase Date">
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="working">Asset Working? Yes/No</label>
                      <!--<input type="text" class="form-control" name="working" placeholder="Enter Yes/No">-->
                      <select name="working" id="working" class="form-control">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                      </select>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="amount">Amount</label>
                      <input type="text" class="form-control" name="amount" placeholder="Enter Amount">
                    </div>
                  </div><!-- /.box-body -->

                  <!--<div class="box-body">-->
                  <!--<label for="expense">Date</label>-->
                  <!--<div class="input-group">-->
                  <!--  <div class="input-group-addon">-->
                  <!--    <i class="fa fa-calendar"></i>-->
                  <!--  </div>-->
                  <!--  <input type="text" class="form-control pull-right" name="expensedate" value="<?php echo date//("d-m-Y");?>" readonly-->
                  <!--  data-date-format="yyyy-mm-dd">-->
                  <!--</div>-->
                  <!--</div><!-- /.box-body -->
                  
                  <div class="box-footer">
                      <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                  </div>
                </form>
            </div>
      </div>
        <!-- Category Table -->
      <div class="col-md-8">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Asset List</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="overflow-x:auto;">
            <table class="table table-striped" id="myAsset">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Serial Number</th>
                        <th>Asset Code</th>
                        <th>Purchase Date</th>
                        <th>Is Working?</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>

                </thead>
                <tbody>
                <?php
                $select = $pdo->prepare('SELECT * FROM tbl_asset');
                $select->execute();
                while($row=$select->fetch(PDO::FETCH_OBJ)){ ?>
                  <tr>
                    <td><?php echo $row->asset_id; ?></td>
                    <td><?php echo $row->asset_name; ?></td>
                    <td><?php echo $row->asset_category; ?></td>
                    <td><?php echo $row->asset_brand; ?></td>
                    <td><?php echo $row->asset_sn; ?></td>
                    <td><?php echo $row->asset_code; ?></td>
                    <td><?php echo $row->asset_pd; ?></td>
                    <td><?php echo $row->asset_working; ?></td>
                    <td><?php echo $row->asset_amount; ?></td>
                    <td>
                        <a href="edit_asset.php?id=<?php echo $row->asset_id; ?>"
                        class="btn btn-info btn-sm" name="btn_edit"><i class="fa fa-pencil"></i></a>
                        <a href="delete_asset.php?id=<?php echo $row->asset_id; ?>"
                        onclick="return confirm('Remove Asset?')"
                        class="btn btn-danger btn-sm" name="btn_delete"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                <?php
                }
                ?>

                </tbody>
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- DataTables Function -->
  <script>
  $(document).ready( function () {
      $('#myAsset').DataTable();
  } );
  </script>

<?php
  include_once'inc/footer_all.php';
?>