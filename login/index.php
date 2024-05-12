<?php require_once "function.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J2R Library Center - Login</title>
    <link rel="icon" type="icon/x-image" href="../assets/img/icon.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body style="background:#F0E2D0;">
    <div class="container-fluid position-absolute h-100" style="background:#F0E2D0;">
        <div class="row h-100">
            <div class="col-8 d-flex justify-content-center align-items-center flex-column" style="background-image:url('../assets/img/background-1.png');background-repeat:no-repeat;background-position:center;background-size:cover;">
                <h1 class="display-1 fw-bolder text-center text-white mb-4" style="text-shadow:-2px -2px 0 black, 2px -2px 0 black, -2px 2px 0 black, 2px 2px 0 black;">Welcome</h1>
                <h4 class="m-0 text-center text-white fw-bolder" style="text-shadow:-2px -2px 0 black, 2px -2px 0 black, -2px 2px 0 black, 2px 2px 0 black;">Let’s read, make our future even bright</h4>
            </div>
            <div class="col-4 px-5">
                <form action="" method="post" class="w-100 h-100 d-flex justify-content-center align-items-center flex-column">
                    <img src="../assets/img/logo.png" class="w-75 mb-5">
                    <h1 class="fw-semibold mt-3 mb-5">Login</h1>
                    <div class="input-group input-group-lg mb-4">
                        <span class="input-group-text" style="width:55px;">
                            <i class="fa-solid fa-user mx-auto"></i>
                        </span>
                        <input type="text" name="nama-user" class="form-control fw-bold" placeholder="Username" autocomplete="off" required>
                    </div>
                    <div class="input-group input-group-lg mb-4">
                        <span class="input-group-text" style="width:55px;">
                        <i class="fa-solid fa-lock mx-auto"></i>
                        </span>
                        <input type="password" name="password" class="form-control fw-bold" placeholder="Password" autocomplete="off" required>
                    </div>
                    <div class="form-check form-switch align-self-start mb-5">
                        <input type="checkbox" name="remember-me" class="form-check-input" id="remember-me">
                        <label for="remember-me" class="form-label fw-semibold">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary fw-bold w-50 mb-4">Login</button>
                    <p class="text-center fw-semibold m-0">Don't have an account? Please <a href="../register/" class="link link-primary">register</a></p>
                </form>
            </div>
        </div>
    </div>
    <?php if(isset($status)): ?>
        <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-4">
                        <h1 class="modal-title text-center fw-bolder mx-auto">Status</h1>
                    </div>
                    <div class="modal-body py-5">
                        <img src="../assets/img/<?= ($status == 1) ? "success" : "fail"; ?>.png" class="d-block mx-auto w-25 mb-4">
                        <h5 class="text-center fw-semibold m-0">
                            <?php 
                                if($status == 1){
                                    echo "Selamat, Anda telah berhasil login!";
                                }elseif($status == 2){
                                    echo "Maaf, password Anda salah!";
                                }elseif($status == 3){
                                    echo "Maaf, akun Anda belum diregistrasi!";
                                }
                            ?>
                        </h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-lg btn-danger fw-bold w-25" data-bs-dismiss="modal" onclick="<?= ($status == 1) ? "location.href='../'" : ""; ?>">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <?php if($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <script src="../assets/js/show-modal.js"></script>
    <?php endif; ?>
</body>
</html>