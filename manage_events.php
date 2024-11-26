<?php
// Include database connection
include('db_connection.php');

// Fetch upcoming events
$query = "SELECT * FROM upcoming_events JOIN events ON upcoming_events.event_id = events.id";
$result = mysqli_query($conn, $query);
?>

<table>
    <tr>
        <th>Event Name</th>
        <th>Actions</th>
    </tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['event_name']; ?></td>
        <td>
            <a href="edit_event.php?id=<?php echo $row['event_id']; ?>">Edit</a>
            <a href="delete_event.php?id=<?php echo $row['event_id']; ?>">Delete</a>
            <a href="move_event.php?id=<?php echo $row['event_id']; ?>">Move to Previous Events</a>
        </td>
    </tr>
<?php } ?>
</table>
