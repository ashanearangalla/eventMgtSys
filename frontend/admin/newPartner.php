<?php
session_start();


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
                <h1>Add Event</h1>
            </div>

            <div class="content-section-body">
                <div id="create-partner" class="content-section-form">
                    <form style="width: 700px;" id="partner-form" action="addPartner.php" method="POST" enctype="multipart/form-data">
                        <div class="form-partner">
                            <div class="form-group">
                                <div class="col">
                                    <label for="partner-name">Partner Name</label>
                                    <input type="text" id="partner-name" name="partnerName" placeholder="Partner Name" required>
                                </div>
                                <div class="col">
                                    <label for="partner-contact">Contact Info</label>
                                    <input type="text" id="partner-contact" name="contactNumber" placeholder="Contact Info" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col">
                                    <label for="partner-address">Address</label>
                                    <input type="text" id="partner-address" name="address" placeholder="Address" required>
                                </div>
                                <div class="col">
                                    <label for="partner-email">Email</label>
                                    <input type="email" id="partner-email" name="email" placeholder="Email" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col">
                                    <label for="partner-password">Password</label>
                                    <input type="password" id="partner-password" name="password" placeholder="Password" required>
                                </div>
                                
                            </div>
                        </div>

                        <button type="submit" class="submit-button-form" id="btn-submit">Create Partner</button>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>