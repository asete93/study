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

            <!-- Login Form -->
            <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane" aria-selected="true">ID/전화번호</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="temp-tab" data-bs-toggle="tab" data-bs-target="#temp-tab-pane" type="button" role="tab" aria-controls="temp-tab-pane" aria-selected="false" onClick="window.location.href='/login-case/cookie/index.php'">쿠키 로그인</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="qr-tab" data-bs-toggle="tab" data-bs-target="#qr-tab-pane" type="button" role="tab" aria-controls="qr-tab-pane" aria-selected="false" onClick="window.location.href='/login-case/session/index.php'">세션 로그인</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active border border-top-0 rounded-bottom rounded-top-end" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
                    <form id="loginForm" class="d-flex justify-content-center flex-column" action="login.php" method="POST">
                        <div class="input-group p-3 pb-0">
                            <input required type="text" class="form-control" placeholder="아이디" name="id" aria-label="Recipient's id" aria-describedby="button-addon2">
                        </div>

                        <div class="input-group p-3 pt-0 pb-0">
                            <input required type="password" class="form-control" placeholder="패스워드" name="pass" aria-label="Recipient's password" aria-describedby="button-addon2">
                        </div>


                        <div class="form-check m-3">
                            <input class="form-check-input" type="radio" name="loginType" id="loginType0" value="basic" checked>
                            <label class="form-check-label" for="loginType0">
                                기본
                            </label>
                        </div>
                        <div class="form-check m-3 mt-0">
                            <input class="form-check-input" type="radio" name="loginType" id="loginType1" value="both">
                            <label class="form-check-label" for="loginType1">
                                식별/인증 동시
                            </label>
                        </div>
                        <div class="form-check m-3 mt-0">
                            <input class="form-check-input" type="radio" name="loginType" id="loginType2" value="sep">
                            <label class="form-check-label" for="loginType2">
                                식별/인증 분리
                            </label>
                        </div>
                        <div class="form-check m-3 mt-0">
                            <input class="form-check-input" type="radio" name="loginType" id="loginType3" value="bothHash">
                            <label class="form-check-label" for="loginType3">
                                식별/인증 동시 (Hash)
                            </label>
                        </div>
                        <div class="form-check m-3 mt-0">
                            <input class="form-check-input" type="radio" name="loginType" id="loginType4" value="sepHash">
                            <label class="form-check-label" for="loginType4">
                                식별/인증 분리 (Hash)
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success m-3">로그인</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="temp-tab-pane" role="tabpanel" aria-labelledby="temp-tab" tabindex="0">TEMP</div>
                <div class="tab-pane fade" id="qr-tab-pane" role="tabpanel" aria-labelledby="qr-tab" tabindex="0">QR</div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="/signup.php" class="m-3 mt-1 text-decoration-none nav-item" style="cursor:pointer;">회원가입</a>
            </div>
        </div>
    </div>
  </body>

  <!-- radio에 맞게 동적 action 설정 -->
  <script>
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', function (e) {
        const selected = document.querySelector('input[name="loginType"]:checked').value;

        switch (selected) {
            case 'basic':
                form.action = 'login.php';
                break;
            case 'both':
                form.action = 'login-case/loginWithIdAndAuth.php';
                break;
            case 'sep':
                form.action = 'login-case/loginSepIdAndAuth.php';
                break;
            case 'bothHash':
                form.action = 'login-case/loginWithIdAndAuthAndHash.php';
                break;
            case 'sepHash':
                form.action = 'login-case/loginSepIdAndAuthAndHash.php';
                break;
        }
    });
    </script>
</html>