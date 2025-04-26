<?php
    $PAGE_NUMBER = $_GET['page'] ?? 1;

    session_start();
    // 로그인한 경우만, Session, Cookie
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

        // SQL인젝션 취약버전
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_array($result);
        $Exists = $result->num_rows > 0;

        if(!$Exists){
            header("Location: /index.php");
        } else {
            $sql = "SELECT * FROM board ORDER BY created_at DESC LIMIT 10 OFFSET ?";
            $stmt = $conn->prepare($sql);
            $offset = ($PAGE_NUMBER - 1) * 10;
            $stmt->bind_param("i", $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = [];
            while($row = mysqli_fetch_array($result)){

                $createdAt = strtotime($row['created_at']);
                $now = time(); // 현재 시간
                $diff = $now - $createdAt; // 시간 차이 (초 단위)

                // 조건 분기
                if ($diff < 60) {
                    // 1분 이내
                    $row['created_at'] = $diff . "초 전";
                } elseif ($diff < 600) {
                    // 10분 이내
                    $row['created_at'] = floor($diff / 60) . "분 전";
                } else {
                    // 10분 이상
                    $row['created_at'] = date("Y-m-d, H:i:s", $createdAt);
                }

                $rows[] = $row;
            }
        }
    } else {
        header("Location: /index.php");
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

        .custom-primary {
            background-color:#ff8d00; 
            border-color:#ff8d00; 
            color: white;
        }

        .custom-primary:hover {
            background-color: #fca235; 
            border-color: #fca235; 
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
  <body class="bg-white">
    <div class="d-flex justify-content-center">
        <div class="d-flex flex-column f-weight">
            <!-- Logo -->
            <div class="d-flex flex-column align-items-center" style="margin-top: 70px">
                <img src="../../images/camel.png" style="width: 100px;"/>
                <h2>CAMEL 게시판</h2>
            </div>

            <!-- List Vuew -->
            <table class="table mt-5" style="width: 80vw;">
                <colgroup>
                    <col style="width: 10%;">
                    <col style="width: 55%;">
                    <col style="width: 10%;">
                    <col style="width: 15%; text-align: center;">
                    <col style="width: 10%;">
                </colgroup>
                <thead>
                    <tr>
                    <th scope="col">No</th>
                    <th scope="col">제목</th>
                    <th scope="col" style="text-align: center;">작성자</th>
                    <th scope="col" style="text-align: center;">작성일시</th>
                    <th scope="col" style="text-align: center;">조회수</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($rows as $row){
                            echo "<tr class='camel' onClick=\"location.href='/board/detail.php?id={$row['id']}'\">";
                            echo "<th scope='row'>{$row['id']}</th>";
                            echo "<td>{$row['title']}</td>";
                            echo "<td style='text-align: center;'>{$row['writer']}</td>";
                            echo "<td style='text-align: center;'>{$row['created_at']}</td>";
                            echo "<td style='text-align: center;'>{$row['view']}</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>

            <!-- 페이징 -->
            <div class="d-flex justify-content-center mt-3">
                <?php
                    $sql = "SELECT COUNT(*) as total FROM board";
                    $result = $conn->query($sql);
                    $row = mysqli_fetch_array($result);
                    $total = $row['total'];
                    $totalPage = ceil($total / 10);

                    for($i=1; $i<=$totalPage; $i++){
                        if($i == $PAGE_NUMBER){
                            echo "<button type='button' class='btn custom-menu m-1' disabled>$i</button>";
                        } else {
                            echo "<button type='button' class='btn custom-menu m-1' onClick='window.location.href=\"/board/list.php?page=$i\"'>$i</button>";
                        }
                    }
                ?>
            </div>

            <div class="d-flex justify-content-between">
                <button type='button' class='btn custom-secondary m-1' onClick='window.location.href="/index.php"'>메인으로</button>
                <button type='button' class='btn custom-primary m-1' onClick='window.location.href="/board/write.php"'>글쓰기</button>
            </div>
        </div>
    </div>
  </body>
</html>