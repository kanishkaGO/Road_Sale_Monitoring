<!DOCTYPE html>
<html>
<head>
<?php require('com/head.php'); ?>
<script>
function fetchdata(){
	
	var unid=document.getElementById("uniqueid").value;
	alert(unid);
	$.ajax({
		type:"POST",
		url:"query/fetchdata.php",
		data:'unid='+encodeURIComponent(unid),
		success:function(data){
		$("#fetchdatares").html(data);
		}
	});
}
</script>
</head>
<style>
  
  .box .form-group {
    margin-bottom: 0px;
  }
  .box-body .row {
  margin-bottom: 0px; 
}

.box-body .form-group {
  margin-bottom: 0px; 
  
}

/* Add horizontal scrolling */
.table-wrapper {
  overflow-x: auto;
}

</style>
<script>
$(document).ready(function(){
    $('.input-group.date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
});
</script>

<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php require('com/topMenu.php');
//$stmt=$fetchdata->fetch_data("insert into HRD_USER_LOG(USERNAME,MODULE,ACTION,CREATE_DATE)values('".$_SESSION['LOGIN_ID']."','VISIT','DASHBOARD',SYSDATE)");
?>
<div class="content-wrapper" style="min-height: 945.875px;">

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
	  
          <div class="box box-primary">
	  
            <div class="box-header with-border">
              <h3 class="box-title"> Quick REPORT</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method="POST" action="">
			<div class="box-body">
                <div class="form-group">
                    <label for="Aname">Name:</label>
                    <input type="text" id="Aname" name="Aname">
                </div>
                <div class="form-group">
                    <label for="Mname">Name of Mine:</label>
                    <input type="text" id="Mname" name="Mname" >
                </div>
                <div class="form-group">
    <label for="date">Despatch Date</label>
    <div class="input-group date">
        <input type="date" class="form-control" id="date" name="date" required>
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div>
				
				
				<div class="btn-group ">
    <button type="submit" class="btn btn-primary pull-right">Search</button>
</div>

				
               
				</div>
            </form>
          </div>
        </div>
		
		  
</div>
<?php
            require_once('class/dbconnect.php');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $mobile_no = $_POST['mobile_no'];
                $eis_no = $_POST['eis_no'];

                // Construct the SQL query based on the search criteria
                $sql = "SELECT * FROM visitor_entry WHERE ";

                $conditions = array();

                if (!empty($name)) {
                    $conditions[] = "name LIKE '%$name%'";
                }

                if (!empty($mobile_no)) {
                    $conditions[] = "mobile_no LIKE '%$mobile_no%'";
                }

                if (!empty($eis_no)) {
                    $conditions[] = "eis_no LIKE '%$eis_no%'";
                }

                $conditionString = implode(' OR ', $conditions);
                $sql .= $conditionString;
                $sql .= " ORDER BY uniqueid DESC";

                 
            $result = $conn->query($sql);
                

                if ($result->num_rows > 0) {
                    echo "<div class='box-body table-wrapper'> <!-- Add wrapper div for horizontal scrolling -->
                            <div id='example2_wrapper' class='dataTables_wrapper form-inline dt-bootstrap'>
                                <div class='row'>
                                    <div class='col-sm-12'>
                                        <table id='example1' class='table table-bordered table-hover dataTable' role='grid' aria-describedby='example2_info'>
                                            <thead>
											<tr><td>
										
                                        
                                        <a href='excelindividual.php?name=$name&mobile_no=$mobile_no&eis_no=$eis_no'><img src='image/excel.png' width='40' height='40'></a>



										
										</td></tr>
                                                <tr role='row'>
                                                    <th class='sorting_asc' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-sort='ascending' aria-label='Id: activate to sort column descending'>Id</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Name: activate to sort column ascending'>Name</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Mobile Number: activate to sort column ascending'>Mobile Number</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Visitor Type: activate to sort column ascending'>Visitor Type</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='EIS Number: activate to sort column ascending'>EIS Number</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Identification Type: activate to sort column ascending'>Identification Type</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Identification Number: activate to sort column ascending'>Identification Number</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Address: activate to sort column ascending'>Address</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Employee Name: activate to sort column ascending'>Employee Name</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Department: activate to sort column ascending'>Department</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Purpose: activate to sort column ascending'>Purpose</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Image: activate to sort column ascending'>Image</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='In Time: activate to sort column ascending'>In Time</th>
                                                    <th class='sorting' tabindex='0' aria-controls='example2' rowspan='1' colspan='1' aria-label='Out Time: activate to sort column ascending'>Out Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['uniqueid'] . "</td>
                                <td>" . $row['name'] . "</td>
                                <td>" . $row['mobile_no'] . "</td>
                                <td>" . $row['visitor_type'] . "</td>
                                <td>" . $row['eis_no'] . "</td>
                                <td>" . $row['identification_type'] . "</td>
                                <td>" . $row['identification_no'] . "</td>
                                <td>" . $row['address'] . "</td>
                                <td>" . $row['employee_name'] . "</td>
                                <td>" . $row['department_name'] . "</td>
                                <td>" . $row['purpose'] . "</td>
                                <td><img src='" . $row['image'] . "' width='100' height='100'></td>
                                <td>" . $row['date_in'] . "</td>
                                <td>" . $row['date_out'] . "</td>
                            </tr>";
                    }

                    echo "</tbody>
                        </table>
                    </div>
                    </div>
                    </div>
                    </div>";
                } else {
                    echo "No entries found for the search criteria.";
                }

                $conn->close();
            }
            ?>
      <!-- /.row -->
    </section>
    <!-- /.content -->
	
  </div>
<?php require('com/footer.php'); ?>
</div>
<?php require('com/lowScript.php'); ?>
</body>
</html>



