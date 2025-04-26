<?php
    session_start();

    $Exists = false;
    $conn = new mysqli("mysql", "root", "1234", "study");
    if ($conn->connect_error) {
        die("DB 연결 실패: " . $conn->connect_error);
    }
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];

        // SQL인젝션 방어버전
        // $sql = "SELECT * FROM users WHERE id = ?";
        // $stmt = $conn->prepare($sql);
        // $stmt->bind_param("ss", $id, $pass);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $row = mysqli_fetch_array($result);
        // $Exists = $result->num_rows > 0;

        // SQL인젝션 취약버전
        $sql = "SELECT * FROM users WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        $Exists = mysqli_fetch_array($result);
        $row = $Exists;
    }
?>

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
            <div class="d-flex flex-column align-items-center" style="margin-top: 70px">
                <img src="../../../images/camel.png" style="width: 100px;"/>
                <h2>CAMEL</h2>
            </div>

            <!-- Login Form -->
            <?php 
                if($Exists){
                    $username = $row['name'];
                    $point = $row['point'];

                    echo "<div class='text-center mt-5 fw-bold'>환영합니다.</br> $username</div>";
                    echo "<div class='text-center mb-2 fw-bold'>당신의 점수는 $point 입니다</div>";
                    echo "<button type='button' class='btn btn-success m-3' onClick='window.location.href=\"/example/login-case/session/logout.php\"'>다시 로그인</button>";
                } else {
                    echo '
                    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane" aria-selected="true">ID/전화번호</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="temp-tab" data-bs-toggle="tab" data-bs-target="#temp-tab-pane" type="button" role="tab" aria-controls="temp-tab-pane" aria-selected="false" onClick="window.location.href=\'/example/index.php\'">이전으로</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active border border-top-0 rounded-bottom rounded-top-end" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
                            <form id="loginForm" class="d-flex justify-content-center flex-column" action="/example/login-case/session/sessionLogin.php" method="POST">
                                <div class="input-group p-3 pb-0">
                                    <input required type="text" class="form-control" placeholder="아이디" name="id" aria-label="Recipient\'s id" aria-describedby="button-addon2">
                                </div>

                                <div class="input-group p-3 pt-0 pb-0">
                                    <input required type="password" class="form-control" placeholder="패스워드" name="pass" aria-label="Recipient\'s password" aria-describedby="button-addon2">
                                </div>

                                <button type="submit" class="btn btn-success m-3">세션 로그인</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="temp-tab-pane" role="tabpanel" aria-labelledby="temp-tab" tabindex="0">TEMP</div>
                        <div class="tab-pane fade" id="qr-tab-pane" role="tabpanel" aria-labelledby="qr-tab" tabindex="0">QR</div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="/signup.php" class="m-3 mt-1 text-decoration-none nav-item" style="cursor:pointer;">회원가입</a>
                    </div>
                    ';
                }
            ?>
        </div>
    </div>
  </body>
</html>