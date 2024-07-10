<?php
include ('includes/config.php');

$sql = "SELECT id, name FROM ticket_Category";
$query = $dbh->prepare($sql);
$query->execute();
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

$name = $number_of_passengers = $ticket_category_name = '';
$name_err = $number_of_passengers_err = $ticket_category_name_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter a name for the ticket.";
    } else {
        $name = trim($_POST["name"]);
    }

    if (empty(trim($_POST["number_of_passengers"]))) {
        $number_of_passengers_err = "Please enter number of passengers.";
    } else {
        $number_of_passengers = trim($_POST["number_of_passengers"]);
    }

    if (empty(trim($_POST["ticket_category_name"]))) {
        $ticket_category_name_err = "Please select a ticket category.";
    } else {
        $ticket_category_name = trim($_POST["ticket_category_name"]);
        $sql = "SELECT id FROM ticket_Category WHERE name = :name";
        $query = $dbh->prepare($sql);
        $query->bindParam(":name", $ticket_category_name, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $ticket_category_id = $result['id'];
        } else {
            $ticket_category_name_err = "Invalid category selected.";
        }
    }

    if (empty($name_err) && empty($number_of_passengers_err) && empty($ticket_category_name_err)) {
        $sql = "INSERT INTO ticker (name, status, number_of_passengers, ticket_category_id) VALUES (:name, 0, :number_of_passengers, :ticket_category_id)";

        if ($stmt = $dbh->prepare($sql)) {
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":number_of_passengers", $param_number_of_passengers, PDO::PARAM_INT);
            $stmt->bindParam(":ticket_category_id", $param_ticket_category_id, PDO::PARAM_INT);

            $param_name = $name;
            $param_number_of_passengers = $number_of_passengers;
            $param_ticket_category_id = $ticket_category_id;

            if ($stmt->execute()) {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Created!',
                                text: 'Ticket created successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location = 'mange_ticket.php';
                            });
                        });
                      </script>";
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        unset($stmt);
    }

    unset($dbh);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create New Ticket</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <div class="container">
        <h2>Create New Ticket</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Ticket Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo $name; ?>" required>
                <span class="text-danger"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label for="number_of_passengers">Number of Passengers:</label>
                <input type="number" id="number_of_passengers" name="number_of_passengers" class="form-control"
                    value="<?php echo $number_of_passengers; ?>" required>
                <span class="text-danger"><?php echo $number_of_passengers_err; ?></span>
            </div>
            <div class="form-group">
                <label for="ticket_category_name">Ticket Category:</label>
                <select id="ticket_category_name" name="ticket_category_name" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['name']; ?>" <?php echo ($ticket_category_name == $category['name']) ? 'selected' : ''; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                    <?php } ?>
                </select>
                <span class="text-danger"><?php echo $ticket_category_name_err; ?></span>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</body>

</html>