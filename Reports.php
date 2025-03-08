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

function functionset(){
			document.getElementById("sddffdf").hidden=false;
		}
		function functionset1(){
			document.getElementById("sddffdf").hidden=true;
		}
		
    </script>
</head>
<style>
    .box .form-group {
        background-color: #f0f0f0;
        margin-bottom: 20px;
    }
    .box-body .row {
        background-color: #f0f0f0;
        margin-bottom: 20px;
    }
    .box-body .form-group {
        background-color: #f0f0f0;
        margin-bottom: 20px;
    }
</style>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
        <?php require('com/topMenu.php'); ?>
        <div class="content-wrapper" style="min-height: 945.875px;">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- ------------------------------------------------------- -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="" method="POST">
                            <div class="box-body">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Select report type</td>
                                            <td>
											
                                                <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" onClick='functionset1()' >
                                                Quick Report<br/>
                                                <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" onClick='functionset1()'>
                                                Detailed Report<br/>
												 <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" onClick='functionset()' checked="" required>
                                               Pending report
<tr id='sddffdf'>
                                            <td></td>
                                            <td>											 
 <select class="form-control" name="pending_report" id="pending_report">
  <option value=''>-select-</option>
  <option value="REPORT_1">REPORT_1</option>
  <option value="REPORT_2">REPORT_2</option>
  <option value="REPORT_3">REPORT_3</option>
</select>
</td></tr>

 

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Area Name</td>
                                            <td>
                                                <select class="form-control" name="area_name" id="a_name" onChange='fetchunit(this.value)' required>
                                                    <option value=''>-select-</option>
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
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Mine Name</td>
                                            <td>
                                                <select class="form-control" name="mine_name" id="m_name" required></select>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                           <td>From Date</td>
                           <td><input type="date" id="fromDate" name="fromDate" value="<?php echo date('Y-m-d', strtotime('-1 day')); ?>" required></td>
                           </tr>
                            <tr>
                            <td>To Date</td>
                            <td><input type="date" id="toDate" name="toDate" value="<?php echo date('Y-m-d', strtotime('-1 day')); ?>" required></td>
                             </tr>
<tr>
                                            <td>Sales Order No</td>
                                            <td><input type="text" class="form-control" id="ARV NO" placeholder="ENTER NO" fdprocessedid="jquj5p"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                   
                                </div>
                            </div>
                        </form>
						<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">GENERATED REPORT</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal">
					<tr>
                        <div class="table-responsive">
                            
                                <?php
error_reporting(E_ERROR | E_PARSE);
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$areaName = $_POST['area_name'];
$mineName = $_POST['mine_name'];
$fromDate = date('d-M-y', strtotime($fromDate));
$toDate = date('d-M-y', strtotime($toDate));
$RADIO = $_POST['optionsRadios'];
$box = $_POST['pending_report'];

require_once 'class/dbconnect.php';

