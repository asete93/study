<?php

    // GET 요청
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        session_start();
        if(isset($_SESSION['id']) || isset($_COOKIE['loginUser'])){

            $conn = new mysqli("mysql", "root", "1234", "study");
            if ($conn->connect_error) {
                die("DB 연결 실패: " . $conn->connect_error);
            }

            $id = $_GET['id'];
            $sql = "SELECT * FROM board WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = mysqli_fetch_array($result);

            if(!$row){
                header("Location: /board/list.php");
            }

            $view = $row['view'];
            $title = $row['title'];
            $writer = $row['writer'];
            $content = $row['contents'];
            // YYYY-MM-DD 패턴으로
            $created_at = $row['created_at'];
            $updated_at = $row['updated_at'];
            $created_at = date("Y-m-d", strtotime($created_at));
            
            // 로그인한 회원과 다른 경우, 조회수 증가
            if(isset($_SESSION['id'])){
                $loginId = $_SESSION['id'];
            } else {
                $loginId = $_COOKIE['loginUser'];
            }
            if($row['writer'] != $loginId){
                $sql = "UPDATE board SET view = view + 1 WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
            }
        } else {
            header("Location: /index.php");
        }

    // POST 요청
    } else {
        $id = $_POST['id']??null;
        if($id){
            $conn = new mysqli("mysql", "root", "1234", "study");
            if ($conn->connect_error) {
                die("DB 연결 실패: " . $conn->connect_error);
            }

            $sql = "DELETE FROM board WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if($stmt->execute()){
                header("Location: /board/list.php");
            }
        }
    }
    
?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        .camel th, .camel td {
            background-color: transparent !important;
        }


        .camel:hover, .camel th:hover, .camel td:hover{
            background-color: #f5b657 !important;
            cursor: pointer;
            transition: 0.3s;
        }

        .custom-menu {
            background-color: #f5b657; 
            border-color: #f5b657;
            color: white;
        }

        .custom-menu:hover {
            background-color:rgb(231, 179, 102); 
            border-color: rgb(231, 179, 102); 
            color: white;
        }
    </style>
    <title>Martin</title>
  </head>
  <body style="background-color: #D9D9D9">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh">
        <div class="d-flex flex-column f-weight bg-white" style="width: 80vw; height: 80vh; border-radius: 10px; padding: 3rem;">

            <!-- Title -->
            <div>
                <h2 class="d-flex flex-row align-items-center">
                    <?php echo $title ?>
                    <button type="button" class="btn ms-auto custom-menu" style="padding-top: 5px; padding-bottom: 5px; font-weight: bold;"  onClick="window.location.href='/board/list.php'">이전으로</button>
                </h2>
                <div class="d-flex flex-row justify-content-start align-items-center mt-4">
                    <div class="d-flex justify-content-center align-items-center fw-bold" style="width: 30px; height: 30px; border-radius: 50%; background-color: #f5b657; display: inline-flex; color: white;">
                        <?php
                            $firstChar = mb_substr($writer, 0, 1, "UTF-8");
                            echo strtoupper($firstChar);
                        ?>
                    </div>
                    <div style="margin-left: 10px;">
                        <?php echo $writer ?>
                    </div>

                    <div style="margin-left: 10px; color:rgb(161, 147, 147); font-weight: 500;">
                        <?php echo $created_at ?>
                    </div>

                    <div class="ms-auto" style="font-size: 9pt; color:rgb(161, 147, 147);">조회수 : <?php echo $view ?> </div>
                </div>
            </div>

            <hr style="margin-top: 20px; margin-bottom: 40px;">

            <pre style="overflow: auto; height: 100%;">
<?php echo $content ?>

            </pre>

            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $id ?>">

            <?php 
                if($writer == $loginId){
                    echo "
                        <div class=\"mt-auto \">
                            <hr style=\"margin-top: 20px; margin-bottom: 40px;\">
                            <div class=\"d-flex flex-row justify-content-start align-items-center\">
                                <button type=\"submit\" class=\"btn btn-danger\" style=\"padding-top: 5px; padding-bottom: 5px; font-weight: bold;\"  onClick=\"window.location.href='/board/list.php'\"> 삭제</button>
                                <button type=\"button\" class=\"btn btn-secondary\" style=\"margin-left: 20px; padding-top: 5px; padding-bottom: 5px; font-weight: bold;\"  onClick=\"window.location.href='/board/write.php?id=$id'\"> 수정</button>
                            </div>
                        </div>
                    ";
                }
            ?>

            </form>
            

            
            
        </div>
    </div>
  </body>
</html>