<?php 
    require_once "../config/database.php";

    $id_user;
    $user_data;
    $search_results;

    function get_user($id_user){
        global $conn;
        $sql = "SELECT tb_user.nama_user, tb_user.tanggal_register, tb_user.status, tb_kota.nama_kota FROM tb_user INNER JOIN tb_kota ON tb_user.id_kota = tb_kota.id_kota WHERE tb_user.id_user = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    function get_results($query){
        global $conn;
        $sql = "";
        $param = "%{$query}%";
        if($query != ""){
            $sql = "SELECT id_buku, judul_buku, cover FROM tb_buku WHERE judul_buku LIKE ?";
        }else{
            $sql = "SELECT id_buku, judul_buku, cover FROM tb_buku";
        }
        $stmt = mysqli_prepare($conn, $sql);
        if($query != ""){
            mysqli_stmt_bind_param($stmt, "s", $param);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return $rows;
    }

    function highlight($judul, $query){
        if($query != ""){
            $split = explode(strtolower($query), strtolower($judul));
            for($i = 0; $i < count($split); $i++){
                if($i == 0){
                    $split[$i] = $split[$i] . "<span class='text-bg-primary'>";
                }elseif($i == count($split) - 1){
                    $split[$i] = "</span>" . $split[$i];
                }else{
                    $split[$i] = "</span>" . $split[$i] . "<span class='text-bg-primary'>";
                }
            }
            return ucwords(implode($query, $split));
        }else{
            return $judul;
        }
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
            $search_result = get_results((isset($_GET["q"])) ? $_GET["q"] : "");
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
    }
?>