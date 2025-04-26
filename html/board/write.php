<?php
    $type = "new";
    $message = null;
    $message_type = null;


    // Post 요청
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // 등록
        if($_POST['_method'] == "POST"){
            session_start();
            if(isset($_SESSION['id']) || isset($_COOKIE['loginUser'])){

                if(isset($_SESSION['id'])){
                    $id = $_SESSION['id'];
                } else {
                    $id = $_COOKIE['loginUser'];
                }

                $conn = new mysqli("mysql", "root", "1234", "study");
                if ($conn->connect_error) {
                    die("DB 연결 실패: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = mysqli_fetch_array($result);
                $Exists = $result->num_rows > 0;

                $writer = $row['id'];

                if(!$Exists){
                    header("Location: /index.php");
                } else {

                    $id = $_post['id']??null;

                    if(!$id){
                        $title = $_POST['title'];
                        $contents = $_POST['contents'];
                        $sql = "INSERT INTO board (title, contents, writer) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sss", $title, $contents, $writer);
                        if($stmt->execute()){
                            header("Location: /board/list.php");
                        } else {
                            $message = "게시글 등록 실패";
                            $message_type = "error";
                        }
                    }
                    
                    
                }
            } else {
                header("Location: /index.php");
            }
        // 수정
        } else if($_POST['_method'] == "PUT"){
            $id = $_POST['id']??null;
            $title = $_POST['title']??null;
            $contents = $_POST['contents']??null;
            $writer = "";
            
            if($id){
                $conn = new mysqli("mysql", "root", "1234", "study");
                if ($conn->connect_error) {
                    die("DB 연결 실패: " . $conn->connect_error);
                }

                $sql = "UPDATE board SET title = ?, contents = ?, updated_at = NOW() WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $title, $contents, $id);

                if($stmt->execute()){
                    header("Location: /board/list.php");
                } else {
                    $message = "게시글 수정 실패";
                    $message_type = "error";
                }
            }
        }
        

    // Get 요청
    } else {
        $type = "new";
        session_start();
        if(isset($_SESSION['id']) || isset($_COOKIE['loginUser'])){

            if(isset($_SESSION['id'])){
                $id = $_SESSION['id'];
            } else {
                $id = $_COOKIE['loginUser'];
            }

            $conn = new mysqli("mysql", "root", "1234", "study");
            if ($conn->connect_error) {
                die("DB 연결 실패: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = mysqli_fetch_array($result);
            $Exists = $result->num_rows > 0;

            $writer = $row['id'];

            if(!$Exists){
                header("Location: /index.php");
            } else {
                // 게시글 조회
                $id = $_GET['id']??null;
                $sql = "SELECT * FROM board WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = mysqli_fetch_array($result);

                // 있는 경우 수정
                if($row){
                    $type = "edit";
                    $title = $row['title'];
                    $contents = $row['contents'];
                } 
            }
        } else {
            header("Location: /index.php");
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
    <!-- JQurey -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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

        .custom-secondary {
            background-color:white;
            border-color: white;
            color: rgb(136, 136, 136); 
        }

        .custom-secondary:hover {
            background-color:white;
            border-color: white;
            color: rgb(168, 168, 168); 
        }
    </style>
    <title>Martin</title>
  </head>
  <body style="background-color: #D9D9D9">
    <?php 
        $firstChar = strtoupper(mb_substr($writer, 0, 1, "UTF-8"));

        if($type == "new"){
            echo "
                <div class=\"d-flex justify-content-center align-items-center\" style=\"height: 100vh\">
                    <form class=\"d-flex flex-column f-weight bg-white\" style=\"width: 80vw; height: 80vh; border-radius: 10px; padding: 3rem;\" action=\"/board/write.php\" method=\"post\">

                        <!-- Title -->
                        <div>
                            <h2 class=\"d-flex flex-row align-items-center\">
                                <input type=\"text\" class=\"form-control\" id=\"title\" name=\"title\" placeholder=\"제목\" style=\"width: 80%\" required>
                                <button type=\"button\" class=\"btn ms-auto custom-secondary\" style=\"with: 150px; padding-top: 5px; padding-bottom: 5px; font-weight: bold;\"  onClick=\"window.location.href='/board/list.php'\">이전으로</button>
                            </h2>
                            <div class=\"d-flex flex-row justify-content-start align-items-center mt-4\">
                                <div class=\"d-flex justify-content-center align-items-center fw-bold\" style=\"width: 30px; height: 30px; border-radius: 50%; background-color: #f5b657; display: inline-flex; color: white;\">
                                    $firstChar
                                </div>
                                <div style=\"margin-left: 10px;\">
                                    $writer
                                </div>

                                <div style=\"margin-left: 10px; color:rgb(161, 147, 147); font-weight: 500;\">
                                </div>

                            </div>
                        </div>

                        <hr style=\"margin-top: 20px; margin-bottom: 40px;\">

                        <textarea class=\"form-control\" id=\"content\" style=\"height: 100%;\" name=\"contents\" placeholder=\"내용을 입력하세요.\" required></textarea>

                        <input type=\"hidden\" name=\"_method\" value=\"POST\">

                        <button type=\"submit\" class=\"btn custom-menu\" style=\"margin-left: auto; width: 70px; margin-top: 30px; padding-top: 5px; padding-bottom: 5px; font-weight: bold;\">등록</button>
                        
                    </form>
                </div>
            ";
        } else {
            echo "
                <div class=\"d-flex justify-content-center align-items-center\" style=\"height: 100vh\">
                    <form class=\"d-flex flex-column f-weight bg-white\" style=\"width: 80vw; height: 80vh; border-radius: 10px; padding: 3rem;\" action=\"/board/write.php\" method=\"POST\">

                        <!-- Title -->
                        <div>
                            <h2 class=\"d-flex flex-row align-items-center\">
                                <input type=\"text\" class=\"form-control\" id=\"title\" name=\"title\" placeholder=\"제목\" style=\"width: 80%\" value=\"$title\" required>
                                <button type=\"button\" class=\"btn ms-auto custom-secondary\" style=\"with: 150px; padding-top: 5px; padding-bottom: 5px; font-weight: bold;\"  onClick=\"history.back()\">이전으로</button>
                            </h2>
                            <div class=\"d-flex flex-row justify-content-start align-items-center mt-4\">
                                <div class=\"d-flex justify-content-center align-items-center fw-bold\" style=\"width: 30px; height: 30px; border-radius: 50%; background-color: #f5b657; display: inline-flex; color: white;\">
                                    $firstChar
                                </div>
                                <div style=\"margin-left: 10px;\">
                                    $writer
                                </div>

                                <div style=\"margin-left: 10px; color:rgb(161, 147, 147); font-weight: 500;\">
                                </div>

                            </div>
                        </div>

                        <hr style=\"margin-top: 20px; margin-bottom: 40px;\">

                        <textarea class=\"form-control\" id=\"content\" style=\"height: 100%;\" name=\"contents\" placeholder=\"내용을 입력하세요.\" required>$contents</textarea>

                        <input type=\"hidden\" name=\"_method\" value=\"PUT\">
                        <input type=\"hidden\" name=\"id\" value=\"$id\">

                        <button type=\"submit\" class=\"btn custom-menu\" style=\"margin-left: auto; width: 70px; margin-top: 30px; padding-top: 5px; padding-bottom: 5px; font-weight: bold;\">수정</button>
                        
                    </form>
                </div>
            ";
        }
    ?>

    <?php if (!is_null($message)) : ?>
        <script>
        <?php if ($message_type == "error"): ?>
            toastr.error("<?= $message ?>");
        <?php else: ?>
            toastr.success("<?= $message ?>");
        <?php endif; ?>
        </script>
    <?php endif; ?>
  </body>
</html>