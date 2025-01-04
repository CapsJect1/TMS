<?php
session_start();
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    // Get selected month and year (if set in the GET request)
    $selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
    $selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

    // Function to fetch total payments for the selected month and year
    function Sales($month, $year, $conn)
    {
        $stmt = $conn->query("SELECT SUM(payment) AS TOTAL FROM booking WHERE MONTH(date_created) = '$month' AND YEAR(date_created) = '$year' ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['TOTAL'];
    }

    // Fetch the sales data for the selected month
    $totalSales = Sales($selectedMonth, $selectedYear, $dbh);

    require './includes/layout-head.php';
?>

<div class="card mt-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h3>Booking Report for <?= $selectedMonth ?>, <?= $selectedYear ?></h3>

            <!-- Dropdown to filter month and year -->
            <form method="get" class="form-inline">
                <select name="month" class="form-control" required>
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

                <select name="year" class="form-control" required>
                    <option value="<?= date('Y') ?>" <?= $selectedYear == date('Y') ? 'selected' : '' ?>><?= date('Y') ?></option>
                    <option value="<?= date('Y') - 1 ?>" <?= $selectedYear == (date('Y') - 1) ? 'selected' : '' ?>><?= date('Y') - 1 ?></option>
                    <option value="<?= date('Y') + 1 ?>" <?= $selectedYear == (date('Y') + 1) ? 'selected' : '' ?>><?= date('Y') + 1 ?></option>
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

        <!-- This is where the pie chart will be displayed -->
        <canvas id="pieChart" style="width:100%;"></canvas>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script>
    // Pie chart data and colors
    var xValues = ["<?= date('F', mktime(0, 0, 0, $selectedMonth, 10)) ?>"];
    var yValues = [<?= $totalSales ?>];
    var pieColors = ["#FF5733"]; // Use a single color for the selected month

    // Create pie chart with filtered data
    new Chart("pieChart", {
        type: "pie",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: pieColors,
                data: yValues
            }]
        },
        options: {
            title: {
                display: true,
                text: "Booking Report for <?= $selectedMonth ?>, <?= $selectedYear ?>"
            }
        }
    });
</script>

<?php
    require 'includes/footer.php';
    require 'includes/layout-foot.php';
}
?>
