<?php
include ('includes/config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM ticket_Category WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $rate = $_POST['rate'];

        $sql = "UPDATE ticket_Category SET name = :name, description = :description, rate = :rate WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':rate', $rate, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);

        if ($query->execute()) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: 'Category updated successfully.',
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
                            text: 'Could not update category.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Category</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <div class="container">
        <h2>Update Category</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="name">Category Name:</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="<?php echo htmlentities($result->name); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"
                    class="form-control"><?php echo htmlentities($result->description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="rate">Rate:</label>
                <input type="text" id="rate" name="rate" class="form-control"
                    value="<?php echo htmlentities($result->rate); ?>" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>

</html>