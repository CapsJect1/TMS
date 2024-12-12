<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    // Function to fetch total bookings and total payment for a given month
    function Sales($month, $conn)
    {
        $year = date('Y');
        $stmt = $conn->query("SELECT COUNT(*) AS total_booked, SUM(payment) AS total_payment FROM booking WHERE MONTH(date_created) = '$month' AND YEAR(date_created) = '$year' ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    // Array of month numbers and their names
    $months = [
        '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
        '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', 
        '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
    ];

    // Fetch data for each month
    $monthly_data = [];
    foreach ($months as $key => $month) {
        $monthly_data[$month] = Sales($key, $dbh);
    }

    require './includes/layout-head.php';
?>

<div class="card mt-4">
    <div class="card-body">
        <h3>Booking Report</h3>

        <!-- Print Button -->
        <button class="float-end mt-3 btn btn-primary" id="printButton"><i class="fa fa-print"></i> Print</button>

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
                <?php foreach ($monthly_data as $month => $data): ?>
                    <tr>
                        <td><?= $month ?></td>
                        <td><?= $data['total_booked'] ?></td>
                        <td><?= number_format($data['total_payment'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
