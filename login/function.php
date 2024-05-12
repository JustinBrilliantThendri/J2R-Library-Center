<?php 
    require_once "../config/database.php";

    function get_user($nama_user){
        global $conn;
        $sql = "SELECT id_user, nama_user, password FROM tb_user WHERE nama_user = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $nama_user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    $status;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nama_user = test_input($_POST["nama-user"]);
        $password = test_input($_POST["password"]);
        $user = get_user($nama_user);
        if(!empty($user)){
            $hash_password = $user["password"];
            if(password_verify($password, $hash_password)){
                $id_user = $user["id_user"];
                $_SESSION["id_user"] = $id_user;
                if(isset($_POST["remember-me"])){
                    setcookie("id_user", $id_user, time() + 84600, "/");
                }
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