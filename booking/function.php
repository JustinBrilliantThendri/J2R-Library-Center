<?php 
    require_once "../config/database.php";

    $id_user;
    $book_details;
    $location;
    $random_code;

    function get_book_details($id_buku){
        global $conn;
        $sql = "SELECT id_buku, judul_buku, cover, penulis, penerbit, deskripsi, rating, harga, stock FROM tb_buku WHERE id_buku = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_buku);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    function show_rating_stars($rating){
        $str = "";
        for($i = 1; $i <= 5; $i++){
            if($rating != 0){
                if($rating >= 1){
                    $str .= "<i class='fa-solid fa-star'></i>";
                    $rating--;
                }else{
                    $str .= "<i class='fa-solid fa-star-half-stroke'></i>";
                    $rating = 0;
                }
            }else{
                $str .= "<i class='fa-regular fa-star'></i>";
            }
        }
        return $str;
    }

    function get_book_location($id_buku){
        global $conn;
        $sql = "SELECT tb_perpustakaan.id_perpustakaan, tb_perpustakaan.nama_perpustakaan, tb_perpustakaan.alamat, tb_perpustakaan.maps FROM tb_lokasi_buku INNER JOIN tb_perpustakaan ON tb_lokasi_buku.id_perpustakaan = tb_perpustakaan.id_perpustakaan WHERE tb_lokasi_buku.id_buku = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_buku);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return $rows;
    }

    function generate_code(){
        $code = "";
        $chars = "0123456789";
        for($i = 1; $i <= 6; $i++){
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $code;
    }

    function insert_kode_peminjaman($random_code, $id_user, $id_perpustakaan, $id_buku, $durasi, $tanggal_expire){
        global $conn;
        $sql = "INSERT INTO tb_kode_peminjaman VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "siiiss", $random_code, $id_user, $id_perpustakaan, $id_buku, $durasi, $tanggal_expire);
        mysqli_stmt_execute($stmt);
    }

    function check_user_status($id_user){
        global $conn;
        $sql = "SELECT status FROM tb_user WHERE id_user = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $status);
        mysqli_stmt_fetch($stmt);
        return $status;
    }

    function change_user_status($id_user){
        global $conn;
        $sql = "UPDATE tb_user SET status = 'Mengambil buku' WHERE id_user = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        mysqli_stmt_execute($stmt);
    }

    function decrease_book_stock($id_buku){
        global $conn;
        $sql1 = "SELECT stock FROM tb_buku WHERE id_buku = ?";
        $stmt1 = mysqli_prepare($conn, $sql1);
        mysqli_stmt_bind_param($stmt1, "i", $id_buku);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_bind_result($stmt1, $stock);
        $decreased_stock = --$stock;
        mysqli_stmt_close($stmt1);
        $sql2 = "UPDATE tb_buku SET stock = ? WHERE id_buku = ?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "ii", $decreased_stock, $id_buku);
        mysqli_stmt_execute($stmt2);
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
            check_due_date($id_user);
            if(isset($_GET["book"]) && $_GET["book"] != ""){
                $book_details = get_book_details($_GET["book"]);
                $location = get_book_location($_GET["book"]);
                if(empty($book_details) || empty($location)){
                    header("Location: http://localhost/j2r-library-center/library-page");
                }
            }else{
                header("Location: http://localhost/j2r-library-center/library-page");
            }
        }else{
            header("Location: http://localhost/j2r-library-center");
        }
    }elseif($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["booking"])){
            $id_user = $_SESSION["id_user"] ?? $_COOKIE["id_user"];
            $id_perpustakaan = intval($_POST["pengambilan"]);
            $id_buku = intval($_POST["buku"]);
            $durasi = intval($_POST["durasi"]);
            $random_code = generate_code();
            $tanggal_expire = date("Y-m-d H:i:s", time() + 86400);
            insert_kode_peminjaman($random_code, $id_user, $id_perpustakaan, $id_buku, $durasi, $tanggal_expire);
            change_user_status($id_user);
            decrease_book_stock($id_buku);
        }
    }
?>