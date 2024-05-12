<?php require_once "function.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J2R Library Center - Booking</title>
    <link rel="icon" type="icon/x-image" href="../assets/img/icon.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body style="background:#F0E2D0;">
    <?php if($_SERVER["REQUEST_METHOD"] == "GET"): ?>
        <div class="container-fluid px-0 py-5 d-flex justify-content-center align-items-center">
            <form action="" method="post">
                <input type="hidden" name="buku" value="<?= $book_details["id_buku"]; ?>">
                <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog <?= (check_user_status($id_user) == "Idle") ? "modal-lg" : ""; ?> modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header p-4">
                                <button type="button" class="btn btn-lg btn-close m-0" data-bs-dismiss="modal"></button>
                                <h1 class="modal-title text-center fw-bolder mx-auto">Booking</h1>
                                <div class="inline-block position-relative p-0" style="width:36px;height:36px;"></div>
                            </div>
                            <div class="modal-body <?= (check_user_status($id_user) != "Idle") ? "d-flex justify-content-center align-items-center flex-column" : ""; ?> py-5 px-4">
                                <?php if(check_user_status($id_user) == "Idle"): ?>
                                    <div class="row mb-4">
                                        <div class="col-4 d-flex align-items-center">
                                            <label for="durasi" class="form-label fw-bold m-0 h5">Durasi peminjaman</label>
                                        </div>
                                        <div class="col-1 d-flex align-items-center justify-content-center">
                                            <label class="form-label fw-bold m-0 h5">:</label>
                                        </div>
                                        <div class="col-7">
                                            <select name="durasi" id="durasi" class="form-select fw-semibold" required>
                                                <option value="" selected disabled>---</option>
                                                <option value="1">1 minggu</option>
                                                <option value="2">2 minggu</option>
                                                <option value="3">3 minggu</option>
                                                <option value="4">4 minggu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-4 d-flex align-items-center">
                                            <label for="pengambilan" class="form-label fw-bold m-0 h5">Lokasi pengambilan</label>
                                        </div>
                                        <div class="col-1 d-flex align-items-center justify-content-center">
                                            <label class="form-label fw-bold m-0 h5">:</label>
                                        </div>
                                        <div class="col-7">
                                            <select name="pengambilan" id="pengambilan" class="form-select fw-semibold" required>
                                                <option value="" selected disabled>---</option>
                                                <?php foreach($location as $each): ?>
                                                    <option value="<?= $each["id_perpustakaan"]; ?>"><?= $each["nama_perpustakaan"]; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 d-flex align-items-center">
                                            <label class="form-label fw-bold m-0 h5">Harga</label>
                                        </div>
                                        <div class="col-1 d-flex align-items-center justify-content-center">
                                            <label class="form-label fw-bold m-0 h5">:</label>
                                        </div>
                                        <div class="col-7 d-flex align-items-center">
                                            <label class="form-label fw-bold m-0 h5">Rp <?= number_format($book_details["harga"], 2, ",", "."); ?></label>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <img src="../assets/img/fail.png" class="w-25 mb-5">
                                    <h5 class="text-center fw-semibold m-0">Maaf, Anda sekarang sedang meminjam ataupun mengambil buku.</h5>
                                <?php endif; ?>
                            </div>
                            <?php if(check_user_status($id_user) == "Idle"): ?>
                                <div class="modal-footer">
                                    <?php if($book_details["stock"] == null): ?>
                                        <p class="text-right m-0 me-5 fw-semibold text-danger">*maaf, stock buku ini telah habis*</p>
                                    <?php endif; ?>
                                    <button type="submit" name="booking" class="btn btn-lg btn-primary fw-bold w-25" <?= ($book_details["stock"] == 0) ? "disabled" : ""; ?>>Checkout</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card w-75 border-3" style="background:transparent;">
                <div class="card-header border-3 py-4 px-5 d-flex align-items-center justify-content-between">
                    <a href="../library-page/" class="text-decoration-none text-black" style="font-size:40px;">
                        <i class="fa-solid fa-arrow-left-long"></i>
                    </a>
                    <h1 class="fw-bold m-0">Book details</h1>
                    <i class="fa-solid fa-arrow-left-long" style="font-size:40px;opacity:0;"></i>
                </div>
                <div class="card-body border-3 p-5">
                    <div class="row">
                        <div class="col-3">
                            <img src="../assets/book-covers/<?= $book_details["cover"]; ?>" class="w-100">
                        </div>
                        <div class="col-9 ps-5">
                            <h1 class="mb-5 fw-semibold"><?= $book_details["judul_buku"]; ?></h1>
                            <table class="table mb-5">
                                <tr>
                                    <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Writer</td>
                                    <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);"><?= $book_details["penulis"]; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Publisher</td>
                                    <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);"><?= $book_details["penerbit"]; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Description</td>
                                    <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);"><?= $book_details["deskripsi"]; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Rating</td>
                                    <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);"><?= show_rating_stars($book_details["rating"]); ?>&nbsp;/ <?= $book_details["rating"]; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Price</td>
                                    <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Rp <?= number_format($book_details["harga"], 2, ",", "."); ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Stock</td>
                                    <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);"><?= $book_details["stock"] ?> buku</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">Location</td>
                                    <td style="background-color:transparent;border-color:rgba(0, 0, 0, .2);">
                                        <ol class="m-0 p-0" style="list-style-position:inside;">
                                            <?php foreach($location as $each): ?>
                                                <li>
                                                    <a href="<?= $each["maps"]; ?>" target="_blank" class="link text-black" data-bs-toggle="tooltip" data-bs-placement="right" title="<?= $each["alamat"]; ?>"><?= $each["nama_perpustakaan"]; ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ol>
                                    </td>
                                </tr>
                            </table>
                            <button class="btn btn-lg btn-primary fw-bold w-25 d-block ms-auto" data-bs-toggle="modal" data-bs-target="#modal">Booking</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif($_SERVER["REQUEST_METHOD"] == "POST"): ?>
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
        <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-4">
                        <h1 class="modal-title text-center fw-bolder mx-auto">Kode peminjaman</h1>
                    </div>
                    <div class="modal-body d-flex justify-content-center align-items-center flex-column py-5">
                        <div class="row w-50 p-2 border border-2 rounded mb-5">
                            <div class="col-10 p-0">
                                <h1 class="text-center fw-bolder m-0" style="letter-spacing:15px;" id="code"><?= $random_code; ?></h1>
                            </div>
                            <div class="col-2 p-0">
                                <button type="button" class="btn btn-lg btn-secondary w-100" id="show-toast" data-bs-toggle="tooltip" title="Copy">
                                    <i class="fa-solid fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <h4 class="fw-semibold text-center m-0 text-secondary">Gunakan kode tersebut ke perpustakaan.</h4>
                    </div>
                    <div class="modal-footer">
                        <a href="../" class="btn btn-lg btn-danger fw-bold w-25">Close</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/show-tooltip.js"></script>
    <?php if($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <script src="../assets/js/show-modal.js"></script>
        <script src="../assets/js/copy-code.js"></script>
    <?php endif; ?>
</body>
</html>