<?php

//коннект
include "../elems/link.php";

//проверка авторизован или нет
if(empty($_SESSION['auth'])) {
    //вывод над каждым инпутом
    $isLogin = '';
    $isPass = '';
    $isEmail = '';

    //проверка на заполнение
    if (!empty($_POST['user']) and !empty($_POST['password']) and !empty($_POST['birthday']) and !empty(
        $_POST['email'])) {

        $login = $_POST['user'];
        //хэш
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $birthday = $_POST['birthday'];
        $country = $_POST['country'];


        //проверка на соотв. пароля
        if ($_POST['password'] == $_POST['confirm']) {
            //длина пароля
            $passwordC = strlen($_POST['password']);

            //проверка длины
            if ($passwordC > 5 and $passwordC < 13) {

                //проверка на минимум
                if (preg_match('#^[a-z0-9]{3,}$#i', $login) == 1) {

                    //проверка емайла
                    if (preg_match('#^[a-z0-9_-]+@[a-z0-9]+\.[a-z]{2,3}$#i', $email)) {


                            //запрос на логин в БД
                            $query = "SELECT * FROM user WHERE login = '$login' ";
                            $data = mysqli_query($connect, $query) or die(mysqli_error($connect));
                            $user = mysqli_fetch_assoc($data);

                            if (!$user) {
                                //запись в БД
                                $query = "INSERT INTO user SET login = '$login',password = '$password',
date = NOW() ,email = '$email' ";
                                mysqli_query($connect, $query) or die(mysqli_error($connect));

                                //немедленная авторизация
                                $_SESSION['auth'] = true;
                                //запрос на ид и запись в сессию
                                $id = mysqli_insert_id($connect);
                                $_SESSION['id'] = $id;

                                header('Location:../index.php');

                            } else {
                                $isLogin = "<p class=\"error\">Логин занят.</p>";
                            }


                    } else {
                        $isEmail = "<p class=\"error\">Некорректный ввод e-mail.</p>";
                    }


                } else {
                    $isLogin = "<p class=\"error\">Некорректный логин.</p>";
                }


            }//длина
            else {
                $isPass = "<p class=\"error\">Пароль должен быть не менее 6 символов и не более 12.</p>";
            }

        } else {
            $isPass = "<p class=\"error\">Введенные пароли не совпадают.</p>";
        }

    }
    $content = '';
    $content .= "<form method=\"POST\" action=\"\">";
    $content .= $isLogin;
    $content .= "<input type=\"text\" name=\"user\"> Login<br><br>";
    $content .= $isPass;
    $content .= "<input type=\"password\" name=\"password\"> Password<br><br>";
    $content .= "<input type=\"password\" name=\"confirm\"> Confirm Password<br> <br>";
    $content .= $isEmail;
    $content .= "<input type=\"text\" name=\"email\"> email<br><br>";
    $content .= "<input type=\"submit\" ><br></form>";
}else{
header('Location:login.php');
}


include "../elems/layout.php";
?>