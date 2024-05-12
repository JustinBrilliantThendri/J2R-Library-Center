<?php 
    require_once "../config/database.php";

    function get_user($nama_user){
        global $conn;
        $query = "SELECT * FROM tb_user WHERE nama_user = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $nama_user);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    function insert_user($nama_user, $hash_password, $kota = "PTK"){
        global $conn;
        $query = "INSERT INTO tb_user VALUES ('', ?, ?, NOW(), 'Idle', ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sss", $nama_user, $hash_password, $kota);
        mysqli_stmt_execute($stmt);
    }

    $status;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nama_user = test_input($_POST["nama-user"]);
        $password = test_input($_POST["password"]);
        $confirm = test_input($_POST["confirm-password"]);
        $user = get_user($nama_user);
        if(mysqli_num_rows($user) == 0){
            if($password == $confirm){
                $hash_password = password_hash($password, PASSWORD_DEFAULT);
                insert_user($nama_user, $hash_password);
                $status = 1;
            }else{
                $status = 2;
            }
        }else{
            $status = 3;
        }
    }elseif($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_SESSION["id_user"]) || isset($_COOKIE["id_user"])){
            header("Location: http://localhost/j2r-library-center/");
        }
    }
?>