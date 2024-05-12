<?php require_once "function.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J2R Library Center - History</title>
    <link rel="icon" type="icon/x-image" href="../assets/img/icon.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body style="background:#F0E2D0;">
    <div class="container-fluid p-0">
        <form action="" method="post">
            <div class="modal fade" id="modal-logout" data-bs-backdrop="static" data-bs-keyboard="false">
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
                <button type="button" class="btn btn-lg btn-danger fw-bold d-block ms-auto" data-bs-toggle="modal" data-bs-target="#modal-logout">Logout</button>
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
                        <a href="../library-page/" class="nav-link fw-bolder" style="font-size:20px;">Library page</a>
                    </li>
                    <li class="nav-item px-3">
                        <a href="" class="nav-link active fw-bolder" style="font-size:20px;">History</a>
                    </li>
                </ul>
                <button type="button" class="p-0" data-bs-toggle="offcanvas" data-bs-target="#user-profile" style="background:transparent;border:none;">
                    <img src="../assets/img/user.png" class="rounded-circle" width="50" data-bs-toggle="tooltip" title="User profile">
                </button>
            </div>
        </div>
        <div class="container-fluid p-0 px-5 pb-5" style="padding-top:150px !important;">
            <div class="row w-100 mx-auto mb-5">
                <div class="col-6 d-flex align-items-center justify-content-end pe-4 border-end border-black border-opacity-50 border-2">
                    <a href="../history/?opt=pengambilan" class="link text-decoration-none h5 fw-bold m-0 <?= (isset($_GET["opt"]) && $_GET["opt"] == "peminjaman") ? "text-muted" : ""; ?>">Pengambilan</a>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-start ps-4 border-start border-black border-opacity-50 border-2">
                    <a href="../history/?opt=peminjaman" class="link text-decoration-none h5 fw-bold m-0 <?= ((!isset($_GET["opt"])) || (isset($_GET["opt"]) && $_GET["opt"] == "") || (isset($_GET["opt"]) && $_GET["opt"] == "pengambilan")) ? "text-muted" : ""; ?>">Peminjaman</a>
                </div>
            </div>
            <?php if((!isset($_GET["opt"])) || (isset($_GET["opt"]) && $_GET["opt"] == "") || (isset($_GET["opt"]) && $_GET["opt"] == "pengambilan")): ?>
                <?php if(!empty($peminjaman)): ?>
                    <form action="" method="post">
                        <input type="hidden" name="buku" value="<?= $peminjaman["id_buku"]; ?>">
                        <div class="modal fade" id="modal-cancel" data-bs-backdrop="static" data-bs-keyboard="false">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header py-4">
                                        <h1 class="modal-title text-center fw-bolder mx-auto">Status</h1>
                                    </div>
                                    <div class="modal-body py-5">
                                        <img src="../assets/img/question-mark.png" class="d-block mx-auto w-25 mb-4">
                                        <h5 class="text-center fw-semibold m-0">Apakah anda yakin untuk cancel?</h5>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-lg btn-primary fw-bold w-25" data-bs-dismiss="modal">Tidak</button>
                                        <button type="submit" name="cancel" class="btn btn-lg btn-danger fw-bold w-25">Iya</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="toast-container position-fixed bottom-0 end-0 p-4">
                        <div class="toast" id="toast">
                            <div class="toast-header justify-content-between px-3">
                                <h5 class="fw-bold m-0">Clipboard</h5>
                                <button type="button" class="btn btn-close" data-bs-dismiss="toast"></button> 
                            </div>
                            <div class="toast-body p-3">
                                <p class="m-0 fw-semibold">Kode peminjamanmu telah disalin ke clipboard.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card w-75 mx-auto border-3 bg-transparent">
                        <div class="card-body p-5">
                            <div class="row">
                                <div class="col-3">
                                    <img src="../assets/book-covers/<?= $peminjaman["cover"]; ?>" class="w-100">
                                </div>
                                <div class="col-9 ps-5">
                                    <div class="row w-50 p-2 border border-2 border-black border-opacity-25 rounded m-0 mb-5 bg-light">
                                        <div class="col-10 p-0">
                                            <h1 class="fw-bolder text-center m-0" style="letter-spacing:10px;" id="code"><?= $peminjaman["kode_peminjaman"]; ?></h1>
                                        </div>
                                        <div class="col-2 p-0">
                                            <button type="button" class="btn btn-lg btn-secondary w-100" id="show-toast" data-bs-toggle="tooltip" title="Copy">
                                                <i class="fa-solid fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <table class="table mb-5">
                                        <tr>
                                            <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Judul buku</td>
                                            <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);"><?= $peminjaman["judul_buku"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Harga</td>
                                            <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Rp <?= number_format($peminjaman["harga"], 2, ",", "."); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Durasi peminjaman</td>
                                            <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);"><?= $peminjaman["durasi"]; ?> minggu</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Lokasi pengambilan</td>
                                            <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">
                                                <a href="<?= $peminjaman["maps"] ?>" target="_blank" class="link text-black" data-bs-toggle="tooltip" data-bs-placement="right" title="<?= $peminjaman["alamat"]; ?>"><?= $peminjaman["nama_perpustakaan"]; ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Expired peminjaman</td>
                                            <td id="container" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">
                                                <?php 
                                                    $diff = strtotime($peminjaman["tanggal_expire"]) - time() - 25200;
                                                    if($diff >= 0){
                                                        echo date("H : i : s", $diff);
                                                    }else{
                                                        echo "<script>window.location.reload();</script>";
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <button type="button" class="btn btn-lg btn-danger fw-bold w-25 d-block ms-auto" data-bs-toggle="modal" data-bs-target="#modal-cancel">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <h1 class="text-secondary text-center m-0 pt-5 fw-bold display-6">Anda tidak sedang dalam pengambilan buku untuk sekarang!</h1>
                <?php endif; ?>
            <?php elseif(isset($_GET["opt"]) && $_GET["opt"] == "peminjaman"): ?>
                <?php if(count($histories) != 0): ?>
                    <?php foreach($histories as $each): ?>
                        <div class="card w-75 border-3 mx-auto mb-4 bg-transparent">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-3">
                                        <img src="../assets/book-covers/<?= $each["cover"]; ?>" class="w-100">
                                    </div>
                                    <div class="col-9 d-flex ps-4 flex-column align-items-center">
                                        <h1 class="fw-bold m-0 mb-5 align-self-start">#<?= $each["kode_peminjaman"]; ?></h1>
                                        <table class="table mb-5">
                                            <tr>
                                                <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Judul buku</td>
                                                <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);"><?= $each["judul_buku"]; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Harga</td>
                                                <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Rp <?= number_format($each["harga"], 2, ",", "."); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Tanggal peminjaman</td>
                                                <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);"><?= date("d F Y", strtotime($each["tanggal_peminjaman"])); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Lokasi pengambilan</td>
                                                <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">
                                                    <a href="<?= $each["maps"]; ?>" target="_blank" class="link text-black" data-bs-toggle="tooltip" data-bs-placement="right" title="<?= $each["alamat"]; ?>"><?= $each["nama_perpustakaan"]; ?></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Tanggal pengembalian</td>
                                                <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);"><?= date("d F Y", strtotime($each["tanggal_pengembalian"])) ?></td>
                                            </tr>
                                        </table>
                                        <?php if($each["status"] == "Dipinjam"): ?>
                                            <h2 class="fw-bold align-self-end" id="returned-date">
                                                <?php 
                                                    $diff = strtotime($each["tanggal_pengembalian"]) - time() - 25200;
                                                    if($diff >= 0){
                                                        echo date("d", $diff - 86400) . " hari " . date("H", $diff) . " jam " . date("i", $diff) . " menit " . date("s", $diff) . " detik";
                                                    }else{
                                                        echo "<script>window.location.reload();</script>";
                                                    }
                                                ?>
                                            </h2>
                                            <script src="../assets/js/returned-date-ajax.js"></script>
                                        <?php elseif($each["status"] == "Dikembalikan"): ?>
                                            <h4 class="fw-bold align-self-end text-danger">Tolong dikembalikan, terima kasih telah dipinjam.</h4>
                                        <?php elseif($each["status"] == "Done"): ?>
                                            <h2 class="fw-bold m-0 text-success align-self-end">Done <i class="fa-solid fa-circle-check"></i></h2>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h1 class="text-secondary text-center m-0 pt-5 fw-bold display-6">Anda belum pernah meminjam buku satupun!</h1>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/show-tooltip.js"></script>
    <?php if((!isset($_GET["opt"])) || (isset($_GET["opt"]) && $_GET["opt"] == "") || (isset($_GET["opt"]) && $_GET["opt"] == "pengambilan")): ?>
        <?php if($user_data["status"] != "Idle"): ?>
            <script src="../assets/js/copy-code.js"></script>
            <script src="../assets/js/expired-date-ajax.js"></script>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>