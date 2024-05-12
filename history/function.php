<?php 
    require_once "../config/database.php";

    $id_user;
    $user_data;
    $peminjaman;
    $histories;

    function get_user($id_user){
        global $conn;
        $sql = "SELECT tb_user.nama_user, tb_user.tanggal_register, tb_user.status, tb_kota.nama_kota FROM tb_user INNER JOIN tb_kota ON tb_user.id_kota = tb_kota.id_kota WHERE tb_user.id_user = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    function get_peminjaman($id_user){
        global $conn;
        $sql = "SELECT tb_kode_peminjaman.kode_peminjaman, tb_kode_peminjaman.durasi, tb_kode_peminjaman.tanggal_expire, tb_perpustakaan.nama_perpustakaan, tb_perpustakaan.alamat, tb_perpustakaan.maps, tb_buku.id_buku, tb_buku.judul_buku, tb_buku.cover, tb_buku.harga FROM ((tb_kode_peminjaman INNER JOIN tb_perpustakaan ON tb_kode_peminjaman.id_perpustakaan = tb_perpustakaan.id_perpustakaan) INNER JOIN tb_buku ON tb_kode_peminjaman.id_buku = tb_buku.id_buku) WHERE id_user = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    function cancel_peminjaman($id_user){
        global $conn;
        $sql1 = "DELETE FROM tb_kode_peminjaman WHERE id_user = ?";
        $sql2 = "UPDATE tb_user SET status = 'Idle' WHERE id_user = ?";
        $stmt1 = mysqli_prepare($conn, $sql1);
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt1, "i", $id_user);
        mysqli_stmt_bind_param($stmt2, "i", $id_user);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_execute($stmt2);
    }

    function increase_book_stock($id_buku){
        global $conn;
        $sql1 = "SELECT stock FROM tb_buku WHERE id_buku = ?";
        $stmt1 = mysqli_prepare($conn, $sql1);
        mysqli_stmt_bind_param($stmt1, "i", $id_buku);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_bind_result($stmt1, $stock);
        $increased_stock = ++$stock;
        mysqli_stmt_close($stmt1);
        $sql2 = "UPDATE tb_buku SET stock = ? WHERE id_buku = ?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "ii", $increased_stock, $id_buku);
        mysqli_stmt_execute($stmt2);
    }

    function get_all_histories($id_user){
        global $conn;
        $sql = "SELECT tb_history.kode_peminjaman, tb_buku.judul_buku, tb_buku.cover, tb_buku.harga, tb_history.tanggal_peminjaman, tb_perpustakaan.nama_perpustakaan, tb_perpustakaan.alamat, tb_perpustakaan.maps, tb_history.tanggal_pengembalian, tb_history.status FROM ((tb_history INNER JOIN tb_perpustakaan ON tb_history.id_perpustakaan = tb_perpustakaan.id_perpustakaan) INNER JOIN tb_buku ON tb_history.id_buku = tb_buku.id_buku) WHERE id_user = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return $rows;
    }

    function check_due_date($id_user){
        global $conn;
        $sql1 = "SELECT * FROM tb_kode_peminjaman WHERE id_user = ?";
        $stmt1 = mysqli_prepare($conn, $sql1);
        mysqli_stmt_bind_param($stmt1, "i", $id_user);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_store_result($stmt1);
        $row1 = mysqli_stmt_num_rows($stmt1);
        mysqli_stmt_close($stmt1);
        if($row1 != 0){
            $sql2 = "SELECT tanggal_expire FROM tb_kode_peminjaman WHERE id_user = ?";
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $id_user);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_bind_result($stmt2, $get_tanggal_expire);
            mysqli_stmt_fetch($stmt2);
            $tanggal_expire = $get_tanggal_expire;
            mysqli_stmt_close($stmt2);
            if(strtotime($tanggal_expire) < time()){
                $sql3 = "DELETE FROM tb_kode_peminjaman WHERE id_user = ?";
                $stmt3 = mysqli_prepare($conn, $sql3);
                mysqli_stmt_bind_param($stmt3, "i", $id_user);
                mysqli_stmt_execute($stmt3);
                mysqli_stmt_close($stmt3);
                $sql4 = "UPDATE tb_user SET status = 'Idle' WHERE id_user = ?";
                $stmt4 = mysqli_prepare($conn, $sql4);
                mysqli_stmt_bind_param($stmt4, "i", $id_user);
                mysqli_stmt_execute($stmt4);
                mysqli_stmt_close($stmt4);
            }
        }
        $sql5 = "SELECT * FROm tb_history WHERE status = 'Dipinjam' AND id_user = ?";
        $stmt5 = mysqli_prepare($conn, $sql5);
        mysqli_stmt_bind_param($stmt5, "i", $id_user);
        mysqli_stmt_execute($stmt5);
        mysqli_stmt_store_result($stmt5);
        $row2 = mysqli_stmt_num_rows($stmt5);
        mysqli_stmt_close($stmt5);
        if($row2 != 0){
            $sql6 = "SELECT tanggal_pengembalian FROM tb_history WHERE id_user = ?";
            $stmt6 = mysqli_prepare($conn, $sql6);
            mysqli_stmt_bind_param($stmt6, "i", $id_user);
            mysqli_stmt_execute($stmt6);
            mysqli_stmt_bind_result($stmt6, $get_tanggal_pengembalian);
            mysqli_stmt_fetch($stmt6);
            $tanggal_pengembalian = $get_tanggal_pengembalian;
            mysqli_stmt_close($stmt6);
            if(strtotime($tanggal_pengembalian) < time()){
                $sql7 = "UPDATE tb_history SET status = 'Dikembalikan' WHERE id_user = ?";
                $stmt7 = mysqli_prepare($conn, $sql7);
                mysqli_stmt_bind_param($stmt7, "i", $id_user);
                mysqli_stmt_execute($stmt7);
                mysqli_stmt_close($stmt7);
                $sql8 = "UPDATE tb_user SET status = 'Mengembalikan buku' WHERE id_user = ?";
                $stmt8 = mysqli_prepare($conn, $sql8);
                mysqli_stmt_bind_param($stmt8, "i", $id_user);
                mysqli_stmt_execute($stmt8);
                mysqli_stmt_close($stmt8);
            }
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_SESSION["id_user"]) || isset($_COOKIE["id_user"])){
            $id_user = $_SESSION["id_user"] ?? $_COOKIE["id_user"];
            $user_data = get_user($id_user);
            if(!isset($_GET["opt"]) || (isset($_GET["opt"]) && $_GET["opt"] == "") || (isset($_GET["opt"]) && $_GET["opt"] == "pengambilan")){
                $peminjaman = get_peminjaman($id_user);
            }elseif(isset($_GET["opt"]) && $_GET["opt"] == "peminjaman"){
                $histories = get_all_histories($id_user);
            }
            check_due_date($id_user);
        }else{
            header("Location: http://localhost/j2r-library-center");
        }
    }elseif($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["logout"])){
            setcookie("id_user", "", time() - 3600, "/");
            unset($_SESSION["id_user"]);
            header("Location: http://localhost/j2r-library-center/login");
        }
        if(isset($_POST["cancel"])){
            $id_user = $_SESSION["id_user"] ?? $_COOKIE["id_user"];
            $id_buku = intval($_POST["buku"]);
            cancel_peminjaman($id_user);
            increase_book_stock($id_buku);
            header("Location: http://localhost/j2r-library-center/history");
        }
    }
?>