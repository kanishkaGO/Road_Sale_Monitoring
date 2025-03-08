<!DOCTYPE html>
<html>
<head>
<?php require('com/head.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
 function fetchunit(area) {
            $.ajax({
                type: "POST",
                url: "query/fetchdata.php",
                data: 'unid=' + encodeURIComponent(area),
                success: function(data) {
                    $("#m_name").html(data);
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
  .Form {
    width: 1500px;
    height: 200px; /* Adjust the height as needed */
    left: 0px;
    top: 70px;
    position: absolute;
    background: rgba(57.64, 152.18, 182.03, 0.39);
  backdrop-filter: blur(4px)}
  .box.box-info {
  background-color: #f2f2f2;
  padding: 20px;
}
.box-title {
  font-size: 24px;
  color: #333333;
  margin: 0;
}

.table-responsive {
  overflow-x: auto;
}


.table {
  width: 100%;
  border-collapse: collapse;
}

.table th, table td {
  border: 1px solid #dddddd;
  padding: 8px;
  text-align: left;
}

.table th {
  background-color: #ffffff;
}

/* Example styles for image link */
.a img {
  width: 30px;
  height: 30px;
  background-color: #f2f2f2;
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  align-content: space-around;
  flex-direction: column-reverse;
  flex-wrap: wrap;
}
.gp{
	width: 1500px;
    height: 400px; /* Adjust the height as needed */
    left: 0px;
    top: 300px;
    position: absolute;
	
}
.footer{
 top: 1000px;
	
}
.boxwrite{ padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-family: Arial, sans-serif;
  font-size: 16px;
	
}
</style>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

<?php require('com/topMenu.php'); ?>
    <div class="Desktop1" style="width: 1288px; height: 1288px; position: relative; background: white">
      <div class="Bigbox" style="width: 0px; height: 0px; padding-bottom: 13px; padding-right: 86px; left: 0px; top: 0px; position: absolute; justify-content: flex-start; align-items: center; display: inline-flex">
        <div class="Bigbox" style="width: 1440px; height: 1440px; background: rgba(36.07, 56.51, 240.47, 0.13)"></div>
		 <div class="QuickReport" style="width: 1112px; height: 50px; left: 55px; top: 0px; position: absolute; text-align: center; color: black; font-size: 48px; font-family: Inria Serif; font-weight: 400; word-wrap: break-word">QUICK REPORT</div>
      </div>
     
      <div class="Form">
        <form action="" method="POST">
            <!-- Area Name select element -->
			<table class="table table-striped">
            <tr>
              <td>Area Name</td>
              <td>
                <select class="form-control" name="area_name" id="a_name" onChange='fetchunit(this.value)' required>
                  <option value="">- Select -</option>
                  <option value="ALL">-ALL-</option>
<?php
                    require_once('class/dbconnect.php');
                    $sql1 = "SELECT distinct AREA_DESCRIPTION FROM AREANAME";
                    $result = oci_parse($conn, $sql1);
                    oci_execute($result);
                    while ($row = oci_fetch_assoc($result)) {
                      echo "<option value='" . $row['AREA_DESCRIPTION'] . "'>" . $row['AREA_DESCRIPTION'] . "</option>";
                    }
                    oci_free_statement($result);
?>
 </select>
 </select>
              </td>
            </tr>
            <!-- Mine Name select element -->
            <tr>
              <td>Mine Name</td>
              <td>
                <select class="form-control" name="mine_name" id="m_name" required>
                  <option value="">- Select -</option>
                </select>
              </td>
            </tr>
            <!-- From Date input -->
            <tr>
              <td>From Date</td>
              <td><input type="date" id="fromDate" name="fromDate" value="<?php echo date('Y-m-d', strtotime('-1 day')); ?>" required></td>
            </tr>
            <!-- To Date input -->
            <tr>
              <td>To Date</td>
              <td><input type="date" id="toDate" name="toDate" value="<?php echo date('Y-m-d', strtotime('-1 day')); ?>" required></td>
            </tr>
            <!-- Sales Order No input -->
            <tr>
              <td>Sales Order No</td>
              <td><input type="text" class="form-control" id="ARV NO" placeholder="ENTER NO" fdprocessedid="jquj5p"></td>
            </tr>
			
          <button type="submit" class="Button" style="width: 300.36px; height: 51px; left: 0px; top: 250px; position: absolute; background: rgba(116.13, 97.10, 233.03, 0.81); border-left: 2.50px black solid; border-top: 2.50px black solid; border-right: 2.50px black solid; border-bottom: 2.50px black solid">
        <div class="boxwrite"> Generate Report</div> </button>
    
      
	</table>
   </form>

<div class="gp">
            <div class="box-header with-border">
              <h3 class="box-title">GENERATED REPORT</h3>
           <div class="table-responsive">                          
<?php
error_reporting(E_ERROR | E_PARSE);
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$areaName = $_POST['area_name'];
$mineName = $_POST['mine_name'];
$fromDate = date('d-M-y', strtotime($fromDate));
$toDate = date('d-M-y', strtotime($toDate));


require_once 'class/dbconnect.php';
 if ($mineName == 'ALL') {
        if ($areaName == 'ALL') {
            $query = "SELECT AREA_NAME, MINE_NAME, GRADE, SUM(NET) AS TOTAL_QTY
                      FROM ROADSALE
                      WHERE DONO IS NOT NULL AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
                      GROUP BY AREA_NAME, MINE_NAME, GRADE
                      ORDER BY 1, 2, 3";
        } else {
            $query = "SELECT AREA_NAME, MINE_NAME, GRADE, SUM(NET) AS TOTAL_QTY
                      FROM ROADSALE
                      WHERE DONO IS NOT NULL AND AREA_NAME = '$areaName' AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
                      GROUP BY AREA_NAME, MINE_NAME, GRADE
                      ORDER BY 1, 2, 3";
        }
    } else {
        $query = "SELECT AREA_NAME, MINE_NAME, GRADE, SUM(NET) AS TOTAL_QTY
                  FROM ROADSALE
                  WHERE DONO IS NOT NULL AND MINE_CODE = '$mineName' AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
                  GROUP BY AREA_NAME, MINE_NAME, GRADE
                  ORDER BY 1, 2, 3";
    }
	// Query to calculate the total of TOTAL_QTY area-wise
$areaTotalQuery = "SELECT AREA_NAME, SUM(TOTAL_QTY) AS AREA_TOTAL FROM ($query) GROUP BY AREA_NAME";

// Query to calculate the total of TOTAL_QTY
$totalQuery = "SELECT 'Grand Total' AS AREA_NAME, SUM(TOTAL_QTY) AS GRAND_TOTAL FROM ($query)";

// Query to calculate the total of TOTAL_QTY area-wise when all areas are selected
$totalAreaQuery = "SELECT 'Total' AS AREA_NAME, SUM(AREA_TOTAL) AS GRAND_TOTAL FROM ($areaTotalQuery)";

// Execute the main query
// Execute the main query
// Execute the main query
// Execute the main query

$stid = oci_parse($conn, $query);
oci_execute($stid);

// Initialize variables for tracking area changes
$currentArea = null;
$areaTotal = 0;
$grandTotal = 0;

// Output the fetched data
if ($stid) {
	echo "<tr><td>";
		echo"  <a href='ex1.php?mine_name=$mineName&area_name=$areaName&fromDate=$fromDate&toDate=$toDate'>
 <img src='image/ex.png' style='
    width: 30px; /* Adjust the width as needed */
    height: 30px; /* Adjust the height as needed */
    background-color: #f2f2f2; /* Adjust the background color as needed */
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    align-content: space-around;
    flex-direction: column-reverse;
    flex-wrap: wrap;
'></a>";
echo "</tr></td>";
    echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
    echo "<tr>
            <th>AREA Name</th>
            <th>Mine Name</th>
            <th>Grade/Size</th>
            <th>Total Qty in Tes</th>
          </tr>";

    $rowCount = 0;
    while ($res = oci_fetch_assoc($stid)) {
        $rowCount++;
        
        // Check if the area has changed
        if ($currentArea != $res['AREA_NAME']) {
            // Output the area total if it's not the first row
            if ($rowCount > 1) {
                echo "<tr>
                        <td colspan='3'>" . $currentArea . " Total</td>
                        <td>" . $areaTotal . "</td>
                      </tr>";
            }
            
            // Reset area total for the new area
            $currentArea = $res['AREA_NAME'];
            $areaTotal = 0;
        }
        
        // Add the total quantity to the area total and grand total
        $areaTotal += $res['TOTAL_QTY'];
        $grandTotal += $res['TOTAL_QTY'];
        
        echo "<tr>
                <td>" . $res['AREA_NAME'] . "</td>
                <td>" . $res['MINE_NAME'] . "</td>
                <td>" . $res['GRADE'] . "</td>
                <td>" . $res['TOTAL_QTY'] . "</td>
              </tr>";
    }

    // Output the last area total
    echo "<tr>
            <td colspan='3'>" . $currentArea . " Total</td>
            <td>" . $areaTotal . "</td>
          </tr>";

    // Output the grand total row
    echo "<tr>
            <td colspan='3'>Grand Total</td>
            <td>" . $grandTotal . "</td>
          </tr>";

    echo "</tbody></table>";
}

?>
 </div>
 </form>
 </div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
<div class="footer">	
	
   
<?php require('com/footer.php'); ?>
</div>
<?php require('com/lowScript.php'); ?>
</div>
</body>
</html>
















