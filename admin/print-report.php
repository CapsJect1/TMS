<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    // Function to fetch the total payments for each month
    function Sales($month, $conn)
    {
        $year = date('Y');
        $stmt = $conn->query("SELECT SUM(payment) AS TOTAL FROM booking WHERE MONTH(date_created) = '$month' AND YEAR(date_created) = '$year' ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['TOTAL'];
    }

    // Data for each month
    $january = Sales('01', $dbh);
    $february = Sales('02', $dbh);
    $mar = Sales('03', $dbh);
    $apr = Sales('04', $dbh);
    $may = Sales('05', $dbh);
    $june = Sales('06', $dbh);
    $july = Sales('07', $dbh);
    $aug = Sales('08', $dbh);
    $sept = Sales('09', $dbh);
    $oct = Sales('10', $dbh);
    $nov = Sales('11', $dbh);
    $dec = Sales('12', $dbh);

    // Total Payment Calculation
    $total = $january + $february + $mar + $june + $july + $aug + $sept + $oct + $nov + $dec;

    require './includes/layout-head.php';
?>

<div class="card mt-4 print">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Booking Report</h3>
            <h3>Total: <?= number_format($total, 2) ?></h3>
        </div>

        <!-- Graph -->
        <canvas id="barChart" style="width:100%;"></canvas>

        <!-- Print Button -->
        <a href="javascript:void(0);" class="float-end mt-3 btn btn-primary" id="printButton"><i class="fa fa-print"></i> Print</a>
    </div>
</div>

<!-- Table for Displaying the Report (Hidden for print) -->
<div id="printSection" style="display:none;">
    <h3>Booking Report - Printable Version</h3>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Month</th>
                <th>Total Booked</th>
                <th>Total Payment</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>January</td>
                <td><?= $january ?></td>
                <td><?= number_format($january, 2) ?></td>
            </tr>
            <tr>
                <td>February</td>
                <td><?= $february ?></td>
                <td><?= number_format($february, 2) ?></td>
            </tr>
            <tr>
                <td>March</td>
                <td><?= $mar ?></td>
                <td><?= number_format($mar, 2) ?></td>
            </tr>
            <tr>
                <td>April</td>
                <td><?= $apr ?></td>
                <td><?= number_format($apr, 2) ?></td>
            </tr>
            <tr>
                <td>May</td>
                <td><?= $may ?></td>
                <td><?= number_format($may, 2) ?></td>
            </tr>
            <tr>
                <td>June</td>
                <td><?= $june ?></td>
                <td><?= number_format($june, 2) ?></td>
            </tr>
            <tr>
                <td>July</td>
                <td><?= $july ?></td>
                <td><?= number_format($july, 2) ?></td>
            </tr>
            <tr>
                <td>August</td>
                <td><?= $aug ?></td>
                <td><?= number_format($aug, 2) ?></td>
            </tr>
            <tr>
                <td>September</td>
                <td><?= $sept ?></td>
                <td><?= number_format($sept, 2) ?></td>
            </tr>
            <tr>
                <td>October</td>
                <td><?= $oct ?></td>
                <td><?= number_format($oct, 2) ?></td>
            </tr>
            <tr>
                <td>November</td>
                <td><?= $nov ?></td>
                <td><?= number_format($nov, 2) ?></td>
            </tr>
            <tr>
                <td>December</td>
                <td><?= $dec ?></td>
                <td><?= number_format($dec, 2) ?></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    // Trigger the print dialog when the print button is clicked
    document.getElementById('printButton').addEventListener('click', function () {
        // Show the print section
        document.getElementById('printSection').style.display = 'block';

        // Open the print dialog
        window.print();

        // Hide the print section after printing
        setTimeout(function () {
            document.getElementById('printSection').style.display = 'none';
        }, 1000);
    });
</script>

<script>
    // Your existing graph code remains the same
    var xValues = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var yValues = [<?= $january ?>, <?= $february ?>, <?= $mar ?>, <?= $apr ?>, <?= $may ?>, <?= $june ?>, <?= $july ?>, <?= $aug ?>, <?= $sept ?>, <?= $oct ?>, <?= $nov ?>, <?= $dec ?>];
    var barColors = ["#fb4c44", "#5386df", "#007b12", "#fb4c44", "#5386df", "#007b12", "#fb4c44", "#5386df", "#007b12", "#fb4c44", "#5386df", "#007b12"];

    new Chart("barChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: false
            }
        }
    });
</script>

<?php
    require 'includes/footer.php';
    require 'includes/layout-foot.php';
}
?>
