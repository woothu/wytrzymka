<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,
initial-scale=1">
    <title>
        Zginanie
    </title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">

    <!--//skrypty powinny być na dole body dla szybkości ładowania strony, są w głowie dla czytelności body.-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="canvas/canvasjs.min.js"></script>

    <style>
        #naglowek4 {
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
        $_SESSION['logged']=1;
    }else{
        include "szkielet.php";
        $_SESSION['logged']=0;
    }
    include "profil3.php";
?>


</body>

</html>
