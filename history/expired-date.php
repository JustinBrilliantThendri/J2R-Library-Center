<?php 
    require_once "../config/database.php";

    function get_expired_date($id_user){
        global $conn;
        $sql = "SELECT tanggal_expire FROM tb_kode_peminjaman WHERE id_user = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $expired_date);
        mysqli_stmt_fetch($stmt);
        return strtotime($expired_date);
    }

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $id_user = $_SESSION["id_user"] ?? $_COOKIE["id_user"];
        $diff = get_expired_date($id_user) - time() - 25200;
        if($diff >= 0){
            echo date("H : i : s", $diff);
        }else{
            echo "<script>window.location.reload();</script>";
        }
    }
?>