<?php
// Get eventID and customerID from query parameters
$eventID = $_GET['eventID'];
$customerID = $_GET['customerID'];

// API URLs
$eventApiUrl = "https://localhost:7040/api/Event/$eventID";
$customerApiUrl = "https://localhost:7040/api/Customer/$customerID";
$salesApiUrl = "https://localhost:7040/api/Sale/customer/$customerID";
$ticketApiUrl = "https://localhost:7040/api/Ticket/";

// Initialize cURL session and fetch event details
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $eventApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification
$eventResponse = curl_exec($ch);
curl_close($ch);

// Initialize cURL session and fetch customer details
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $customerApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification
$customerResponse = curl_exec($ch);
curl_close($ch);

// Initialize cURL session and fetch sales details
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $salesApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification
$salesResponse = curl_exec($ch);
curl_close($ch);

// Decode responses
$eventData = json_decode($eventResponse, true);
$customerData = json_decode($customerResponse, true);
$salesData = json_decode($salesResponse, true);

$baseImageUrl = "https://localhost:7040/";
// Fetch ticket details for each sale
$tickets = [];
$totalAmount = 0;
foreach ($salesData as $sale) {
    $ticketID = $sale['ticketID'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ticketApiUrl . $ticketID);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL certificate verification
    $ticketResponse = curl_exec($ch);
    curl_close($ch);
    $ticketData = json_decode($ticketResponse, true);
    $tickets[] = array_merge($sale, $ticketData);
    $totalAmount += $ticketData['price'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Receipt</title>
    <link rel="stylesheet" href="../assets/css/ticketstyle.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script>
</head>
<body>
<?php include("../header.php") ?> 
    <main id="main-content">
        <div id="receipt" class="receipt">
            <div class="receipt-header">
                <h1>Receipt</h1>
                <h2><?php echo $eventData['eventName']; ?></h2>
                <p><?php echo $eventData['date']; ?> |  <?php echo $eventData['time']; ?></p>
                <p> <?php echo $eventData['location']; ?></p>
            </div>
            <div class="customer-details">
                <h2>Customer Details</h2>
                <p>Name: <?php echo $customerData['name']; ?></p>
                <p>Email: <?php echo $customerData['email']; ?></p>
                <p>NIC: <?php echo $customerData['nic']; ?></p>
            </div>
            <div class="receipt-body">
                <div class="ticket-details">
                    <h2>Ticket Details</h2>
                    <?php foreach ($tickets as $index => $ticket): ?>
                    <div class="ticket">
                        <div class="ticket-info">
                            <p>Category: <?php echo $ticket['ticketType']; ?></p>
                            <p>Ticket Number: #<?php echo str_pad($ticket['ticketNumber'], 3, '0', STR_PAD_LEFT); ?></p>
                        </div>
                        <div class="ticket-price">
                            <p>Rs <?php echo number_format($ticket['price'], 2); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="total-amount">
                <h2>Total Amount</h2>
                <p>Rs <?php echo number_format($totalAmount, 2); ?></p>
            </div>
        </div>
        <button id="download">Download as PDF</button>
    </main>
</body>
<?php include("../footer.php") ?>
<script>
    $(document).ready(function() {
        document.getElementById('download').addEventListener('click', function() {
            const mainElement = document.getElementById('receipt');
            html2canvas(mainElement, { 
                scale: 2,
                useCORS: true
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/jpeg', 0.98);
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgProps = pdf.getImageProperties(imgData);
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
                pdf.save('booking-confirmation.pdf');
            }).catch(function(error) {
                console.error('Error generating canvas:', error);
            });
        });
    });
</script>
</html>