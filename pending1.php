<?php
error_reporting(E_ERROR | E_PARSE);
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$areaName = $_GET['area_name'];
$mineName = $_GET['mine_name'];
$fromDate = date('d-M-y', strtotime($fromDate));
$toDate = date('d-M-y', strtotime($toDate));
$RADIO = $_GET['optionsRadios'];
$box = $_GET['pending_report'];



$name = "REPORT";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=" . $name . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'class/dbconnect.php';
$query = "";

			
if ($mineName == 'ALL'){
if($areaName=='ALL')	{
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
		 and a.area_name=b.area_name
		 		AND a.mine_name=b.mine_name
        AND AREA_NAME = '$areaName'
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
}

else{
	//minename==all
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
	
	AND MINE_CODE='$mineName'
  
)
AND TIMEOUT = (
    SELECT MAX(TIMEOUT)
    FROM ROADSALE b
    WHERE a.DATEOUT = b.DATEOUT
	and a.area_name=b.area_name
	and a.MINE_CODE=b.MINE_CODE
    AND DONO IS NOT NULL
	AND MINE_CODE='$mineName'
)
AND DONO IS NOT NULL
AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
	AND MINE_CODE='$mineName'
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

MINE_NAME,
PARTY_NAME AS Customer_Name,
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
	AND AREA_NAME='$areaName'
)
AND TIMEOUT = (
    SELECT MAX(TIMEOUT)
    FROM ROADSALE b
    WHERE a.DATEOUT = b.DATEOUT
    AND DONO IS NOT NULL
	and a.area_name=b.area_name
	and a.MINE_CODE=b.MINE_CODE
    AND MINE_CODE = '$mineName'
	AND AREA_NAME='$areaName'
)
AND DONO IS NOT NULL
AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
AND MINE_CODE = '$mineName'
AND AREA_NAME='$areaName'
GROUP BY
GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE, BALANCE_QTY), ())
ORDER BY
AREA_NAME,
MINE_NAME,
PARTY_NAME,
GRADE";

    }
}
	$stid = oci_parse($conn, $query);
    oci_execute($stid);

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
					   <td>" . $res['PARTY_NAME'] . "</td>
                    <td>" . $res['GRADE'] . "</td>
                    <td>" . $res['PENDING'] . "</td>
                  </tr>";
        }
 echo "</tbody></table>";