if ($RADIO == 'option1') {
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

$stid = oci_parse($conn, $query);
oci_execute($stid);

// Initialize variables for tracking area changes
$currentArea = null;
$areaTotal = 0;
$grandTotal = 0;

// Output the fetched data
if ($stid) {
	echo "<tr><td>";
		echo"  <a href='ex2.php?mine_name=$mineName&area_name=$areaName&fromDate=$fromDate&toDate=$toDate'>
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
}
elseif ($RADIO == 'option2') {
    if ($mineName == 'ALL') {
        if ($areaName == 'ALL') {
            $query = "SELECT AREA_NAME, MINE_NAME, DONO AS SALE_ORDER, GRADE, SUM(NET) AS TOTAL_QTY
                FROM ROADSALE
                WHERE DONO IS NOT NULL AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
                GROUP BY AREA_NAME, MINE_NAME, DONO, GRADE
                ORDER BY 1, 2, 3, 4";
        } else {
            $query = "SELECT AREA_NAME, MINE_NAME, DONO AS SALE_ORDER, GRADE, SUM(NET) AS TOTAL_QTY
                FROM ROADSALE
                WHERE DONO IS NOT NULL AND AREA_NAME = '$areaName' AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
                GROUP BY AREA_NAME, MINE_NAME, DONO, GRADE
                ORDER BY 1, 2, 3, 4";
        }
    } else {
        $query = "SELECT AREA_NAME, MINE_NAME, DONO AS SALE_ORDER, GRADE, SUM(NET3) AS TOTAL_QTY
            FROM ROADSALE
            WHERE DONO IS NOT NULL AND MINE_CODE = '$mineName' AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
            GROUP BY AREA_NAME, MINE_NAME, DONO, GRADE
            ORDER BY 1, 2, 3, 4";
    }

    // Query to calculate the total of TOTAL_QTY
    $totalQuery = "SELECT SUM(TOTAL_QTY) AS GRAND_TOTAL FROM ($query)";

    // Execute the main query
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    // Output the fetched data
    if ($stid) {
        echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
		echo "<tr><td>";
		echo"  <a href='ex2.php?mine_name=$mineName&area_name=$areaName&fromDate=$fromDate&toDate=$toDate'>
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
        echo "<tr>
                <th>AREA Name</th>
                <th>Mine Name</th>
                <th>SALE_ORDER</th>
                <th>Grade/Size</th>
                <th>Qty in Tes</th>
              </tr>";

        $rowCount = 0;
        while ($res = oci_fetch_assoc($stid)) {
            $rowCount++;
            echo "<tr>
                    <td>" . $res['AREA_NAME'] . "</td>
                    <td>" . $res['MINE_NAME'] . "</td>
                    <td>" . $res['SALE_ORDER'] . "</td>
                    <td>" . $res['GRADE'] . "</td>
                    <td>" . $res['TOTAL_QTY'] . "</td>
                  </tr>";
        }

        // Execute the total query
        $totalStid = oci_parse($conn, $totalQuery);
        oci_execute($totalStid);

        // Fetch the total
        $totalRes = oci_fetch_assoc($totalStid);
        $grandTotal = $totalRes['GRAND_TOTAL'];

        // Output the grand total row
        echo "<tr>
                <td colspan='4'>Grand Total</td>
                <td>$grandTotal</td>
              </tr>";

        echo "</tbody></table>";
    }
}
////repot3

    else{
		if ($mineName == 'ALL')
			{
		
		
        if ($box == 'REPORT_1') {
		
			if ($areaName == 'ALL') {
				$query = "SELECT
    AREA_NAME,
    PARTY_NAME AS Customer_Name,
    MINE_NAME,
    GRADE,
    BALANCE_QTY AS PENDING
FROM
    ROADSALE a
WHERE
    DATEOUT = (
        SELECT MAX(DATEOUT)
        FROM ROADSALE b
        WHERE DONO IS NOT NULL
		
      
    )
    AND TIMEOUT = (
        SELECT MAX(TIMEOUT)
        FROM ROADSALE b
        WHERE a.DATEOUT = b.DATEOUT
        AND DONO IS NOT NULL
		and a.area_name=b.area_name
    )
    AND DONO IS NOT NULL
    AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
    
GROUP BY
    GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE, BALANCE_QTY), ())
ORDER BY
    AREA_NAME,
    MINE_NAME,
    PARTY_NAME,
    GRADE,PENDING";
				
			}
			else{
            $query = "SELECT
    AREA_NAME,
    PARTY_NAME AS Customer_Name,
    MINE_NAME,
    GRADE,
    BALANCE_QTY AS PENDING
FROM
    ROADSALE a
WHERE
    DATEOUT = (
        SELECT MAX(DATEOUT)
        FROM ROADSALE
        WHERE DONO IS NOT NULL
        AND AREA_NAME = '$areaName'
		
    )
    AND TIMEOUT = (
        SELECT MAX(TIMEOUT)
        FROM ROADSALE b
        WHERE a.DATEOUT = b.DATEOUT
        AND DONO IS NOT NULL
        AND AREA_NAME = '$areaName'
		AND a.area_name=b.area_name
		AND a.mine_name=b.mine_name
    )
    AND DONO IS NOT NULL
    AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
    AND AREA_NAME = '$areaName'
GROUP BY
    GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE, BALANCE_QTY), ())
ORDER BY
    AREA_NAME,
    MINE_NAME,
    PARTY_NAME,
    GRADE";

        }
		}elseif ($box == 'REPORT_2') {
		if($areaName=='ALL'){
            $query = "SELECT
    AREA_NAME,
    PARTY_NAME AS Customer_Name,
    MINE_NAME,
    GRADE,
    BALANCE_QTY AS PENDING
FROM
    ROADSALE a
WHERE
    DATEOUT = (
        SELECT MAX(DATEOUT)
        FROM ROADSALE
	
        WHERE DONO IS NOT NULL
		
    )
    AND TIMEOUT = (
        SELECT MAX(TIMEOUT)
        FROM ROADSALE b
        WHERE a.DATEOUT = b.DATEOUT
		and a.area_name=b.area_name
        AND DONO IS NOT NULL
		
    )
    AND DONO IS NOT NULL
    AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
 
GROUP BY
    GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE, BALANCE_QTY), ())
