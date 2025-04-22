<?php 
    session_start();
    $_SESSION = []; // 세션 배열 비우기
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy(); // 세션 파괴
    header("Location: /login-case/session/index.php");
?>