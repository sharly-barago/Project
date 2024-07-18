<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Inventory Management System'; ?></title>
    <?php include('partials/styles.php'); ?>
    <?php include('partials/scripts.php'); ?>
</head>

<body <?php echo isset($bodyClass) ? "class=\"$bodyClass\"" : ''; ?>>