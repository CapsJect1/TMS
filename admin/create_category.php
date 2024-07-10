<?php
include ('includes/config.php');

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $rate = $_POST['rate'];

    $sql = "INSERT INTO ticket_Category (name, description, rate) VALUES (:name, :description, :rate)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':description', $description, PDO::PARAM_STR);
    $query->bindParam(':rate', $rate, PDO::PARAM_STR);

    if ($query->execute()) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Created!',
                        text: 'Category created successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location = 'mange_ticket_category.php';
                    });
                });
              </script>";
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Could not create category.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Category</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <div class="container">
        <h2>Create New Category</h2>
        <form method="post" action="create_category.php">
            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="rate">Rate:</label>
                <input type="text" id="rate" name="rate" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</body>

</html>