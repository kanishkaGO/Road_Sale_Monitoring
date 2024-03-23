<?php
error_reporting(E_ERROR | E_PARSE);

$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$areaName = $_POST['area_name'];
$mineName = $_POST['mine_name'];
$fromDate = date('d-M-y', strtotime($fromDate));
$toDate = date('d-M-y', strtotime($toDate));
$RADIO= $_POST['optionsRadios'];


$name = "REPORT";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=" . $name . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'class/dbconnect.php';

if ($RADIO == 'option1') {
	if ($mineName == 'ALL') {
       
       $query = "SELECT AREA_NAME, MINE_NAME, GRADE, SUM(DO_QTY) AS TOTAL_QTY
                FROM ROADSALE
                WHERE DONO IS NOT NULL AND AREA_NAME = '$areaName' AND DATEOUT BETWEEN '$fromDate' AND '$toDate' GROUP BY AREA_NAME, MINE_NAME, GRADE
                ORDER BY 1, 2, 3";
    }
	
	else{
		$query = "SELECT AREA_NAME, MINE_NAME, GRADE, SUM(DO_QTY) AS TOTAL_QTY
                  FROM ROADSALE
                  WHERE DONO IS NOT NULL
				   AND MINE_CODE = '$mineName'
                  AND DATEOUT BETWEEN '$fromDate' AND '$toDate' GROUP BY AREA_NAME, MINE_NAME, GRADE
            ORDER BY 1, 2, 3";
		
	}
    //echo $mineName;
    echo $query;
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    // Output the fetched data
    if ($stid) {
        echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
        echo "<tr>
                <th>AREA Name</th>
                <th>Mine Name</th>
                <th>Grade/Size</th>
                <th>Qty in Tes</th>
            </tr>";

        $rowCount = 0;
        while ($res = oci_fetch_assoc($stid)) {
            $rowCount++;
            echo "<tr>
                    <td>" . $res['AREA_NAME'] . "</td>
                    <td>" . $res['MINE_NAME'] . "</td>
                    <td>" . $res['GRADE'] . "</td>
                    <td>" . $res['TOTAL_QTY'] . "</td>
                </tr>";
        }

        echo "</tbody></table>";
    }
}

elseif($RADIO=='option2'){
	if ($mineName == 'ALL') {
       
       $query = "SELECT AREA_NAME, MINE_NAME,DONO AS SALE_ORDER, GRADE, SUM(DO_QTY) AS TOTAL_QTY
          FROM ROADSALE
          WHERE DONO IS NOT NULL
		 AND AREA_NAME = '$areaName'
          AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
          GROUP BY AREA_NAME, MINE_NAME,DONO, GRADE
          ORDER BY 1, 2, 3,4";
    }
	
	else{
		$query = "SELECT AREA_NAME, MINE_NAME,DONO AS SALE_ORDER, GRADE, SUM(DO_QTY) AS TOTAL_QTY
          FROM ROADSALE
          WHERE DONO IS NOT NULL
		  AND MINE_CODE = '$mineName'
          AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
          GROUP BY AREA_NAME, MINE_NAME,DONO, GRADE
          ORDER BY 1, 2, 3,4";
		
	}
//echo $query;
$stid = oci_parse($conn, $query);
oci_execute($stid);

// Output the fetched data
if ($stid) {
    echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
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

    echo "</tbody></table>";   
}
}
elseif($RADIO=='option3'){
	if ($mineName == 'ALL') {
       if($box=='REPORT_1'){
		 $query="SELECT
  AREA_NAME,
  PARTY_NAME AS Customer_Name,
  MINE_NAME,
  GRADE,
  SUM(DO_QTY - BALANCE_QTY) AS PENDING
FROM
  ROADSALE
WHERE
  DATEOUT = (SELECT MAX(DATEOUT) FROM ROADSALE WHERE DONO IS NOT NULL AND AREA_NAME = '$areaName')
  AND SYSDATE - VALIDITY_DT < 0
  AND DONO IS NOT NULL
  AND AREA_NAME = '$areaName'
GROUP BY
  GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE), ())
ORDER BY
  AREA_NAME,
  MINE_NAME,
  PARTY_NAME,
  GRADE;";  
		   
		   
	   }
	elseif($box=='REPORT_2'){
		
	$query="SELECT
  AREA_NAME,
  PARTY_NAME AS Customer_Name,
  MINE_NAME,
  GRADE,
  SUM(DO_QTY - BALANCE_QTY) AS PENDING
FROM
  ROADSALE
WHERE
  DATEOUT = (SELECT MAX(DATEOUT) FROM ROADSALE WHERE DONO IS NOT NULL AND AREA_NAME = '$areaName')
  AND SYSDATE - VALIDITY_DT < 0
  AND DONO IS NOT NULL
  AND AREA_NAME = '$areaName'
GROUP BY
  GROUPING SETS ((AREA_NAME, PARTY_NAME,MINE_NAME, GRADE), ())
ORDER BY
  AREA_NAME,
  PARTY_NAME,
   MINE_NAME,
  GRADE;";		
	}
	
	else{
	$query="SELECT
AREA_NAME,
PARTY_NAME AS Customer_Name,
MINE_NAME,
DONO AS SALE_ORDER,
GRADE,
SUM(DO_QTY - BALANCE_QTY) AS PENDING
FROM
  ROADSALE
WHERE
  DATEOUT = (SELECT MAX(DATEOUT) FROM ROADSALE WHERE DONO IS NOT NULL AND AREA_NAME = '$areaName')
  AND SYSDATE - VALIDITY_DT < 0
  AND DONO IS NOT NULL
  AND AREA_NAME = '$areaName'
GROUP BY
  GROUPING SETS ((AREA_NAME, PARTY_NAME,MINE_NAME, DONO,GRADE), ())
ORDER BY
  AREA_NAME,
  PARTY_NAME,
   MINE_NAME,
   DONO,
  GRADE; ";
	}
       
    }
	
	else{
		 
       if($box=='REPORT_1'){
		 $query="SELECT
  AREA_NAME,
  PARTY_NAME AS Customer_Name,
  MINE_NAME,
  GRADE,
  SUM(DO_QTY - BALANCE_QTY) AS PENDING
FROM
  ROADSALE
WHERE
  DATEOUT = (SELECT MAX(DATEOUT) FROM ROADSALE WHERE DONO IS NOT NULL ANd MINE_CODE = '$mineName')
  AND SYSDATE - VALIDITY_DT < 0
  AND DONO IS NOT NULL
  ANd MINE_CODE = '$mineName'
GROUP BY
  GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE), ())
