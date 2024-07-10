<?php
include ('includes/config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM ticket_Category WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);

    if ($query->execute()) {
        echo "Category deleted successfully.";
    } else {
        echo "Error: Could not delete category.";
    }

    header("Location: mange_ticket_category.php");
    exit;
}
?>