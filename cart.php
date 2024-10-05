<?php
session_start();
include ('includes/config.php');

// Redirect to login if user is not logged in
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Fetch user's full name from session
$user_name = clean($_SESSION['user_name']);

// Initialize cart session if not already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$sql = "SELECT id, name, rate FROM ticket_category WHERE isActive = 1";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_amount = 0;
foreach ($_SESSION['cart'] as $ticket) {
    $ticket_category_id = clean($ticket['ticket_category_id']);
    $number_of_passengers = clean($ticket['number_of_passengers']);

    $rate = 0;
    foreach ($categories as $category) {
        if ($category['id'] == $ticket_category_id) {
            $rate = $category['rate'];
            break;
        }
    }

    $subtotal = $rate * $number_of_passengers;
    $total_amount += $subtotal;
}

if (isset($_POST['save_cart'])) {
    foreach ($_SESSION['cart'] as $ticket) {
        $ticket_category_id = clean($ticket['ticket_category_id']);
        $number_of_passengers = clean($ticket['number_of_passengers']);

        // Assuming you have a valid $ticket_category_id and $user_name
        $sql = "INSERT INTO ticker (name, status, number_of_passengers, ticket_category_id) VALUES (:name, :status, :number_of_passengers, :ticket_category_id)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $user_name, PDO::PARAM_STR);
        $stmt->bindValue(':status', 'Booked', PDO::PARAM_STR);
        $stmt->bindParam(':number_of_passengers', $number_of_passengers, PDO::PARAM_INT);
        $stmt->bindParam(':ticket_category_id', $ticket_category_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Optionally, clear the cart or show a success message
            echo "<script>alert('Tickets saved successfully.');</script>";
            unset($_SESSION['cart']); // Clear cart after saving
        } else {
            echo "<script>alert('Error: Could not save tickets.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Cart</h2>
        <?php if (!empty($_SESSION['cart']) && !empty($categories)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ticket Category</th>
                        <th>Number of Passengers</th>
                        <th>Rate</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $index => $ticket): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php
                                $category_found = false;
                                foreach ($categories as $category) {
                                    if ($category['id'] == $ticket['ticket_category_id']) {
                                        echo htmlentities($category['name']);
                                        $category_found = true;
                                        break;
                                    }
                                }
                                if (!$category_found) {
                                    echo 'Category not found';
                                }
                                ?>
                            </td>
                            <td><?php echo clean($ticket['number_of_passengers']); ?></td>
                            <td><?php echo number_format($rate, 2); ?></td>
                            <td><?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total Amount:</strong></td>
                        <td><strong><?php echo number_format($total_amount, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
            <form method="post">
                <button type="submit" name="save_cart" class="btn btn-primary">Save Tickets</button>
            </form>
        <?php else: ?>
            <p>No tickets added to the cart or categories not available.</p>
        <?php endif; ?>
        <br>
        <a href="ticket.php" class="btn btn-secondary">Back to Ticket Selection</a>
    </div>
</body>

</html>