<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Martin</title>
  </head>
  <body class="bg-white">
    <div class="d-flex justify-content-center">
        <div class="d-flex flex-column f-weight">
            <!-- Logo -->
            <div class="d-flex flex-column align-items-center" style="margin-top: 150px">
                <img src="../images/camel.png" style="width: 100px;"/>
                <h2>CAMEL</h2>
            </div>

            <!-- Result -->
            <div class="d-flex flex-column align-items-center">
                <?php
                    $conn = new mysqli("mysql", "root", "1234", "study");
                    if ($conn->connect_error) {
                        die("DB 연결 실패: " . $conn->connect_error);
                    }
                
                    $id = $_POST['id'];
                    $pass = $_POST['pass'];

                    // SQL인젝션 방어버전
                    // $sql = "SELECT * FROM users WHERE id = ? And pass = ?";
                    // $stmt = $conn->prepare($sql);
                    // $stmt->bind_param("ss", $id, $pass);
                    // $stmt->execute();
                    // $result = $stmt->get_result();
                    // $row = mysqli_fetch_array($result);
                    // $Exists = $result->num_rows > 0;

                    // SQL인젝션 취약버전
                    $id_hash = $id . "_hash";
                    $pass_hash = hash("sha256", $pass);
                    $sql = "SELECT * FROM users WHERE id = '$id_hash' AND pass = '$pass_hash'";

                    $result = mysqli_query($conn, $sql);
                    $Exists = mysqli_fetch_array($result);
                    $row = $Exists;
                    
                    if($Exists){
                        $username = $row['name']."님";
                        $point = $row['point']."점";
                        echo "<div class='text-center mt-5 fw-bold'>환영합니다.</br> $username</div>";
                        echo "<div class='text-center mb-2 fw-bold'>식별/인증 동시(Hash)</div>";
                        echo "<button type='button' class='btn btn-success m-3' onClick='history.back()'>다시 로그인</button>";
                    } else {
                        echo "<div class='text-center mt-5 fw-bold'>로그인 실패</div>";
                        echo "<div class='text-center mb-2 fw-bold'>식별/인증 동시(Hash)</div>";
                        echo "<button type='button' class='btn btn-success m-3' onClick='history.back()'>다시시도</button>";
                    }
                ?>
            </div>
        </div>
    </div>
    
  </body>
</html>