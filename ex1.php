<?php
error_reporting(E_ERROR | E_PARSE);
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$areaName = $_GET['area_name'];
$mineName = $_GET['mine_name'];
$fromDate = date('d-M-y', strtotime($fromDate));
$toDate = date('d-M-y', strtotime($toDate));

$name = "REPORT";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=" . $name . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'class/dbconnect.php';

$query = "";
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
$stid = oci_parse($conn, $query);
oci_execute($stid);

// Initialize variables for tracking area changes
$currentArea = null;
$areaTotal = 0;

// Output the fetched data
if ($stid) {
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
        
        // Add the total quantity to the area total
        $areaTotal += $res['TOTAL_QTY'];
        
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

    // Execute the total area query
    $areaTotalStid = oci_parse($conn, $areaTotalQuery);
    oci_execute($areaTotalStid);

    // Output the area-wise totals
    while ($areaTotalRes = oci_fetch_assoc($areaTotalStid)) {
        echo "<tr>
                <td>" . $areaTotalRes['AREA_NAME'] . " Total</td>
                <td></td>
                <td></td>
                <td>" . $areaTotalRes['AREA_TOTAL'] . "</td>
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
            <td colspan='3'>Grand Total</td>
            <td>$grandTotal</td>
          </tr>";

    echo "</tbody></table>";
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
