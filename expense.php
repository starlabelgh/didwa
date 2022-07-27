<?php
  include_once'db/connect_db.php';
  session_start();
  if($_SESSION['role']!=="Admin"){
    header('location:index.php');
  }
  include_once'inc/header_all.php';

  if(isset($_POST['submit'])){


    $expense = $_POST['expense'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];

    if(isset($_POST['expense'])){

      $select = $pdo->prepare("SELECT exp_name FROM tbl_expense WHERE exp_name='$expense'");
      $select->execute();

      if($select->rowCount() > 0 ){
          echo'<script type="text/javascript">
              jQuery(function validation(){
              swal("Warning", "Expense Already Exists", "warning", {
              button: "Continue",
                  });
              });
              </script>';
          }else{
            $insert = $pdo->prepare("INSERT INTO tbl_expense(exp_name, exp_category, exp_amount) VALUES(:expense, :category, :amount)");

            $insert->bindParam(':expense', $expense);
            $insert->bindParam(':category', $category);
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
                      <label for="expense">Expenses Name</label>
                      <input type="text" class="form-control" name="expense" placeholder="Enter Expenses">
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="category">Expenses Category</label>
                      <input type="text" class="form-control" name="category" placeholder="Enter Expenses Category">
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label for="amount">Amount</label>
                      <input type="text" class="form-control" name="amount" placeholder="Enter Amount">
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-body">
                  <label for="expense">Date</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" name="expensedate" value="<?php echo date("d-m-Y");?>" readonly
                    data-date-format="yyyy-mm-dd">
                  </div>
                  </div><!-- /.box-body -->
                  
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
            <h3 class="box-title">Expense List</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="overflow-x:auto;">
            <table class="table table-striped" id="myExpense">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Expense Name</th>
                        <th>Expense Category</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Action</th>
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
                    <td><?php echo $row->exp_date; ?></td>
                    <td>
                        <a href="edit_expense.php?id=<?php echo $row->exp_id; ?>"
                        class="btn btn-info btn-sm" name="btn_edit"><i class="fa fa-pencil"></i></a>
                        <a href="delete_expense.php?id=<?php echo $row->exp_id; ?>"
                        onclick="return confirm('Remove Expense?')"
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
      $('#myExpense').DataTable();
  } );
  </script>

<?php
  include_once'inc/footer_all.php';
?>