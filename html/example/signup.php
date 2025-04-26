<?php
// 결과 플래그 초기화
$alreadyExists = null;
$message = null;
$message_type = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $pass = $_POST['pass'] ?? '';

    $conn = new mysqli("mysql", "root", "1234", "study");
    if ($conn->connect_error) {
        die("DB 연결 실패: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $alreadyExists = $result->num_rows > 0;
    if(!$alreadyExists && !is_null($id) && $id != "") {
        $point = rand(50,100);
        // 일반 데이터 입력
        $sql = "INSERT INTO users values(?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssd", $id, $name, $email, $pass, $point);
        $stmt->execute();
        $result = $stmt->get_result();

        // Hash 데이터 테스트용
        $sql = "INSERT INTO users values(?,?,?,?,?)";
        $stmt = $conn->prepare($sql);

        $id_hash = $id . "_hash";
        $name_hash = $name . "_hash";
        $pw_hash = hash("sha256", $pass);

        $stmt->bind_param("ssssd", $id_hash, $name_hash, $email, $pw_hash, $point);
        $stmt->execute();
        $result = $stmt->get_result();
        header("Location: /example/signupOk.php");
    } else {
        $message = "이미 사용중인 ID입니다";
        $message_type = "error";
    }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- JQurey -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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

            <!-- Signup Form -->
            <div class="tab-pane fade show active border rounded" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
                <form class="d-flex justify-content-center flex-column" autocomplete="off" action="signup.php" method="POST">
                    <div class="input-group p-3 pb-0 m-1">
                        <input type="text" required  class="form-control" placeholder="아이디" name="id" aria-label="Recipient's id" aria-describedby="button-addon2">
                    </div>

                    <div class="input-group p-3 pt-0 pb-0 m-1">
                        <input type="text" required class="form-control" placeholder="이름" name="name" aria-label="Recipient's name" aria-describedby="button-addon2">
                    </div>

                    <div class="input-group p-3 pt-0 pb-0 m-1">
                        <input type="email" required class="form-control" placeholder="이메일" name="email" aria-label="Recipient's email" aria-describedby="button-addon2">
                    </div>

                    <div class="input-group p-3 pt-0 pb-0 m-1">
                        <input type="password" required class="form-control" placeholder="패스워드" name="pass" aria-label="Recipient's username" aria-describedby="button-addon2">
                    </div>

                    <button type="submit" class="btn btn-success m-3">회원가입</button>
                </from>
            </div>

            <div class="d-flex justify-content-end">
                <a href="/example" class="m-3 mt-1 text-decoration-none nav-item" style="cursor:pointer;">이전으로</a>
            </div>
        </div>
    </div>

    

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