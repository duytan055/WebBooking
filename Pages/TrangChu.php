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
            background: radial-gradient(circle at top, rgba(56, 189, 248, 0.12), transparent 28%),
                linear-gradient(180deg, #0f172a 0%, #111827 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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