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
        #zarejestruj {
            color: rgba(0, 0, 0, 1);
            background-color: rgba(230, 216, 216, 1);
            background: -webkit-linear-gradient(top, rgba(207, 206, 214, 1) 0%, rgba(230, 216, 216, 1) 100%);
            background: linear-gradient(to bottom, rgba(207, 206, 214, 1) 0%, rgba(230, 216, 216, 1) 100%);
        }

    </style>
</head>

<body>
    <?php
    include "szkielet.php";
?>

        <?php
// zmienna do łączenia zbazą woothu
$conn = mysqli_connect("localhost","root","","woothu") or die("Nie mozna polaczyc sie z baza danych:". mysqli_connect_error());
$ip = $_SERVER['REMOTE_ADDR'];
//po klinknięciu
    if (isset($_POST['action'])) {
$nick = substr(addslashes(htmlspecialchars($_POST['nick'])),0,32);
$haslo = substr(addslashes($_POST['haslo']),0,32);
$vhaslo = substr($_POST['vhaslo'],0,32);
$email = substr($_POST['email'],0,32);
$vemail = substr($_POST['vemail'],0,32);
$nick = trim($nick);
//kilka sprawdzen co do nicku i maila
$sql2="SELECT COUNT(*) FROM users WHERE login='$nick' LIMIT 1";
$result2=mysqli_query($conn,$sql2);
$spr1 = mysqli_fetch_array($result2,MYSQLI_NUM); //czy user o takim nicku istnieje
$sql3="SELECT COUNT(*) FROM users WHERE email='$email' LIMIT 1";
$result3=mysqli_query($conn,$sql3);
$spr2 = mysqli_fetch_array($result3,MYSQLI_NUM); // czy user o takim emailu istnieje
$pos = strpos($email, "@");
$pos2 = strpos($email, ".");
$komunikaty = '';
$spr4 = strlen($nick);
$spr5 = strlen($haslo);
//sprawdzenie co uzytkownik zle zrobil
if (!$nick || !$email || !$haslo || !$vhaslo || !$vemail ) {
$komunikaty .= "Musisz wypełnić wszystkie pola!<br>"; }
if ($spr4 < 4) {
$komunikaty .= "Login musi mieć przynajmniej 4 znaki<br>"; }
if ($spr5 < 4) {
$komunikaty .= "Hasło musi mieć przynajmniej 4 znaki<br>"; }
if ($spr1[0] >= 1) {
$komunikaty .= "Ten login jest zajęty!<br>"; }
if ($spr2[0] >= 1) {
$komunikaty .= "Ten e-mail jest już używany!<br>"; }
if ($email != $vemail) {
$komunikaty .= "E-maile się nie zgadzają ...<br>";}
if ($haslo != $vhaslo) {
$komunikaty .= "Hasła się nie zgadzają ...<br>";}
if ($pos == false OR $pos2 == false) {
$komunikaty .= "Nieprawidłowy adres e-mail<br>"; }

//jesli cos jest nie tak to blokuje rejestracje i wyswietla bledy
if ($komunikaty) {
echo '
<b>Rejestracja nie powiodła się, popraw następujące błędy:</b><br>
'.$komunikaty.'<br>';
} else {
//jesli wszystko jest ok dodaje uzytkownika i wyswietla informacje
$nick = str_replace ( ' ','', $nick );
$haslo = md5($haslo); //szyfrowanie hasla

$sql = "INSERT INTO users (login, email, password, ip) VALUES('$nick','$email','$haslo','$ip')";

if(mysqli_query($conn, $sql)){
mkdir("users/$nick");
echo '<br><span style="color: green; font-weight: bold;">Zostałeś zarejestrowany '.$nick.'. Teraz możesz się zalogować</span><br>';
echo '<br><a href="zaloguj.php">Logowanie</a>';
}else{
echo "Błąd: Unable to execute $sql" .
mysqli_error($conn);
}
}
}

?>
        <form method="post" action="">
            <table>
                <tr class="tlo-b">
                    <td>Nick:</td>
                    <td><input maxlength="18" type="text" name="nick"></td>
                </tr>
                <tr class="tlek">
                    <td>Hasło:</td>
                    <td><input maxlength="32" type="password" name="haslo"></td>
                </tr>
                <tr class="tlo-b">
                    <td>Powtórz hasło:</td>
                    <td><input maxlength="32" type="password" name="vhaslo"></td>
                </tr>
                <tr class="tlo-b">
                    <td>E-mail:</td>
                    <td><input type="text" name="email" maxlength="50"></td>
                </tr>
                <tr class="tlek">
                    <td>Powtórz E-mail:</td>
                    <td><input type="text" maxlength="50" name="vemail"></td>
                </tr>


                <tr>
                    <td colspan="2" align="center"><input type="submit" name="action" value="Zarejestruj"></td>
                </tr>
            </table>
        </form>

</body>

</html>
