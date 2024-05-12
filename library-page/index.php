<?php require_once "function.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J2R Library Center - Library Page</title>
    <link rel="icon" type="icon/x-image" href="../assets/img/icon.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body style="background:#F0E2D0;">
    <div class="container-fluid position-absolute p-0 h-100" style="background:#F0E2D0;">
        <form action="" method="post">
            <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header py-4">
                            <h1 class="modal-title text-center fw-bolder mx-auto">Status</h1>
                        </div>
                        <div class="modal-body py-5">
                            <img src="../assets/img/question-mark.png" class="d-block mx-auto w-25 mb-4">
                            <h5 class="text-center fw-semibold m-0">Apakah anda yakin untuk logout?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-lg btn-primary fw-bold w-25" data-bs-dismiss="modal">Tidak</button>
                            <button type="submit" name="logout" class="btn btn-lg btn-danger fw-bold w-25">Iya</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="offcanvas offcanvas-end" id="user-profile">
            <div class="offcanvas-header border-bottom border-2">
                <h1 class="offcanvas-title fw-bold mx-auto">User profile</h1>
            </div>
            <div class="offcanvas-body py-5 d-flex justify-content-between align-items-center flex-column">
                <div class="container-fluid p-0">
                    <h5 class="text-secondary mb-4">Username : <?= $user_data["nama_user"]; ?></h5>
                    <h5 class="text-secondary mb-4">Registration date : <?= explode(" ", $user_data["tanggal_register"])[0]; ?></h5>
                    <h5 class="text-secondary mb-4">Location : <?= $user_data["nama_kota"]; ?></h5>
                    <h5 class="text-secondary">Status : <?= $user_data["status"]; ?></h5>
                </div>
                <button type="button" class="btn btn-lg btn-danger fw-bold d-block ms-auto" data-bs-toggle="modal" data-bs-target="#modal">Logout</button>
            </div>
        </div>
        <div class="navbar navbar-expand position-fixed w-100 shadow" style="background:#AA8976;z-index:2;">
            <div class="container-fluid px-5 py-1">
                <a href="" class="navbar-brand position-relative m-0" style="width:50px;">
                    <img src="../assets/img/logo.png" width="200">
                </a>
                <ul class="navbar-nav">
                    <li class="nav-item px-3">
                        <a href="../" class="nav-link fw-bolder" style="font-size:20px;">Homepage</a>
                    </li>
                    <li class="nav-item px-3">
                        <a href="" class="nav-link active fw-bolder" style="font-size:20px;">Library page</a>
                    </li>
                    <li class="nav-item px-3">
                        <a href="../history" class="nav-link fw-bolder" style="font-size:20px;">History</a>
                    </li>
                </ul>
                <button type="button" class="p-0" data-bs-toggle="offcanvas" data-bs-target="#user-profile" style="background:transparent;border:none;">
                    <img src="../assets/img/user.png" class="rounded-circle" width="50" data-bs-toggle="tooltip" title="User profile">
                </button>
            </div>
        </div>
        <div class="container-fluid p-0" style="background:#F0E2D0;padding-top:150px !important;">
            <form action="" method="get" class="w-50 mx-auto mb-5">
                <div class="input-group input-group-lg">
                    <input type="text" name="q" id="search" class="form-control fw-bold py-3" value="<?= $_GET["q"] ?? ""; ?>" placeholder="Search books" autocomplete="off" style="z-index:1;" required>
                    <button type="submit" class="btn btn-primary px-4 fw-bold" style="z-index:1;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
            <a href="https://www.google.com/maps/place/Pontianak" class="d-inline-block mb-4" target="_blank">
                <h3 class="px-5">
                    <span class="badge bg-secondary">
                        <i class="fa-solid fa-location-dot"></i>&nbsp;Pontianak
                    </span>
                </h3>
            </a>
            <div id="container">
                <div class="container-fluid p-0" style="background:#AA8976">
                    <h3 class="py-3 px-5 fw-bold">
                        <?php if(isset($_GET["q"]) && $_GET["q"] != ""): ?>
                            Search results (<?= count($search_result); ?>)
                        <?php else: ?>
                            Books
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="container-fluid p-5 d-flex justify-content-center flex-wrap gap-5" id="container" style="background:#F0E2D0;">
                    <?php if(!empty($search_result)): ?>
                        <?php foreach($search_result as $each): ?>
                            <div class="card position-relative shadow-sm" style="width:240px;">
                                <a href="../booking/?book=<?= $each["id_buku"]; ?>" class="text-decoration-none text-black">
                                    <img src="../assets/book-covers/<?= $each["cover"]; ?>" class="card-img-top">
                                    <div class="card-body py-4 d-flex align-items-center justify-content-center">
                                        <h4 class="text-center fw-semibold m-0 text-capitalize"><?= highlight($each["judul_buku"], $_GET["q"] ?? ""); ?></h4>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h1 class="fw-bold text-secondary m-0">The result "<?= $_GET["q"] ?? ""; ?>" was not found!</h1>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/show-tooltip.js"></script>
    <script src="../assets/js/search-ajax.js"></script>
</body>
</html>