<?php
    $isLogin = false;
    $name = "";

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

        // SQL인젝션 취약버전
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_array($result);
        $Exists = $result->num_rows > 0;

        if($Exists){
            $isLogin = true;
            $name = $row['name'];
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
    <title>Martin</title>
  </head>
  <body class="bg-white">
    <div class="d-flex justify-content-center">
        <div class="d-flex flex-column f-weight">
            <!-- Logo -->
            <div class="d-flex flex-column align-items-center" style="margin-top: 70px">
                <img src="./images/camel.png" style="width: 100px;"/>
                <h2>CAMEL</h2>
            </div>

            <?php 
                if($isLogin){
                    echo "<div class='text-center mt-5 fw-bold'>환영합니다.</br> $name</div>";
                    echo "<button type='button' class='btn btn-success m-1 mt-5' onClick='window.location.href=\"/board/list.php\"'>게시판 이동</button>";
                    echo "<button type='button' class='btn btn-danger m-1' onClick='window.location.href=\"/logout.php\"'>로그아웃</button>";
                } else {
                    echo '
                        <!-- Login Form -->
                            <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="cookie-login-tab" data-bs-toggle="tab" data-bs-target="#cookie-login-tab-pane" type="button" role="tab" aria-controls="cookie-login-tab-pane" aria-selected="true">쿠키 로그인</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="session-login-tab" data-bs-toggle="tab" data-bs-target="#session-login-tab-pane" type="button" role="tab" aria-controls="session-login-tab-pane" aria-selected="false" >세션 로그인</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active border border-top-0 rounded-bottom rounded-top-end" id="cookie-login-tab-pane" role="tabpanel" aria-labelledby="cookie-login-tab" tabindex="0">
                                    <form class="d-flex justify-content-center flex-column" action="login.php" method="POST">
                                        <div class="input-group p-3 pb-0">
                                            <input required type="text" class="form-control" placeholder="아이디" name="id" aria-label="Recipient\'s id" aria-describedby="button-addon2">
                                        </div>

                                        <div class="input-group p-3 pt-0 pb-0">
                                            <input required type="password" class="form-control" placeholder="패스워드" name="pass" aria-label="Recipient\'s password" aria-describedby="button-addon2">
                                        </div>

                                        <input type="hidden" name="loginType" value="cookie">

                                        <button type="submit" class="btn btn-success m-3">로그인</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade border border-top-0 rounded-bottom rounded-top-end" id="session-login-tab-pane" role="tabpanel" aria-labelledby="session-login-tab" tabindex="1">
                                    <form class="d-flex justify-content-center flex-column" action="login.php" method="POST">
                                        <div class="input-group p-3 pb-0">
                                            <input required type="text" class="form-control" placeholder="아이디" name="id" aria-label="Recipient\'s id" aria-describedby="button-addon2">
                                        </div>

                                        <div class="input-group p-3 pt-0 pb-0">
                                            <input required type="password" class="form-control" placeholder="패스워드" name="pass" aria-label="Recipient\'s password" aria-describedby="button-addon2">
                                        </div>

                                        <input type="hidden" name="loginType" value="session">

                                        <button type="submit" class="btn btn-success m-3">로그인</button>
                                    </form>
                                </div>
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