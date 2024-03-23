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
    if ($mineName == 'ALL') {
        if ($areaName == 'ALL') {
            $query = "SELECT AREA_NAME, MINE_NAME, DONO AS SALE_ORDER, GRADE, SUM(NET) AS TOTAL_QTY
                FROM ROADSALE
                WHERE DONO IS NOT NULL AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
                GROUP BY AREA_NAME, MINE_NAME, DONO, GRADE
                ORDER BY 1, 2, 3, 4";
        } else {
            // Retrieve the AREA_NAME and respective MINECODES from a different table
// Retrieve the AREA_NAME and respective MINECODES from the "AREANAME" table
$areaQuery = "SELECT AREA_DESCRIPTION, MINE_CODE FROM AREANAME"; // Replace "AREANAME" with the actual table name and "AREA_DESCRIPTION" and "MINE_CODE" with the respective column names
$areaResult = oci_parse($conn, $areaQuery);
oci_execute($areaResult);

// Create an array to store the MINECODES for each AREA_NAME
$areaMines = array();

while ($areaRow = oci_fetch_assoc($areaResult)) {
    $areaName = $areaRow['AREA_DESCRIPTION']; // Update to the actual column name for AREA_NAME
    $mineCode = $areaRow['MINE_CODE']; // Update to the actual column name for MINE_CODE

    // Add the MINECODE to the respective AREA_NAME in the array
    if (!isset($areaMines[$areaName])) {
        $areaMines[$areaName] = array();
    }
    $areaMines[$areaName][] = $mineCode;
}

// Generate the dynamic part of the SQL query for MINE_NAME and MINE_CODE conditions
$mineConditions = "";
if (isset($areaMines[$areaName])) {
    $mineCodes = implode("', '", $areaMines[$areaName]);
    $mineConditions = "AND MINE_CODE IN ('$mineCodes')";
}

// Execute the modified query with the updated conditions
$query = "SELECT AREA_NAME, MINE_NAME, DONO AS SALE_ORDER, GRADE, SUM(NET) AS TOTAL_QTY
            FROM ROADSALE
            WHERE DONO IS NOT NULL AND AREA_NAME = '$areaName' $mineConditions AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
            GROUP BY AREA_NAME, MINE_NAME, DONO, GRADE
            ORDER BY 1, 2, 3, 4";

$stid = oci_parse($conn, $query);
oci_execute($stid);

// Rest of the code for displaying the fetched data and calculating totals
// ...

// Execute the modified query with the updated conditions
$query = "SELECT AREA_NAME, MINE_NAME, DONO AS SALE_ORDER, GRADE, SUM(NET) AS TOTAL_QTY
                FROM ROADSALE
                WHERE DONO IS NOT NULL AND AREA_NAME = '$areaName' $mineConditions AND DATEOUT BETWEEN '$fromDate' AND '$toDate'
                GROUP BY AREA_NAME, MINE_NAME, DONO, GRADE
                ORDER BY 1, 2, 3, 4";

$stid = oci_parse($conn, $query);
oci_execute($stid);

        }
    } else {
        $query = "SELECT AREA_NAME, MINE_NAME, DONO AS SALE_ORDER, GRADE, SUM(NET) AS TOTAL_QTY
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



    oci_free_statement($stid);
    oci_close($conn);

    if ($rowCount === 0) {
        echo "No data found.";
    }
 else {
    $error = oci_error($stid);
   
}

?>
