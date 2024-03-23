<?php

error_reporting(E_ERROR | E_PARSE);

$name1 = $_GET['name'];
$mobile_no = $_GET['mobile_no'];
$eis_no = $_GET['eis_no'];



$name = "individual_training";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=" . $name . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'class/dbconnect.php';

//$query = "SELECT * FROM visitor_entry WHERE  ORDER BY uniqueid DESC";
//echo $query;
//$query = "SELECT * FROM visitor_entry WHERE name = '$name' AND mobileno = '$mobile_no' AND eisno = '$eis_no' ORDER BY uniqueid DESC";
$sql = "SELECT * FROM visitor_entry WHERE ";
$conditions = array();

                if (!empty($name1)) {
                    $conditions[] = "name LIKE '%$name1%'";
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
//echo $sql;
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {

echo "<table border='1' cellpadding='0' cellspacing='0'><tbody>";
echo "<tr>
        <th>Id</th>
        <th>Name</th>
        <th>Mobile number</th>
        <th>Visitor Type</th>
        <th>EIS Number</th>
        <th>Identification Type</th>
        <th>Identification Number</th>
        <th>Address</th>
        <th>Employee Name</th>
        <th>Department</th>
        <th>Purpose</th>
        <th>In Time</th>
        <th>Out Time</th>
      </tr>";

 while ($res = $result->fetch_assoc()) {

        echo "<tr>
                <td>" . $res['uniqueid'] . "</td>
                <td>" . $res['name'] . "</td>
                <td>" . $res['mobile_no'] . "</td>
                <td>" . $res['visitor_type'] . "</td>
                <td>" . $res['eis_no'] . "</td>
                <td>" . $res['identification_type'] . "</td>
                <td>" . $res['identification_no'] . "</td>
                <td>" . $res['address'] . "</td>
                <td>" . $res['employee_name'] . "</td>
                <td>" . $res['department_name'] . "</td>
                <td>" . $res['purpose'] . "</td>
                <td>" . $res['date_in'] . "</td>
                <td>" . $res['date_out'] . "</td>
              </tr>";
    }

    $result->free_result();
} else {
	
}
    



$conn->close();

?>
