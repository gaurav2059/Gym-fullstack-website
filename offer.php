<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offers</title>
    <link rel="stylesheet" href="css/offer.css">
</head>

<body>

    <div class="main">
    <?php
    include "connection.php";

    $sql = "SELECT id, title, end_date, promo_code, discount_percentage FROM offers";
    $result = mysqli_query($conn, $sql) or die("Query Failed: Offer Post");

    // Fetch offers for JavaScript
    $offers = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($offers as $row) {
        ?>
        <div class="overlay">
            <div class="title"><?php echo htmlspecialchars($row['title']); ?></div>
            <div class="title2"><?php echo htmlspecialchars($row['end_date']); ?></div>
            <div class="promo-code">Promo Code: <?php echo htmlspecialchars($row['promo_code']); ?></div>
            <div class="discount">Discount: <?php echo htmlspecialchars($row['discount_percentage']); ?>%</div>
            <div class="col">
                <div>
                    <input type="text" readonly class="days" value="0">
                    <br/>
                    <label>Days</label>
                </div>
                <div>
                    <input type="text" readonly class="hours" value="0">
                    <br/>
                    <label>Hours</label>
                </div>
                <div>
                    <input type="text" readonly class="minutes" value="0">
                    <br/>
                    <label>Minutes</label>
                </div>
                <div>
                    <input type="text" readonly class="seconds" value="0">
                    <br/>
                    <label>Seconds</label>
                </div>
            </div>
        </div>
        <?php
    }

    mysqli_close($conn);
    ?>
    </div>

    <script src="js/offer.js"></script>
</body>

</html>
