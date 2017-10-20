<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,
initial-scale=1">
    <title>
        Strona główna
    </title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./style.css">

    <!--//skrypty powinny być na dole body dla szybkości ładowania strony, są w głowie dla czytelności body.-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        #zaloguj {
            color: rgba(0, 0, 0, 1);
            background-color: rgba(230, 216, 216, 1);
            background: -webkit-linear-gradient(top, rgba(207, 206, 214, 1) 0%, rgba(230, 216, 216, 1) 100%);
            background: linear-gradient(to bottom, rgba(207, 206, 214, 1) 0%, rgba(230, 216, 216, 1) 100%);
        }

    </style>
</head>

<body>
    <?php
    session_start();
    include "szkielet.php";
?>
        <form method="POST">
            <table cellpadding="0" cellspacing="0" width="180">

                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td width="50">Login:</td>
                    <td><input type="text" name="login" maxlength="32"></td>
                </tr>
                <tr>
                    <td width="50">Hasło:</td>
                    <td><input type="password" name="haslo" maxlength="32"></td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><input type="submit" name="action" value="Zaloguj"><br></td>
                </tr>

            </table>
        </form>
        <?php
$conn = mysqli_connect("localhost","root","","woothu") or die("Nie mozna polaczyc sie z baza danych:". mysqli_connect_error());
    if (isset($_POST['action'])){
$login = substr(addslashes(htmlspecialchars($_POST['login'])),0,32);
$haslo = substr(addslashes($_POST['haslo']),0,32);
$haslo = md5($haslo); //szyfrowanie hasla
    if (!$login OR empty($login)) {
echo '<p class="alert">Wypełnij pole z loginem!</p>';
exit;
}
    if (!$haslo OR empty($haslo)) {
echo '<p class="alert">Wypełnij pole z hasłem!</p>';
exit;
}
$istnick = mysqli_fetch_array((mysqli_query($conn,"SELECT COUNT(*) FROM `users` WHERE `login` = '$login' AND `password` = '$haslo'")),MYSQLI_NUM); // sprawdzenie czy istnieje uzytkownik o takim nicku i hasle
    if ($istnick[0] == 0) {
echo 'Logowanie nieudane. Sprawdź pisownię nicku oraz hasła.';
    } else {
$_SESSION['nick'] = $login;
$_SESSION['id'] =(mysqli_fetch_object(mysqli_query($conn,"SELECT id FROM users WHERE `login` = '$login'")))->id;
die("
<script>
    location.href = 'index.php'
</script>");
}
    }
?>

</body>

</html>