ORDER BY
    AREA_NAME,
    MINE_NAME,
    PARTY_NAME,
    GRADE";
		}
        
		else{
			
			$query = "SELECT
    AREA_NAME,
    PARTY_NAME AS Customer_Name,
    MINE_NAME,
    GRADE,
    BALANCE_QTY AS PENDING
FROM
    ROADSALE a
WHERE
    DATEOUT = (
        SELECT MAX(DATEOUT)
        FROM ROADSALE
        WHERE DONO IS NOT NULL
		
        AND AREA_NAME = '$areaName'
    )
    AND TIMEOUT = (
        SELECT MAX(TIMEOUT)
        FROM ROADSALE b
        WHERE a.DATEOUT = b.DATEOUT
		and a.area_name=b.area_name
		AND a.mine_name=b.mine_name
        AND DONO IS NOT NULL
        AND AREA_NAME = '$areaName'
    )
    AND DONO IS NOT NULL
    AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
    AND AREA_NAME ='$areaName'
GROUP BY
    GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE, BALANCE_QTY), ())
ORDER BY
    AREA_NAME,
    MINE_NAME,
    PARTY_NAME,
    GRADE";
			
		} 
		}
			else{
				if($areaName=='ALL'){
				$query = "SELECT
                          AREA_NAME,
                          PARTY_NAME AS Customer_Name,
                          MINE_NAME,
						  DONO as SALES_ORDER,
                          GRADE,
                         BALANCE_QTY AS PENDING
                         
                      FROM
                          ROADSALE a
                      WHERE
    DATEOUT = (
        SELECT MAX(DATEOUT)
        FROM ROADSALE
		
        WHERE DONO IS NOT NULL
        
    )
    AND TIMEOUT = (
        SELECT MAX(TIMEOUT)
        FROM ROADSALE b
        WHERE a.DATEOUT = b.DATEOUT
		and a.area_name=b.area_name
        AND DONO IS NOT NULL
       
    )
    AND DONO IS NOT NULL
    AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
  
                      GROUP BY
                          GROUPING SETS ((AREA_NAME, PARTY_NAME, MINE_NAME,DONO, GRADE,BALANCE_QTY), ())
                      ORDER BY
                          AREA_NAME,
                          PARTY_NAME,
                          MINE_NAME,
						  SALES_ORDER,
                          GRADE,
						  PENDING";
			
			}
			else{
				
$query = "SELECT
                          AREA_NAME,
                          PARTY_NAME AS Customer_Name,
                          MINE_NAME,
						  DONO AS SALES_ORDER,
                          GRADE,
                         BALANCE_QTY AS PENDING
                         
                      FROM
                          ROADSALE a
                      WHERE
    DATEOUT = (
        SELECT MAX(DATEOUT)
        FROM ROADSALE
        WHERE DONO IS NOT NULL
	
        AND AREA_NAME = '$areaName'
    )
    AND TIMEOUT = (
        SELECT MAX(TIMEOUT)
        FROM ROADSALE b
        WHERE a.DATEOUT = b.DATEOUT
        AND DONO IS NOT NULL
		and a.area_name=b.area_name
		AND a.mine_name=b.mine_name
        AND AREA_NAME = '$areaName'
    )
    AND DONO IS NOT NULL
    AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
    AND AREA_NAME ='$areaName'
                      GROUP BY
                          GROUPING SETS ((AREA_NAME, PARTY_NAME, MINE_NAME,DONO, GRADE,BALANCE_QTY), ())
                      ORDER BY
                          AREA_NAME,
                          PARTY_NAME,
                          MINE_NAME,
						  SALES_ORDER,
                          GRADE,
						  PENDING";
			}
		}
		
		
		} 
		///////////
		else {
			
              if ($box == 'REPORT_1') {
		
  if($areaName=='ALL')
  {$query = "SELECT
AREA_NAME,
PARTY_NAME AS Customer_Name,
MINE_NAME,
GRADE,
BALANCE_QTY AS PENDING
FROM
ROADSALE a
WHERE
DATEOUT = (
    SELECT MAX(DATEOUT)
    FROM ROADSALE
    WHERE DONO IS NOT NULL

	AND MINE_CODE = '$mineName'
  
)
AND TIMEOUT = (
    SELECT MAX(TIMEOUT)
    FROM ROADSALE b
    WHERE a.DATEOUT = b.DATEOUT
    AND DONO IS NOT NULL
	and a.area_name=b.area_name
	and a.MINE_CODE=b.MINE_CODE
	AND MINE_CODE = '$mineName'
)
AND DONO IS NOT NULL
AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
AND MINE_CODE = '$mineName'
GROUP BY
GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE, BALANCE_QTY), ())
ORDER BY
AREA_NAME,
MINE_NAME,
PARTY_NAME,
GRADE,PENDING";
    
  }
  else{
        $query = "SELECT
AREA_NAME,
PARTY_NAME AS Customer_Name,
MINE_NAME,
GRADE,
BALANCE_QTY AS PENDING
FROM
ROADSALE a
WHERE
DATEOUT = (
    SELECT MAX(DATEOUT)
    FROM ROADSALE
    WHERE DONO IS NOT NULL
	
    AND MINE_CODE = '$mineName'
	AND AREA_NAME= '$areaName'
)
AND TIMEOUT = (
    SELECT MAX(TIMEOUT)
    FROM ROADSALE b
    WHERE a.DATEOUT = b.DATEOUT
    AND DONO IS NOT NULL
	and a.area_name=b.area_name
	and a.MINE_CODE=b.MINE_CODE
    AND MINE_CODE = '$mineName'
	AND AREA_NAME= '$areaName'
)
AND DONO IS NOT NULL
AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
AND MINE_CODE = '$mineName'
AND AREA_NAME= '$areaName'
GROUP BY
GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE, BALANCE_QTY), ())
ORDER BY
AREA_NAME,
MINE_NAME,
PARTY_NAME,
GRADE";

    }
}elseif ($box == 'REPORT_2') {
if($areaName=='ALL'){
        $query = "SELECT
AREA_NAME,
PARTY_NAME AS Customer_Name,
MINE_NAME,
GRADE,
BALANCE_QTY AS PENDING
FROM
ROADSALE a
WHERE
DATEOUT = (
    SELECT MAX(DATEOUT)
    FROM ROADSALE
    WHERE DONO IS NOT NULL

	
	AND MINE_CODE ='$mineName'
)
AND TIMEOUT = (
    SELECT MAX(TIMEOUT)
    FROM ROADSALE b
    WHERE a.DATEOUT = b.DATEOUT
    AND DONO IS NOT NULL
	and a.area_name=b.area_name
	and a.MINE_CODE=b.MINE_CODE
	AND MINE_CODE ='$mineName'
)
AND DONO IS NOT NULL
AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
AND MINE_CODE ='$mineName'
GROUP BY
GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE, BALANCE_QTY), ())
ORDER BY
AREA_NAME,
MINE_NAME,
PARTY_NAME,
GRADE";
}
    
