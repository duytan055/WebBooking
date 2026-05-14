<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <title>BookingMovies</title>
    <style>
        body {
            height: 100%;
            margin: 0;
            background-color: rgba(164, 159, 159, 0.49);
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>
    <?php session_start(); ?>
    <?php include '../Module/header.php'; ?>
    <?php include '../Module/SliderMovies.php'; ?>
    <?php include '../Module/movies.php'; ?>
    <?php include '../Module/PromotionsAndEvents.php'; ?>
    <?php include '../Module/footer.php'; ?>
</body>

</html>