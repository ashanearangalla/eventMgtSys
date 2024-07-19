<?php
// Fetch events from the API
$url = "https://localhost:7040/api/Event";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
if ($response === FALSE) {
    die('cURL Error: ' . curl_error($ch));
}
curl_close($ch);
$events = json_decode($response, true);
if ($events === null) {
    die('Error decoding JSON');
}
$baseImageUrl = "https://localhost:7040/";

function formatDate($date) {
    $timestamp = strtotime($date);
    return date('M j', $timestamp); // Format: 'Jun 1', 'Jul 15', etc.
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debra Events</title>
    <link rel="stylesheet" href="assets/css/stylesheet1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <style>
        /* Home Carousel */
    </style>
</head>

<body>
    <div class="navbar">
        <a href="index.php">
            <div class="heading"><p>Debra Events</p></div>
        </a>
        
        <div class="links">
            <ul class="nav-list">
                
                <li class="nav-item"><a href="login.php">Login</a></li>
                
            </ul>
        </div>
    </div>

    <!-- Home Carousel -->
    <section id="home" class="carousel">
        <h1 class="title-main">Debra Events</h1>
    </section>

    <!-- Outdoor Events Section -->
    <section class="events-display">
        <section id="outdoor-events" class="events-section">
            <div class="home-section-heading">
                <h2>Book Tickets</h2>
            </div>
            <div class="events-container">
                <div class="events">
                <?php foreach ($events as $event) : ?>
                    <a href="client/viewEvent.php?eventID=<?php echo htmlspecialchars($event['eventID']); ?>">
                        <div class="event-card">
                            <img src="<?php echo htmlspecialchars($baseImageUrl . $event['eventImage']); ?>" alt="Event Image" class="event-image">
                            <div class="event-details">
                                <div class="event-date-box"><p><?php echo htmlspecialchars(formatDate($event['date'])); ?></p></div>
                                <div class="event-info">
                                    <h2 class="event-name"><?php echo htmlspecialchars($event['eventName']); ?></h2>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>

                </div>
                
            </div>
            </section>
    </section>

    <?php include("footer.php") ?>

    <script>
        const leftArrowOutdoor = document.getElementById('left-arrow-outdoor');
        const rightArrowOutdoor = document.getElementById('right-arrow-outdoor');
        const outdoorContainer = document.querySelector('#outdoor-events .events-container');

        if (leftArrowOutdoor) {
            leftArrowOutdoor.addEventListener('click', () => {
                outdoorContainer.scrollLeft -= 500;
            });
        }

        if (rightArrowOutdoor) {
            rightArrowOutdoor.addEventListener('click', () => {
                outdoorContainer.scrollLeft += 500;
            });
        }
    </script>
</body>

</html>
