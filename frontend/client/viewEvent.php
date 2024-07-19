<?php
session_start();
?>

<?php
// Get eventID from query parameter
$eventID = isset($_GET['eventID']) ? $_GET['eventID'] : die('Event ID not specified.');

// API URLs
$eventUrl = "https://localhost:7040/api/Event/$eventID";
$ticketsUrl = "https://localhost:7040/api/Event/ticket/$eventID";

// Initialize cURL session for event details
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $eventUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$eventResponse = curl_exec($ch);
curl_close($ch);

// Initialize cURL session for ticket details
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ticketsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$ticketsResponse = curl_exec($ch);
curl_close($ch);

// Decode JSON responses to PHP arrays
$event = json_decode($eventResponse, true);
$tickets = json_decode($ticketsResponse, true);

// Check if decoding was successful
if ($event === null || $tickets === null) {
    die('Error decoding JSON');
}

// Define base URL for images
$baseImageUrl = "https://localhost:7040/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <link rel="stylesheet" href="../assets/css/stylesheet1.css">
    <script>
        function calculateTotal() {
            var total = 0;
            var selects = document.querySelectorAll('.ticket-table select');
            
            selects.forEach(function(select) {
                var quantity = parseInt(select.value);
                total += quantity;
            });
            
            document.querySelector('.total-amount').textContent = total;
        }
        
        window.onload = function() {
            var selects = document.querySelectorAll('.ticket-table select');
            selects.forEach(function(select) {
                select.addEventListener('change', calculateTotal);
            });
        };
    </script>
    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            display: none; /* Hide overlay by default */
        }
        .popup {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .popup h2 {
            margin: 0;
            font-size: 18px;
        }
        .popup .close {
            cursor: pointer;
            font-size: 20px;
        }
    </style>
</head>
<body>
<?php include("../header.php") ?> 
    <div class="container">
        <div class="event-con">
            <div class="event-details-container">
                <div class="event-details-large">
                    <h1 class="title"><?php echo htmlspecialchars($event['eventName']); ?></h1>
                    <p><?php echo htmlspecialchars($event['description']); ?></p>
                    <p>Date: <?php echo htmlspecialchars($event['date']); ?></p>
                    <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
                    <p>Time: <?php echo htmlspecialchars($event['time']); ?></p>
                    <h5 class="subtitle">Book Tickets</h5>
                    <form action="bookingDetails.php" method="POST" class="ticket-selection-container">
                        <input type="hidden" name="eventID" value="<?php echo htmlspecialchars($eventID); ?>">
                        
                        <?php foreach ($tickets as $ticket): ?>
                        <div class="ticket-category-container">
                            <div class="ticket-type-event"><p><?php echo htmlspecialchars($ticket['ticketType']); ?><p></div>
                            <div class="price-event"><p> LKR <?php echo htmlspecialchars($ticket['price']); ?></p></div>
                            <div class="quantity-selector-container">
                                <select class="quantity-selector" name="quantity[<?php echo htmlspecialchars($ticket['ticketID']); ?>]" id="quantity-<?php echo htmlspecialchars($ticket['ticketID']); ?>" onchange="calculateTotal()">
                                    <?php for ($i = 0; $i <= min(6, ($ticket['quantity'] - $ticket['sold'])); $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="proceed-button-container">
                            <button type="submit" class="proceed-button">Proceed</button>
                        </div>
                    </form>
                </div>
                <img src="<?php echo htmlspecialchars($baseImageUrl . $event['eventImage']); ?>" alt="Event Image" class="event-image-large">
            </div>
        </div>
    </div>
    <?php include("../footer.php") ?>

    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="overlay" id="popup-overlay">
                <div class="popup">
                    <span class="close" id="popup-close">&times;</span>
                    <h2>' . $_SESSION['message'] . '</h2>
                </div>
              </div>';
        unset($_SESSION['message']);
    }
    ?>
   
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