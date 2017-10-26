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

    $id = $_SESSION["id"];
    $login = $_SESSION["nick"];
    $conn = mysqli_connect("localhost","root","","woothu") or die("Nie mozna polaczyc sie z baza danych:". mysqli_connect_error());

    //obliczenie liczby zapisanych na koncie wynikow
    $lscreenow = mysqli_fetch_array((mysqli_query($conn,"SELECT COUNT(*) FROM screen WHERE loginid='$id'")),MYSQLI_NUM);
    $lscr = $lscreenow[0];

    //wybieranie wynikow uzytkownika
    $query = mysqli_query($conn,"SELECT * FROM screen WHERE loginid='$id'");
    $czasy = array();
    $rodzaje = array();
    $daty = array();
while($row = mysqli_fetch_array($query)) {
    // Append to the array
    $czasy[] = $row['time'];
    $rodzaje[] =$row['typ'];
    $daty[] = $row['date'];
}

    if($lscr == 0){
       echo "Nie masz jeszcze żadnych screenów";
    }else{
       $count = count($czasy)-1;
       for($i=$count; $i>-1 ; $i--){
        $w= $i+1;
        $czas = $czasy[$i];
        $dir  = "users/$login/$czas.png";
        echo "<div class='glowne'><p>$w. Rozwiązanie obliczenia $rodzaje[$i] zapisane $daty[$i]:</p><img src='$dir'></div>";
        }

    }


?>


</body>

</html>
