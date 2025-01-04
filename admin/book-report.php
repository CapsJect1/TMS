<?php
session_start();
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    // Function to fetch total payments for each month or specific month
    function Sales($month, $conn)
    {
        $year = date('Y');
        $stmt = $conn->prepare("SELECT SUM(payment) AS TOTAL FROM booking WHERE YEAR(date_created) = :year" . ($month ? " AND MONTH(date_created) = :month" : ""));
        $stmt->bindParam(':year', $year);
        if ($month) {
            $stmt->bindParam(':month', $month);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['TOTAL'] ?: 0;
    }

    // Get the selected month from the dropdown
    $selectedMonth = isset($_GET['monthFilter']) ? $_GET['monthFilter'] : '';

    // Fetch data
    $january = Sales('01', $dbh);
    $february = Sales('02', $dbh);
    $march = Sales('03', $dbh);
    $april = Sales('04', $dbh);
    $may = Sales('05', $dbh);
    $june = Sales('06', $dbh);
    $july = Sales('07', $dbh);
    $august = Sales('08', $dbh);
    $september = Sales('09', $dbh);
    $october = Sales('10', $dbh);
    $november = Sales('11', $dbh);
    $december = Sales('12', $dbh);

    $filteredMonth = $selectedMonth ? Sales($selectedMonth, $dbh) : null;

    // Total Payment Calculation
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
                    <tr><td>March</td><td><?= $march ?></td><td><?= number_format($march, 2) ?></td></tr>
                    <tr><td>April</td><td><?= $april ?></td><td><?= number_format($april, 2) ?></td></tr>
                    <tr><td>May</td><td><?= $may ?></td><td><?= number_format($may, 2) ?></td></tr>
                    <tr><td>June</td><td><?= $june ?></td><td><?= number_format($june, 2) ?></td></tr>
                    <tr><td>July</td><td><?= $july ?></td><td><?= number_format($july, 2) ?></td></tr>
                    <tr><td>August</td><td><?= $august ?></td><td><?= number_format($august, 2) ?></td></tr>
                    <tr><td>September</td><td><?= $september ?></td><td><?= number_format($september, 2) ?></td></tr>
                    <tr><td>October</td><td><?= $october ?></td><td><?= number_format($october, 2) ?></td></tr>
                    <tr><td>November</td><td><?= $november ?></td><td><?= number_format($november, 2) ?></td></tr>
                    <tr><td>December</td><td><?= $december ?></td><td><?= number_format($december, 2) ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <h3>Total: <?= number_format($total, 2) ?></h3>
    </center>
</div>

<script>
document.getElementById('printButton').addEventListener('click', function () {
    document.querySelector('form').style.display = 'none';
    document.querySelector('.card').style.display = 'none';
    document.getElementById('printButton').style.display = 'none';
    document.getElementById('printSection').style.display = 'block';
    window.print();
    setTimeout(function () {
        document.getElementById('printSection').style.display = 'none';
        document.querySelector('.card').style.display = 'block';
        document.querySelector('form').style.display = 'block';
        document.getElementById('printButton').style.display = 'block';
    }, 1000);
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script>
    var xValues = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var yValues = [<?= $january ?>, <?= $february ?>, <?= $march ?>, <?= $april ?>, <?= $may ?>, <?= $june ?>, <?= $july ?>, <?= $august ?>, <?= $september ?>, <?= $october ?>, <?= $november ?>, <?= $december ?>];
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
            legend: { display: false },
            title: { display: false }
        }
    });
</script>

<?php
    require 'includes/footer.php';
    require 'includes/layout-foot.php';
}
?>
