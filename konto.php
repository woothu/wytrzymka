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
        #naglowek1 {
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
    if(isset($_SESSION['nick'])){
        include "szkieletzal.php";
//        echo $_SESSION['nick'];
    }else{
        die("
<script>
    location.href = 'index.php'
</script>");
    }
?>
        <?php
    $login = $_SESSION["nick"];
    $conn = mysqli_connect("localhost","root","","woothu") or die("Nie mozna polaczyc sie z baza danych:". mysqli_connect_error());
//nadawanie wartosci obrazkom sesji
    for($i=5; $i>0 ; $i--){
    $x='img'.$i;
    $sql="SELECT $x FROM users WHERE `login` = '$login'";
    $img = (mysqli_fetch_object(mysqli_query($conn,$sql)))->$x;
//     echo $x;
$_SESSION[$x] = $img;
        if(isset($_SESSION[$x])){
        $dir  = "users/$login/$_SESSION[$x].png";
        echo "<div class='glowne'><p>Wynik $x:</p><img src='$dir'></div>";
        }
}
if(!isset($_SESSION["img1"])){
       echo "Nie masz jeszcze żadnych screenów";
        }
?>


</body>

</html>
