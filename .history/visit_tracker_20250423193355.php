<?php
session_start(); // Start or resume the session

if (!isset($_SESSION['has_visited'])) {
    // User hasn't been counted yet in this session
    $visit_conn = new mysqli("localhost", "root", "", "basf_visits");

    // Make sure the connection was successful
    if ($visit_conn->connect_error) {
        die("Connection failed: " . $visit_conn->connect_error);
    }

    // Log the visit
    $visit_conn->query("INSERT INTO visit_counter (visited_at) VALUES (NOW())");

    // Mark this session as counted
    $_SESSION['has_visited'] = true;

    $visit_conn->close();
}
?>
