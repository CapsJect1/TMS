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
        $stmt = $conn->query("SELECT COUNT(*) AS total_booked, SUM(payment) AS total_payment FROM booking WHERE MONTH(date_created) = '$month' AND YEAR(date_created) = '$year' ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
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
    $total = $january['total_payment'] + $february['total_payment'] + $mar['total_payment'] + $june['total_payment'] + $july['total_payment'] + $aug['total_payment'] + $sept['total_payment'] + $oct['total_payment'] + $nov['total_payment'] + $dec['total_payment'];

    require './includes/layout-head.php';
?>

<div class="card mt-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Booking Report</h3>
            <h3>Total: <?= number_format($total, 2) ?></h3>
        </div>

        <!-- Table for Displaying the Report -->
        <table class="table" id="reportTable">
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
                    <td><?= $january['total_booked'] ?></td>
                    <td><?= number_format($january['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>February</td>
                    <td><?= $february['total_booked'] ?></td>
                    <td><?= number_format($february['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>March</td>
                    <td><?= $mar['total_booked'] ?></td>
                    <td><?= number_format($mar['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>April</td>
                    <td><?= $apr['total_booked'] ?></td>
                    <td><?= number_format($apr['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>May</td>
                    <td><?= $may['total_booked'] ?></td>
                    <td><?= number_format($may['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>June</td>
                    <td><?= $june['total_booked'] ?></td>
                    <td><?= number_format($june['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>July</td>
                    <td><?= $july['total_booked'] ?></td>
                    <td><?= number_format($july['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>August</td>
                    <td><?= $aug['total_booked'] ?></td>
                    <td><?= number_format($aug['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>September</td>
                    <td><?= $sept['total_booked'] ?></td>
                    <td><?= number_format($sept['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>October</td>
                    <td><?= $oct['total_booked'] ?></td>
                    <td><?= number_format($oct['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>November</td>
                    <td><?= $nov['total_booked'] ?></td>
                    <td><?= number_format($nov['total_payment'], 2) ?></td>
                </tr>
                <tr>
                    <td>December</td>
                    <td><?= $dec['total_booked'] ?></td>
                    <td><?= number_format($dec['total_payment'], 2) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Print Button -->
        <button class="float-end mt-3 btn btn-primary" id="printButton"><i class="fa fa-print"></i> Print</button>
    </div>
</div>

<script>
    // Trigger the print dialog when the print button is clicked
    document.getElementById('printButton').addEventListener('click', function () {
        window.print();  // This will open the print dialog with the current page
    });
</script>

<!-- Print-Friendly Styles -->
<style>
    @media print {
        body {
            font-family: Arial, sans-serif;
        }

        /* Hide elements that should not be printed */
        .float-end, .btn {
            display: none;
        }

        /* Ensure the table looks nice when printed */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        /* Optional: You can add a page break before each section, but this is usually optional */
        .card {
            page-break-before: always;
            page-break-after: always;
        }
    }
</style>

<?php
    require 'includes/footer.php';
    require 'includes/layout-foot.php';
}
?>
