<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Soori Travels Official Website'; ?></title>
    <link rel="icon" href="<?php echo $basePath ?? ''; ?>images/title_icon.png">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- AOS animation library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- font awesome older version for tripadvisor icon -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="<?php echo $basePath ?? ''; ?>css/style.css">

    <?php if (!empty($extraCss)): ?>
        <?php foreach ($extraCss as $cssFile): ?>
            <link rel="stylesheet" href="<?php echo $basePath ?? ''; ?><?php echo $cssFile; ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-auth-compat.js"></script>
    <script src="<?php echo $basePath ?? ''; ?>js/firebase-auth.js"></script>
</head>
<body>
