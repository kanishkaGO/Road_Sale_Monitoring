<!DOCTYPE html>
<html>
<head>
<?php require('com/head.php'); ?>
<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<script type="text/javascript">
function check(){
  if(document.getElementById('newpwd').value==document.getElementById('confirmnewpwd').value){
    return true;
  }else{
    alert('New password and Confirm new password should be same');
    document.getElementById('newpwd').value=document.getElementById('confirmnewpwd').value='';
    return false;
  }
}
</script>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php require('com/topMenu.php');
if(isset($res['USERTYPE'])){
	$usr=$_SESSION['USERID'];
}else{
  echo"<script>alert('Invalid option');location.href='com/logout.php';</script>";
}
?>
  <div class="content-wrapper">
    <div class="container">
      <div class="row col-xs-12">
        <div class="box">
            <div class="box-header">
              <h3 class="box-title">Change Password</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype='multipart/form-data'>
              <table  class="table table-bordered table-striped">
                <tbody>
                  <tr>
                    <td>Enter Old Password</td><td><input type='password' id='oldpwd' name='oldpwd' required /></td>
                  </tr>
                  <tr>
                    <td>Enter New Password</td><td><input type='password' id='newpwd' name='newpwd' required /></td>
                  </tr>
                  <tr>
                    <td>Confirm New Password</td><td><input type='password' id='confirmnewpwd' name='confirmnewpwd' onBlur='check()' required /></td>
                  </tr>
                  <tr>
                    <td></td><td><button type="submit" class="btn btn-primary" name='submit'>Change Password</button></td>
                  </tr>
                </tbody>
              </table>
              </form>
            </div>
          </div>
      </div>
    </div>
  </div>
<?php require('com/footer.php'); ?>
</div>
<?php require('com/lowScript.php'); ?>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="dist/js/demo.js"></script>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
</body>
</html>
<?php
  if(isset($_REQUEST['submit'])){
    $oldpwd=$_POST['oldpwd'];
    $newpwd=$_POST['newpwd'];
    $confirmnewpwd=$_POST['confirmnewpwd'];
    if($newpwd==$confirmnewpwd){
      $fetchdata=new fetch();
      $stmt=$fetchdata->fetch_data("select USERPASSWORD from EDO_USER_MASTER where USERID ='".$_SESSION['USERID']."'");
      $res=oci_fetch_assoc($stmt);
      if($res['USERPASSWORD']==$oldpwd){
        $stmt=$fetchdata->fetch_data("update EDO_USER_MASTER set USERPASSWORD='$newpwd',MODIFY_USER='".$_SESSION['USERID']."',MODIFY_DATE=SYSDATE where USERID ='".$_SESSION['USERID']."'");
        $stmt=$fetchdata->fetch_data("insert into EDO_USER_LOG(USERNAME,MODULE,ACTION,CREATE_DATE)values('".$_SESSION['USERID']."','CHANGE PWD','',SYSDATE)");
        echo"<script>alert('Password changed. Kindly login with new password');location.href='com/logout.php';</script>";
      }else{
        echo"<script>alert('Incorrect Old password');</script>";
      }
    }else{
      echo"<script>alert('New password and Confirm new password should be same');</script>";
    }
  }
 ?>
