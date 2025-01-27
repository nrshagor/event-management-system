<!DOCTYPE html>
<html lang="en">
<?php require_once __DIR__ . '/../config.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">


    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">


    <!-- Google Font  -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="./public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <!-- FontAwesome for Icons -->

</head>


<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">

            <a class="navbar-brand logo" href="<?= BASE_URL ?>"><img src="./public/uploads/eventLogo.png" alt="logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Offcanvas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')): ?>

                            <li class="nav-item">
                                <a class="nav-link nav-btn" aria-current="page" href="<?= BASE_URL ?>dashboard.php">Dashboard </a>
                            </li>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                            <li class="nav-item"><a class="nav-link nav-btn" href="<?= BASE_URL ?>events.php">Manage Events</a></li>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                            <li class="nav-item "><a class="nav-link nav-btn" href="<?= BASE_URL ?>manage_users.php">Manage Users</a></li>
                        <?php endif; ?>


                    </ul>
                    <form class="d-flex" role="search" action="<?= BASE_URL ?>search_results.php" method="GET">
                        <input class="form-control me-2" type="search" name="search" placeholder="Search events..." aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active nav-btn" aria-current="page" href="<?= BASE_URL ?>logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
                            </li>
                        </ul>

                    <?php else: ?>
                        <ul class="navbar-nav">

                            <li class="nav-item">
                                <a class="nav-link active nav-btn" aria-current="page" href="<?= BASE_URL ?>register.php">
                                    <i class="fa-regular fa-address-book ml-2"></i> Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active nav-btn" aria-current="page" href="<?= BASE_URL ?>login.php"> <i class="fa-solid fa-arrow-right-to-bracket ml-2"></i> Login</a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
    </nav>

    <div class="container mt-4  min-vh-100 d-flex flex-row justify-content-center align-items-start">