<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['type']) && isset($_POST['id'])) {
    $type = $_POST['type'];
    $id = intval($_POST['id']);

    switch ($type) {
        case 'highlight':
            $table = 'highlight_carousel';
            break;
        case 'athlete':
            $table = 'top_athletes';
            break;
        case 'leader':
            $table = 'community_leaders';
            break;
        case 'partner':
            $table = 'partnerships';
            break;
        default:
            exit("Invalid type");
    }

    $conn->query("DELETE FROM $table WHERE id=$id");
}

header("Location: editInlinePage.php");
?>
