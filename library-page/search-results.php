<?php 
    require_once "../config/database.php";

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

    $search_result = get_results($_GET["q"] ?? "");
?>

<div id="container">
    <div class="container-fluid p-0" style="background:#AA8976">
        <h3 class="py-3 px-5 fw-bold">
            <?php if(isset($_GET["q"]) && $_GET["q"] != ""): ?>
                Search results (<?= count($search_result); ?>)
            <?php else: ?>
                Books
            <?php endif; ?>
        </h3>
    </div>
    <div class="container-fluid p-5 d-flex justify-content-center flex-wrap gap-5" id="container" style="background:#F0E2D0;">
        <?php if(!empty($search_result)): ?>
            <?php foreach($search_result as $each): ?>
                <div class="card position-relative shadow-sm" style="width:240px;">
                    <a href="../booking/?book=<?= $each["id_buku"]; ?>" class="text-decoration-none text-black">
                        <img src="../assets/book-covers/<?= $each["cover"]; ?>" class="card-img-top">
                        <div class="card-body py-4 d-flex align-items-center justify-content-center">
                            <h4 class="text-center fw-semibold m-0 text-capitalize"><?= highlight($each["judul_buku"], $_GET["q"] ?? ""); ?></h4>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h1 class="fw-bold text-secondary m-0">The result "<?= $_GET["q"] ?? ""; ?>" was not found!</h1>
        <?php endif; ?>
    </div>
</div>