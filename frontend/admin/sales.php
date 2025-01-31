<?php
session_start();
?>


<?php
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

// Fetch commission data
$commissionUrl = 'https://localhost:7040/api/Commission';
$commissions = fetchData($commissionUrl);

// Function to fetch event name by event ID
function getEventName($eventID) {
    $eventUrl = 'https://localhost:7040/api/Event/' . $eventID;
    $eventData = fetchData($eventUrl);
    return $eventData['eventName'];
}

// Process and display commission data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debra Admin Panel - Event Overview</title>
    <link rel="stylesheet" href="../assets/css/stylead.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include("sidemenu.php"); ?>
        <div class="content">
        <div class="content-header">
            <h1>Sales</h1>
        </div>
            <div class="content-section">
                
                <table class="event-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Event Name</th>

                            <th>Commission Rate</th>
                            <th>Sales (Rs)</th>
                            <th>Commission Amount (Rs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($commissions) {
                            $count = 1;
                            foreach ($commissions as $commission) {
                                $eventName = getEventName($commission['eventID']);
                                $commissionAmount = $commission['commissionRate'] * $commission['totalSales'];
                                echo "<tr>
                                        <td>{$count}</td>
                                        <td>{$eventName}</td>

                                        <td>{$commission['commissionRate']}</td>
                                        <td>{$commission['totalSales']}</td>
                                        <td>{$commissionAmount}</td>
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
            <div class="bottom-box">
            <div class="bottom-button">
                
            </div>
        </div>
        </div>
    </div>
    <script src="../assets/js/admin.js"></script>
</body>
</html>
