<?php
session_start();
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    // Function to fetch total payments for a specific month and year
    function Sales($month, $year, $conn)
    {
        $stmt = $conn->prepare("SELECT SUM(payment) AS TOTAL FROM booking WHERE YEAR(date_created) = :year" . ($month ? " AND MONTH(date_created) = :month" : ""));
        $stmt->bindParam(':year', $year);
        if ($month) {
            $stmt->bindParam(':month', $month);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['TOTAL'] ?: 0;
    }

    // Get the selected year and month from the dropdown
    $currentYear = date('Y');
    $selectedYear = isset($_GET['yearFilter']) ? $_GET['yearFilter'] : $currentYear;
    $selectedMonth = isset($_GET['monthFilter']) ? $_GET['monthFilter'] : '';

    // Fetch data for each month of the selected year
    $january = Sales('01', $selectedYear, $dbh);
    $february = Sales('02', $selectedYear, $dbh);
    $march = Sales('03', $selectedYear, $dbh);
    $april = Sales('04', $selectedYear, $dbh);
    $may = Sales('05', $selectedYear, $dbh);
    $june = Sales('06', $selectedYear, $dbh);
    $july = Sales('07', $selectedYear, $dbh);
    $august = Sales('08', $selectedYear, $dbh);
    $september = Sales('09', $selectedYear, $dbh);
    $october = Sales('10', $selectedYear, $dbh);
    $november = Sales('11', $selectedYear, $dbh);
    $december = Sales('12', $selectedYear, $dbh);

    $filteredMonth = $selectedMonth ? Sales($selectedMonth, $selectedYear, $dbh) : null;

    // Total Payment Calculation for the selected year
    $total = $january + $february + $march + $april + $may + $june + $july + $august + $september + $october + $november + $december;

    require './includes/layout-head.php';
?>

<div class="card mt-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Booking Report</h3>
        </div>

        <!-- Filter Dropdown -->
        <form method="GET" action="" class="mt-3">
            <label for="yearFilter">Select Year:</label>
            <select id="yearFilter" name="yearFilter" class="form-select w-auto d-inline-block">
                <?php
                for ($year = $currentYear - 5; $year <= $currentYear; $year++) {
                    echo "<option value=\"$year\"" . ($selectedYear == $year ? ' selected' : '') . ">$year</option>";
                }
                ?>
            </select>

            <label for="monthFilter">Select Month:</label>
            <select id="monthFilter" name="monthFilter" class="form-select w-auto d-inline-block">
                <option value="">All Months</option>
                <option value="01" <?= $selectedMonth == '01' ? 'selected' : '' ?>>January</option>
                <option value="02" <?= $selectedMonth == '02' ? 'selected' : '' ?>>February</option>
                <option value="03" <?= $selectedMonth == '03' ? 'selected' : '' ?>>March</option>
                <option value="04" <?= $selectedMonth == '04' ? 'selected' : '' ?>>April</option>
                <option value="05" <?= $selectedMonth == '05' ? 'selected' : '' ?>>May</option>
                <option value="06" <?= $selectedMonth == '06' ? 'selected' : '' ?>>June</option>
                <option value="07" <?= $selectedMonth == '07' ? 'selected' : '' ?>>July</option>
                <option value="08" <?= $selectedMonth == '08' ? 'selected' : '' ?>>August</option>
                <option value="09" <?= $selectedMonth == '09' ? 'selected' : '' ?>>September</option>
                <option value="10" <?= $selectedMonth == '10' ? 'selected' : '' ?>>October</option>
                <option value="11" <?= $selectedMonth == '11' ? 'selected' : '' ?>>November</option>
                <option value="12" <?= $selectedMonth == '12' ? 'selected' : '' ?>>December</option>
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <!-- Chart -->
        <canvas id="barChart" style="width:100%;"></canvas>

        <!-- Print Button -->
        <a href="javascript:void(0);" class="float-end mt-3 btn btn-primary" id="printButton"><i class="fa fa-print"></i> Print</a>
    </div>
</div>

<!-- Printable Section -->
<div id="printSection" style="display:none;">
    <center>
        <div class="header-row" style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
            <img src="../images/Santa_Fe_Cebu.png" alt="logo" style="height: 100px; width: auto;">
            <div>
                <h1>Santa Fe Port TMS</h1>
                <p>Address: Talisay, Santa Fe, Bantayan Island, Cebu</p>
                <p>Contact: 0917800185</p>
                <p>Email: santafeport@gmail.com</p>
            </div>
        </div>
    </center>

    <center>
        <h3>Booking Report for Year: <?= $selectedYear ?><?= $selectedMonth ? " - " . date('F', mktime(0, 0, 0, $selectedMonth, 10)) : '' ?></h3>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Booked</th>
                    <th>Total Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($selectedMonth): ?>
                    <tr>
                        <td><?= date('F', mktime(0, 0, 0, $selectedMonth, 10)) ?></td>
                        <td><?= $filteredMonth ?></td>
                        <td><?= number_format($filteredMonth, 2) ?></td>
                    </tr>
                <?php else: ?>
                    <tr><td>January</td><td><?= $january ?></td><td><?= number_format($january, 2) ?></td></tr>
                    <tr><td>February</td><td><?= $february ?></td><td><?= number_format($february, 2) ?></td></tr>
                    <!-- Other months here -->
                    <tr><td>December</td><td><?= $december ?></td><td><?= number_format($december, 2) ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <h3>Total: <?= number_format($total, 2) ?></h3>
    </center>
</div>

<script>
// Same JS functionality as before, updated to handle years
</script>

<?php
    require 'includes/footer.php';
    require 'includes/layout-foot.php';
}
?>
