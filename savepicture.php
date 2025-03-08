<?php
$pic = $_POST["image"];
$id = $_GET["qq"];

require_once('class/dbconnect.php');
$sql =  "update visitor_entry set image='$pic' where uniqueid='$id'";
$result = $conn->query($sql);
//echo $sql;

if ($conn->query($sql) === TRUE) {
  echo "<script>alert('Data Saved');location.href='../Welcome1.php;</script>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
  
 echo "<script>alert('Data Saved');location.href='Welcome1.php';</script>";

?>