else{
  
  $query = "SELECT
AREA_NAME,
PARTY_NAME AS Customer_Name,
MINE_NAME,
GRADE,
BALANCE_QTY AS PENDING
FROM
ROADSALE a
WHERE
DATEOUT = (
    SELECT MAX(DATEOUT)
    FROM ROADSALE
    WHERE DONO IS NOT NULL
	
	AND AREA_NAME= '$areaName'
    AND MINE_CODE = '$mineName'
)
AND TIMEOUT = (
    SELECT MAX(TIMEOUT)
    FROM ROADSALE b
    WHERE a.DATEOUT = b.DATEOUT
    AND DONO IS NOT NULL
	and a.area_name=b.area_name
	and a.MINE_CODE=b.MINE_CODE
	AND AREA_NAME= '$areaName'
    AND MINE_CODE= '$mineName'
)
AND DONO IS NOT NULL
AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
AND AREA_NAME= '$areaName'
AND MINE_CODE ='$mineName'
GROUP BY
GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE, BALANCE_QTY), ())
ORDER BY
AREA_NAME,
MINE_NAME,
PARTY_NAME,
GRADE";
  
} 
}
  else{
    if($areaName=='ALL'){
    $query = "SELECT
                      AREA_NAME,
                      PARTY_NAME AS Customer_Name,
                      MINE_NAME,
          DONO as SALES_ORDER,
                      GRADE,
                     BALANCE_QTY AS PENDING
                     
                  FROM
                      ROADSALE a
                  WHERE
DATEOUT = (
    SELECT MAX(DATEOUT)
    FROM ROADSALE
    WHERE DONO IS NOT NULL

AND MINE_CODE= '$mineName'
    
)
AND TIMEOUT = (
    SELECT MAX(TIMEOUT)
    FROM ROADSALE b
    WHERE a.DATEOUT = b.DATEOUT
	and a.area_name=b.area_name
	and a.MINE_CODE=b.MINE_CODE
    AND DONO IS NOT NULL
	
   AND MINE_CODE= '$mineName'
)
AND DONO IS NOT NULL
AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
AND MINE_CODE= '$mineName'

                  GROUP BY
                      GROUPING SETS ((AREA_NAME, PARTY_NAME, MINE_NAME,DONO, GRADE,BALANCE_QTY), ())
                  ORDER BY
                      AREA_NAME,
                      PARTY_NAME,
                      MINE_NAME,
          SALES_ORDER,
                      GRADE,
          PENDING";
  
  }
  else{
    
$query = "SELECT
                      AREA_NAME,
                      PARTY_NAME AS Customer_Name,
                      MINE_NAME,
          DONO AS SALES_ORDER,
                      GRADE,
                     BALANCE_QTY AS PENDING
                     
                  FROM
                      ROADSALE a
                  WHERE
DATEOUT = (
    SELECT MAX(DATEOUT)
    FROM ROADSALE
    WHERE DONO IS NOT NULL

    AND MINE_CODE= '$mineName'
	AND AREA_NAME= '$areaName'
)
AND TIMEOUT = (
    SELECT MAX(TIMEOUT)
    FROM ROADSALE b
    WHERE a.DATEOUT = b.DATEOUT
	and a.area_name=b.area_name
	and a.MINE_CODE=b.MINE_CODE
    AND DONO IS NOT NULL
    AND MINE_CODE= '$mineName'
	AND AREA_NAME= '$areaName'
)
AND DONO IS NOT NULL
AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
 AND MINE_CODE= '$mineName'
 AND AREA_NAME= '$areaName'
                  GROUP BY
                      GROUPING SETS ((AREA_NAME, PARTY_NAME, MINE_NAME,DONO, GRADE,BALANCE_QTY), ())
                  ORDER BY
                      AREA_NAME,
                      PARTY_NAME,
                      MINE_NAME,
          SALES_ORDER,
                      GRADE,
          PENDING";
  }
}
    }
	
	//////
	
   //echo "$query";
 if ($box == 'REPORT_1' ) {
 
    $stid = oci_parse($conn, $query);
    oci_execute($stid);
echo "<tr><td>";
		echo"  <a href='pending1.php?mine_name=$mineName&area_name=$areaName&fromDate=$fromDate&toDate=$toDate'>
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
    // Output the fetched data
        echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
        echo "<tr>
                <th>AREA Name</th>
                <th>Mine Name</th>
				<th>Customer_Name</th>
                <th>Grade/Size</th>
                <th>PENDING</th>
              </tr>";

        $rowCount = 0;
        while ($res = oci_fetch_assoc($stid)) {
            $rowCount++;
            echo "<tr>
                    <td>" . $res['AREA_NAME'] . "</td>
                    <td>" . $res['MINE_NAME'] . "</td>
					 <td>" . $res['CUSTOMER_NAME'] ."</td>
                    <td>" . $res['GRADE'] . "</td>
                    <td>" . $res['PENDING'] . "</td>
                  </tr>";
        }

        echo "</tbody></table>";
} 
///
elseif($box == 'REPORT_2'){
	$stid=oci_parse($conn, $query);
    oci_execute($stid);
	echo "<tr><td>";
		echo"<a href='pending2.php?mine_name=$mineName&area_name=$areaName&fromDate=$fromDate&toDate=$toDate'>
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

    // Output the fetched data
        echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
        echo "<tr>
                <th>AREA Name</th>
                <th>Mine Name</th>
                <th>Grade/Size</th>
                <th>Customer_Name</th>
                <th>PENDING</th>
              </tr>";

        $rowCount = 0;
        while ($res1 = oci_fetch_assoc($stid)) {
            $rowCount++;
            echo "<tr>
                    <td>" . $res1['AREA_NAME'] . "</td>
                    <td>" . $res1['MINE_NAME']. "</td>
                    <td>" . $res1['GRADE'] . "</td>
                    <td>" . $res1['CUSTOMER_NAME'] . "</td>
                    <td>" . $res1['PENDING'] . "</td>
                  </tr>";
        }

        echo "</tbody></table>";
	
}

