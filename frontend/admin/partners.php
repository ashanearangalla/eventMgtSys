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

// Function to toggle partner status via API using cURL
function togglePartnerStatus($partnerID) {
    $url = "https://localhost:7040/api/Partner/toggleStatus/$partnerID";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

// Handle delete request
if (isset($_POST['delete_partner'])) {
    $partnerID = $_POST['partnerID'];
    $deleteUrl = 'https://localhost:7040/api/Partner/' . $partnerID;
    deleteData($deleteUrl);

    // Reload the page to reflect changes
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle toggle status request
if (isset($_POST['toggle_status'])) {
    $partnerID = $_POST['partner_id'];
    $response = togglePartnerStatus($partnerID);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch partner data
$partnerUrl = 'https://localhost:7040/api/Partner';
$partners = fetchData($partnerUrl);

// Process and display partner data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debra Admin Panel - Partner Overview</title>
    <link rel="stylesheet" href="../assets/css/stylead.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .partner-table {
            width: 95%;
            margin-top: 40px;
        }
        .partner-table th, .partner-table td {
            border: 1px solid #ccc;
            padding: 20px;
            font-size: 18px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include("sidemenu.php"); ?>
        <div class="content">
            <div class="content-header">
                <h1>Partners</h1>
            </div>
            <div class="content-section">
                <table class="event-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Partner Name</th>
                            <th>Contact Info</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Registered Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($partners) {
                            $count = 1;
                            foreach ($partners as $partner) {
                                $status = $partner['status'];
                                $toggleButton = ($status == 'Active') ? 
                                    "<button type='submit' name='toggle_status' class='toggle-button' value='Deactivate'>Deactivate</button>" :
                                    "<button type='submit' name='toggle_status' class='toggle-button' value='Activate'>Activate</button>";

                                echo "<tr>
                                        <td>{$count}</td>
                                        <td>{$partner['name']}</td>
                                        <td>{$partner['contactInfo']}</td>
                                        <td>{$partner['address']}</td>
                                        <td>{$partner['email']}</td>
                                        <td>{$partner['registeredDate']}</td>
                                        <td>{$status}</td>
                                        <td>
                                            <form method='POST' action=''>
                                                <input type='hidden' name='partner_id' value='{$partner['partnerID']}'>
                                                {$toggleButton}
                                            </form>
                                            <form method='POST' action='' style='margin-top: 10px;'>
                                                <input type='hidden' name='partnerID' value='{$partner['partnerID']}'>
                                                <button type='submit' name='delete_partner' class='delete-button'>Delete</button>
                                            </form>
                                        </td>
                                      </tr>";
                                $count++;
                            }
                        } else {
                            echo "<tr><td colspan='8'>No data available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Display popup if it exists
        document.addEventListener('DOMContentLoaded', function() {
            const popupOverlay = document.getElementById('popup-overlay');
            const popupClose = document.getElementById('popup-close');

            if (popupOverlay) {
                popupOverlay.style.display = 'flex';
                popupClose.addEventListener('click', function() {
                    popupOverlay.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>