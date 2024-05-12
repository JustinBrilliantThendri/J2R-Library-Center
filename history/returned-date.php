<?php 
    require_once "../config/database.php";

    function get_tanggal_pengembalian($id_user){
        global $conn;
        $sql = "SELECT tanggal_pengembalian FROM tb_history WHERE id_user = ? AND status = 'Dipinjam'";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $tanggal_pengembalian);
        mysqli_stmt_fetch($stmt);
        return $tanggal_pengembalian;
    }

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $id_user = $_SESSION["id_user"] ?? $_COOKIE["id_user"];
        $tanggal_pengembalian = get_tanggal_pengembalian($id_user);
        $diff = strtotime($tanggal_pengembalian) - time() - 25200;
        if($diff >= 0){
            echo date("d", $diff - 86400) . " hari " . date("H", $diff) . " jam " . date("i", $diff) . " menit " . date("s", $diff) . " detik";
        }else{
            echo "<script>window.location.reload();</script>";
        }
    }
?>