else {

    $stid = oci_parse($conn, $query);
    oci_execute($stid);
echo "<tr><td>";
		echo"<a href='pending3.php?mine_name=$mineName&area_name=$areaName&fromDate=$fromDate&toDate=$toDate'>
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
    // Output the fetched data
        echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
        echo "<tr>
                <th>AREA Name</th>
                <th>Mine Name</th>
                <th>SALE_ORDER</th>
                <th>Grade/Size</th>
                <th>Customer_Name</th>
                <th>PENDING</th>
              </tr>";

        $rowCount = 0;
        while ($res1 = oci_fetch_assoc($stid)) {
            $rowCount++;
            echo "
                    <td>" . $res1['AREA_NAME']."</td>
                    <td>" . $res1['MINE_NAME']."</td>
                    <td>" . $res1['SALES_ORDER']."</td>
                    <td>" . $res1['GRADE'] . "</td>
                    <td>" . $res1['CUSTOMER_NAME']."</td>
                    <td>" . $res1['PENDING'] . "</td>
                  </tr>";
        }

        echo "</tbody></table>";
   
}
	}
///racle connection
	
?>   
                        </div>
						</tr>
						
              
            </form>
          </div>
   
            <!-- /.box-body -->
          </div>						
						
                    </div>
                </div>
            </section>
        </div>
        <?php require('com/footer.php'); ?>
    </div>
    <?php require('com/lowScript.php'); ?>
</body>
</html>
