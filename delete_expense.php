<?php
 include_once'db/connect_db.php';
 session_start();
 if($_SESSION['role']!=="Admin"){
   header('location:index.php');
 }

$delete = $pdo->prepare("DELETE FROM tbl_expense WHERE exp_id = '".$_GET['id']." '");
if($delete->execute()){
    header('location:expense.php');
}