ORDER BY
  AREA_NAME,
  MINE_NAME,
  PARTY_NAME,
  GRADE;";  
		   
		   
	   }
	elseif($box=='REPORT_2'){
		
	$query="SELECT
  AREA_NAME,
  PARTY_NAME AS Customer_Name,
  MINE_NAME,
  GRADE,
  SUM(DO_QTY - BALANCE_QTY) AS PENDING
FROM
  ROADSALE
WHERE
  DATEOUT = (SELECT MAX(DATEOUT) FROM ROADSALE WHERE DONO IS NOT NULL ANd MINE_CODE = '$mineName')
  AND SYSDATE - VALIDITY_DT < 0
  AND DONO IS NOT NULL
  ANd MINE_CODE = '$mineName'
GROUP BY
  GROUPING SETS ((AREA_NAME, PARTY_NAME,MINE_NAME, GRADE), ())
ORDER BY
  AREA_NAME,
  PARTY_NAME,
   MINE_NAME,
  GRADE;";		
	}
	
	else{
	$query="SELECT
AREA_NAME,
PARTY_NAME AS Customer_Name,
MINE_NAME,
DONO AS SALE_ORDER,
GRADE,
SUM(DO_QTY - BALANCE_QTY) AS PENDING
FROM
  ROADSALE
WHERE
  DATEOUT = (SELECT MAX(DATEOUT) FROM ROADSALE WHERE DONO IS NOT NULL ANd MINE_CODE = '$mineName')
  AND SYSDATE - VALIDITY_DT < 0
  AND DONO IS NOT NULL
  ANd MINE_CODE = '$mineName'
GROUP BY
  GROUPING SETS ((AREA_NAME, PARTY_NAME,MINE_NAME, DONO,GRADE), ())
ORDER BY
  AREA_NAME,
  PARTY_NAME,
   MINE_NAME,
   DONO,
  GRADE; ";
	}
       
    }
	
	
	
//echo $query;
$stid = oci_parse($conn, $query);
oci_execute($stid);

// Output the fetched data
if ($stid) {
    echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
    echo "<tr>
            <th>AREA Name</th>
			<th>Customer Name</th>
            <th>Mine Name</th>
			  <th>SALE_ORDER</th>
            <th>Grade/Size</th>
            <th>Pending Qty in Tes</th>
          </tr>";

    $rowCount = 0;
    while ($res = oci_fetch_assoc($stid)) {
        $rowCount++;
        echo "<tr>
                <td>" . $res['AREA_NAME'] . "</td>
				<td>" . $res['PARTY_NAME'] . "</td>
                <td>" . $res['MINE_NAME'] . "</td>
				<td>" . $res['SALE_ORDER'] . "</td>
                <td>" . $res['GRADE'] . "</td>
                <td>" . $res['PENDING'] . "</td>
              </tr>";
    }

    echo "</tbody></table>";	   
}
}

    oci_free_statement($stid);
    oci_close($conn);

    if ($rowCount === 0) {
        echo "No data found.";
    }
 else {
    $error = oci_error($stid);
   
}

?>
