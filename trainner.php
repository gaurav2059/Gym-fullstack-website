<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meet Our Trainers</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #51c27a;
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: 100px;
        }

        .training-video {
            text-align: center;
            margin: 20px auto;
        }

        .training-video video {
            width: 80%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 10px;
        }

        .trainer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .trainer-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            width: 300px;
            overflow: hidden;
            text-align: center;
        }

        .trainer-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .trainer-card h3 {
            margin: 10px 0;
            color: #333;
        }

        .trainer-card p {
            margin: 0;
            padding: 0 20px 20px;
            color: #666;
        }

        .trainer-card .details {
            font-size: 14px;
            color: #333;
        }

        .back-button {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <header class="header">
        <h1>Meet Our Trainers</h1>
    </header>
    
    <div class="training-video">
       <video src="images/trainning video.webm" autoplay loop muted></video>
        <!-- Add a caption or description for the training picture if needed -->
    </div>
    
    <div class="trainer-container">
        <!-- Trainer 1 -->
        <div class="trainer-card">
            <img src="images/trainner1.jpeg" alt="John Doe">
            <h3>John Doe</h3>
            <p class="details">
                John is a certified personal trainer with over 10 years of experience. He specializes in strength training and cardiovascular workouts.
            </p>
        </div>
        
        <!-- Trainer 2 -->
        <div class="trainer-card">
            <img src="images/trainner2.jpeg" alt="Jane Smith">
            <h3>Jimmy Smith</h3>
            <p class="details">
                Jane is a fitness enthusiast with a passion for group fitness classes and yoga. She has been teaching for 5 years and loves helping clients reach their fitness goals.
            </p>
        </div>
        
        <!-- Trainer 3 -->
        <div class="trainer-card">
            <img src="images/trainner4.jpeg" alt="Michael Brown">
            <h3>Michael Brown</h3>
            <p class="details">
                Michael is an expert in sports conditioning and injury prevention. With a background in physical therapy, he helps athletes improve their performance and prevent injuries.
            </p>
        </div>

        <!-- Trainer 4 -->
        <div class="trainer-card">
            <img src="images/trainner3.jpg" alt="Emily Davis">
            <h3>Emily Davis</h3>
            <p class="details">
                Emily is a nutritionist and fitness coach who integrates healthy eating habits with effective workout routines. She believes in a holistic approach to fitness.
            </p>
        </div>
    </div>

    <a href="index.php" class="back-button">Go Back</a>


    <?php include 'footer.php'; ?>
</body>
</html>
