<?php
function renderClientSessionJS()
{
    if (isset($_SESSION['login_info'])) {
        $info = json_encode($_SESSION['login_info']);

        // Nếu chưa từng set bên client
        if (!isset($_SESSION['set_client_session'])) {
            echo "<script>
                    sessionStorage.setItem('loginAccount', JSON.stringify($info));
                    sessionStorage.setItem('login', true);
                  </script>";
            $_SESSION['set_client_session'] = true;
        }
    } else {
        echo "<script>sessionStorage.removeItem('loginAccount');</script>";
        echo "<script>sessionStorage.removeItem('login');</script>";
    }
}
