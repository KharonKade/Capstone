<?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname_content = "basf_content";
    $dbname_events = "basf_events";

    $conn_content = new mysqli($servername, $username, $password, $dbname_content);
    $conn_events = new mysqli($servername, $username, $password, $dbname_events);

    if ($conn_content->connect_error || $conn_events->connect_error) {
        die("Connection failed: " . ($conn_content->connect_error ?: $conn_events->connect_error));
    }
    ?>

    <section class="inline-content">
        <div class="middle-content">
            <h2 id="about-us">About Us</h2>
            <?php
            $result = $conn_content->query("SELECT content FROM content WHERE section='about_us'");
            if ($row = $result->fetch_assoc()) {
                echo '<p>' . $row['content'] . '</p>';
            } else {
                echo "<p>About Us content not found.</p>";
            }
            ?>
            <button class="edit-btn" onclick="toggleForm('aboutUsForm')">Edit</button>
            <div id="aboutUsForm" class="hidden-form">
                <form action="handle_edit.php" method="post">
                    <textarea name="about_us_content"> <?php echo $row['content']; ?> </textarea>
                    <button type="submit">Update</button>
                </form>
            </div>
        </div>
    </section>

    <section class="highlight-carousel-section">
        <h1 class="carousel-heading">Highlight</h1>
        <div class="carousel-container">
            <div class="carousel">
                <?php
                $result = $conn_content->query("SELECT id, video, title, description FROM highlight_carousel");

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="carousel-item">'
                            . '<video src="' . $row["video"] . '" autoplay muted loop></video>'
                            . '<button onclick="toggleForm('highlightForm' . $row['id'] . '')">Edit</button>'
                            . '<div id="highlightForm' . $row['id'] . '" class="hidden-form">'
                            . '<form action="handle_edit.php" method="post">'
                            . '<input type="hidden" name="highlight_id" value="' . $row['id'] . '">'
                            . '<input type="text" name="title" value="' . $row['title'] . '">'
                            . '<textarea name="description">' . $row['description'] . '</textarea>'
                            . '<button type="submit">Update</button>'
                            . '</form></div>'
                            . '</div>';
                    }
                } else {
                    echo '<p class="no-videos">No highlight videos available at the moment.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <div class="players" id="top-athletes">
        <h2>Top Athletes</h2>
        <div class="slider">
            <?php
            $result = $conn_content->query("SELECT id, name, description, image FROM top_athletes");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="slides">'
                        . '<div class="content">'
                        . '<h1>' . $row["name"] . '</h1>'
                        . '<p>' . $row["description"] . '</p>'
                        . '<button onclick="toggleForm('athleteForm' . $row['id'] . '')">Edit</button>'
                        . '<div id="athleteForm' . $row['id'] . '" class="hidden-form">'
                        . '<form action="handle_edit.php" method="post">'
                        . '<input type="hidden" name="athlete_id" value="' . $row['id'] . '">'
                        . '<input type="text" name="name" value="' . $row['name'] . '">'
                        . '<textarea name="description">' . $row['description'] . '</textarea>'
                        . '<button type="submit">Update</button>'
                        . '</form></div>'
                        . '</div>'
                        . '</div>';
                }
            } else {
                echo '<p class="no-data">No athletes have been added yet.</p>';
            }
            ?>
        </div>
    </div>

    <section id="community-leaders">
        <h2>Community Leaders</h2>
        <div class="leaders-list">
            <?php
            $result = $conn_content->query("SELECT id, name, role, image FROM community_leaders");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="leader-item">'
                        . '<h3>' . $row["name"] . '</h3>'
                        . '<p>' . $row["role"] . '</p>'
                        . '<button onclick="toggleForm('leaderForm' . $row['id'] . '')">Edit</button>'
                        . '<div id="leaderForm' . $row['id'] . '" class="hidden-form">'
                        . '<form action="handle_edit.php" method="post">'
                        . '<input type="hidden" name="leader_id" value="' . $row['id'] . '">'
                        . '<input type="text" name="name" value="' . $row['name'] . '">'
                        . '<input type="text" name="role" value="' . $row['role'] . '">'
                        . '<button type="submit">Update</button>'
                        . '</form></div>'
                        . '</div>';
                }
            } else {
                echo '<p>No community leaders available.</p>';
            }
            ?>
        </div>
    </section>

    <section id="partnerships">
        <h2>Partnerships</h2>
        <div class="partners-list">
            <?php
            $result = $conn_content->query("SELECT id, name, logo FROM partnerships");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="partner-item">'
                        . '<h3>' . $row["name"] . '</h3>'
                        . '<button onclick="toggleForm('partnerForm' . $row['id'] . '')">Edit</button>'
                        . '<div id="partnerForm' . $row['id'] . '" class="hidden-form">'
                        . '<form action="handle_edit.php" method="post">'
                        . '<input type="hidden" name="partner_id" value="' . $row['id'] . '">'
                        . '<input type="text" name="name" value="' . $row['name'] . '">'
                        . '<button type="submit">Update</button>'
                        . '</form></div>'
                        . '</div>';
                }
            } else {
                echo '<p>No partnerships available.</p>';
            }
            ?>
        </div>
    </section>

    <?php
    $conn_content->close();
    $conn_events->close();
    ?>
