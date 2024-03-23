<!DOCTYPE html>
<html>
<head>
    <?php require('com/head.php'); ?>
    <title>Visitor Entry</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body class="hold-transition skin-blue layout-top-nav">
    <?php require('com/topMenu.php'); ?>
    <div class="content-wrapper" style="min-height: 945.875px;">
        <div class="container">
            <h2></h2>
            <!-- Search Form -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="mobile_no">Mobile Number:</label>
                    <input type="text" id="mobile_no" name="mobile_no" maxlength='10' minlength='10'>
                </div>
                <div class="form-group">
                    <label for="eis_no">EIS Number:</label>
                    <input type="text" id="eis_no" name="eis_no" maxlength='8' minlength='8'>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>

            <hr>
            <div class="table-responsive">
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
                    echo "<div class='box-body'>
                            <div id='example2_wrapper' class='dataTables_wrapper form-inline dt-bootstrap'>
                                <div class='row'>
                                    <div class='col-sm-12'>
                                        <table id='example2' class='table table-bordered table-hover dataTable' role='grid' aria-describedby='example2_info'>
                                            <thead>
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
                    </div>";
                } else {
                    echo "No entries found for the search criteria.";
                }

                $conn->close();
            }
            ?>
            </div>
        </div>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        
    </div>
</body>
</html>
