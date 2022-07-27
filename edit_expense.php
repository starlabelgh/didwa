<?php
  include_once'db/connect_db.php';
  session_start();
  if($_SESSION['role']!=="Admin"){
    header('location:index.php');
  }

  if(isset($_POST['btn_edit'])){
      $exp_name = $_POST['expense'];
      $exp_category = $_POST['category'];
      $exp_amount = $_POST['amount'];

      $update = $pdo->prepare("UPDATE tbl_expense SET exp_name='$exp_name', exp_category='$exp_category', exp_amount='$exp_amount' WHERE exp_id='".$_GET['id']."' ");
      $update->bindParam(':exp_name', $exp_name);
      $update->bindParam(':exp_category', $exp_category);
      $update->bindParam(':exp_name', $exp_name);
      if($update->execute()){
        echo'<script type="text/javascript">
        jQuery(function validation(){
        swal("Success", "Expense Has Been Updated", "success", {
        button: "Continue",
            });
        });
        </script>';
      }else{
        echo'<script type="text/javascript">
        jQuery(function validation(){
        swal("Success", "Category Already Available", "success", {
        button: "Continue",
            });
        });
        </script>';
      }
  }

  if($id=$_GET['id']){
    $select = $pdo->prepare("SELECT * FROM tbl_expense WHERE exp_id = '".$_GET['id']."' ");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_OBJ);
    $exp_name = $row->exp_name;
    $exp_catgory = $row->exp_category;
    $exp_amount = $row->exp_amount;
  }else{
    header('location:expense.php');
  }

  include_once'inc/header_all.php';

?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Expense
      </h1>
      <hr>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
       <!-- Category Form-->
      <div class="col-md-4">
            <div class="box box-warning">
                <!-- /.box-header -->
                <!-- form start -->
                <form action="" method="POST">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="expense">Expense Name</label>
                      <input type="text" class="form-control" name="expense" placeholder="Enter Expense"
                      value="<?php echo $exp_name; ?>" required>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="category">Expense Category</label>
                      <input type="text" class="form-control" name="category" placeholder="Enter Expense Category"
                      value="<?php echo $exp_category; ?>" required>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="amount">Amount</label>
                      <input type="text" class="form-control" name="amount" placeholder="Enter Amount"
                      value="<?php echo $exp_amount; ?>" required>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                      <button type="submit" class="btn btn-primary" name="btn_edit">Update</button>
                      <a href="expense.php" class="btn btn-warning">Back</a>
                  </div>
                </form>
            </div>
      </div>

      <div class="col-md-8">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Edit Expense</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
          <table class="table table-striped">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Expense Name</th>
                      <th>Expense Category</th>
                      <th>Amount</th>
                      
                  </tr>
              </thead>
              <tbody>
              <?php
              $select = $pdo->prepare('SELECT * FROM tbl_expense');
              $select->execute();
              while($row=$select->fetch(PDO::FETCH_OBJ)){ ?>
                <tr>
                  <td><?php echo $row->exp_id; ?></td>
                  <td><?php echo $row->exp_name; ?></td>
                  <td><?php echo $row->exp_category; ?></td>
                  <td><?php echo $row->exp_amount; ?></td>
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
<?php
    include_once'inc/footer_all.php';
?>
