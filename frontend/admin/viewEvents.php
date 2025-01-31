<?php
session_start();

// Function to fetch data from API using cURL
function fetchData($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

// Function to send a DELETE request to the API
function deleteData($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

// Check if delete request is made
if (isset($_POST['delete'])) {
    $eventID = $_POST['eventID'];
    $deleteUrl = 'https://localhost:7040/api/Event/' . $eventID;
    deleteData($deleteUrl);

    // Reload the page to reflect changes
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch events data
$eventsUrl = 'https://localhost:7040/api/Event';
$events = fetchData($eventsUrl);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debra Admin Panel - Event Overview</title>
    <link rel="stylesheet" href="../assets/css/stylead.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include("sidemenu.php"); ?>

        <div class="content">
            <div class="content-header">
                <h1>Events</h1>
            </div>
            
                <div class="content-section">
                    <table class="event-table" >
                        <thead >
                            <tr>
                                <th>No</th>
                                <th>Event Name</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($events) {
                                $count = 1;
                                foreach ($events as $event) {
                                    echo "<tr>
                                            <td>{$count}</td>
                                            <td>{$event['eventName']}</td>
                                            <td>{$event['date']}</td>
                                            <td>{$event['location']}</td>
                                            <td>{$event['description']}</td>
                                            <td>
                                                <form method='post' action=''>
                                                    <input type='hidden' name='eventID' value='{$event['eventID']}'>
                                                    <button type='submit' name='delete' class='delete-button'>Delete</button>
                                                </form>
                                            </td>
                                        </tr>";
                                    $count++;
                                }
                            } else {
                                echo "<tr><td colspan='6'>No data available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
        
        
    </div>
    <script src="../assets/js/admin.js"></script>
</body>
</html>
