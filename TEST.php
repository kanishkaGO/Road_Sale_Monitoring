<?php

require('class/dbconnect.php');

$query ="SELECT AREA_NAME, PARTY_NAME AS Customer_Name, MINE_NAME, GRADE, SUM(DO_QTY - BALANCE_QTY) AS PENDING FROM ROADSALE WHERE DATEOUT = (SELECT MAX(DATEOUT) FROM ROADSALE WHERE DONO IS NOT NULL AND AREA_NAME = 'PENCH') AND SYSDATE - VALIDITY_DT < 0 AND DONO IS NOT NULL AND AREA_NAME = 'PENCH' 
GROUP BY GROUPING SETS ((AREA_NAME, MINE_NAME, PARTY_NAME, GRADE), ()) ORDER BY AREA_NAME, MINE_NAME, PARTY_NAME, GRADE";


$stid = oci_parse($conn, $query);
    oci_execute($stid);

    // Output the fetched data
        echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
        echo "<tr>
                <th>AREA Name</th>
                <th>Mine Name</th>
                <th>SALE_ORDER</th>
                <th>Grade/Size</th>
                <th>Customer_Name</th>
                <th>PENDING9</th>
              </tr>";

        $rowCount = 0;
        while ($res1 = oci_fetch_assoc($stid)) {
            $rowCount++;
            echo "<tr><td>$rowCount</td>
                    <td>" . $res1['AREA_NAME'] . "</td>
                    <td>" . $res1['MINE_NAME'] . "</td>
                    <td>" . $res1['SALE_ORDER'] . "</td>
                    <td>" . $res1['GRADE'] . "</td>
                    <td>" . $res1['CUSTOMER_NAME'] . "</td>
                    <td>" . $res1['PENDING'] . "</td>
                  </tr>";
        }

        echo "</tbody></table>";
		
?>
