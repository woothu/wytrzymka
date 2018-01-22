<?php
$x = 2;
if($x==3){$result = file_put_contents( 'wyniki' . time() . '.png', base64_decode( str_replace('data:image/png;base64,','',$_POST['image'] ) ) );}
else{
   echo "<script>";
echo "alert('You are logged out');";
echo "window.location = '../profil3.php';"; // redirect with javascript, after page loads
echo "</script>";
}

?>


/// skrypt canvasa

 <script src="2canvas/html2canvas.js"></script>
<!--        <script src="/site/js/prettify.js"></script>-->
        <script>

            $('#dodaj_obciazenie').on( 'click', function() {

    var screenshot = {};
                var h=1100;
    html2canvas( [ document.getElementById( 'screen' ) ], {
//                html2canvas(document.body, {
        onrendered: function( canvas ) {
            screenshot.img = canvas.toDataURL( "image/png" );
            screenshot.data = { 'image' : screenshot.img, action:'screen' };
            $.ajax({
//                url: "image.php",
//                data:
                data:screenshot.data,
                type: 'post',
                success: function( result ) {
                    console.log( result );
                }
            });
            },
        //można ustawić wysokość zmiennymi ;)
        height: h
    });
});

</script>
//php canvasa
<?php
if(isset($_POST['action'])) {
//  $x = 2;
//if($x==3){$result = file_put_contents( 'wyniki' . time() . '.png', base64_decode( str_replace('data:image/png;base64,','',$_POST['image'] ) ) );}
//else{
   print_r("

	a+b+*c=5
	a-b = u
	u-2*a=4
	2*b=a

	");
//}
    }

?>

// php rownan

<!--
<?php
 include("phpequations.class.php");
 $equations = new phpequations();
?>

<h1>Examples for using phpEquations</h1>


<h2>Roots of a linear equation</h2>

	5 - x + 2*4 = 88

<?php
 print_r($equations->solve("

 	5 - x + 2*4 = 88

	"));
?>

<h2>System of linear equations</h2>

	a+b+2*c=5
	a-b = u
	u-2*a=4
	2*b=a

<?php
$liczba = 2;
 print_r($equations->solve("

	a+b+". $liczba ."*c=5
	a-b = u
	u-2*a=4
	2*b=a

	"));
?>-->
//canvas obrazek

<canvas id="myCanvas" width="578" height="400"></canvas>
    <script>
      var canvas = document.getElementById('myCanvas');
      var context = canvas.getContext('2d');
      var imageObj = new Image();

      imageObj.onload = function() {
        context.drawImage(imageObj, 69, 50);
      };
      imageObj.src = 'http://www.html5canvastutorials.com/demos/assets/darth-vader.jpg';
    </script>

// obrazek o pewnym rozmiarze
    <canvas id="myCanvas" width="578" height="200"></canvas>
    <script>
      var canvas = document.getElementById('myCanvas');
      var context = canvas.getContext('2d');
      var x = 188;
      var y = 30;
      var width = 200;
      var height = 137;
      var imageObj = new Image();

      imageObj.onload = function() {
        context.drawImage(imageObj, x, y, width, height);
      };
      imageObj.src = 'http://www.html5canvastutorials.com/demos/assets/darth-vader.jpg';
    </script>
// jak robic belke canvasa
 //ile pixeli ma widok użytkownika
        //        var clientWidth = document.getElementById('parametry').clientWidth - 20;
        //        var canvas = document.getElementById('belka');
        //        var context = canvas.getContext('2d');
        //        //ile jest pixeli w canvasie
        //        canvas.width = clientWidth;
        //        canvas.height = clientWidth / 10;

        //        //wzór jak definiowac elementy
        //        var utwierdzenie = new Image();
        //        utwierdzenie.src = 'png/utwierdzenie.png';
        //        utwierdzenie.dx = canvas.width*0.011;
        //        utwierdzenie.dy = 0;
        //        utwierdzenie.width = canvas.width*0.04;
        //        utwierdzenie.height = canvas.height;
        //
        //         var belka = new Image();
        //        belka.src = 'png/belka.png';
        //        belka.dx = canvas.width*0.05;
        //        belka.dy = canvas.height*0.5;
        //        belka.width = canvas.width*0.90;
        //        belka.height = canvas.height*0.03;
        //
        //         var podpora = new Image();
        //        podpora.src = 'png/podpora.png';
        //        podpora.dx = canvas.width*0.05- canvas.height*0.2/2 +belka.width*0.1;
        //        podpora.dy = canvas.height*0.52;
        //        podpora.width = canvas.height*0.3;
        //        podpora.height = canvas.height*0.3;
        //
        //        var podporap = new Image();
        //        podporap.src = 'png/podporap.png';
        //        podporap.dx = canvas.width*0.05-canvas.height*0.2/2+belka.width*0.2;
        //        podporap.dy = canvas.height*0.52;
        //        podporap.width = canvas.height*0.3;
        //        podporap.height = canvas.height*0.3;
        //
        //        var sila = new Image();
        //        sila.src = 'png/sila.png';
        //        sila.dx = canvas.width*0.05- (canvas.height*0.05/2) +belka.width*0.3;
        //        sila.dy = 0
        //        sila.width = canvas.height*0.1;
        //        sila.height = canvas.height*0.51;
        //
        //        var moment = new Image();
        //        moment.src = 'png/moment.png';
        //        moment.dx = canvas.width*0.05- canvas.height*0.2/2 +belka.width*0.4;
        //        moment.dy = canvas.height*0.06;
        //        moment.width = canvas.height*0.45;
        //        moment.height = canvas.height*0.45;
        //
        //        var obciazenie = new Image();
        //        obciazenie.src = 'png/obciazenie.png';
        //        obciazenie.dx = canvas.width*0.05 +belka.width*0.7;
        //        obciazenie.dy = canvas.height*0.06;
        //        obciazenie.width = belka.width*0.2;
        //        obciazenie.height = canvas.height*0.45;
        //
        //           var silam = new Image();
        //        silam.src = 'png/silam.png';
        //        silam.dx = canvas.width*0.05- (canvas.height*0.05/2) +belka.width*0.3;
        //        silam.dy = canvas.height*0.52;
        //        silam.width = canvas.height*0.1;
        //        silam.height = canvas.height*0.51;
        //
        //        var momentm = new Image();
        //        momentm.src = 'png/momentm.png';
        //        momentm.dx = canvas.width*0.05- canvas.height*0.2/2 +belka.width*0.4;
        //        momentm.dy = canvas.height*0.06;
        //        momentm.width = canvas.height*0.45;
        //        momentm.height = canvas.height*0.45;
        //
        //        var obciazeniem = new Image();
        //        obciazeniem.src = 'png/obciazeniem.png';
        //        obciazeniem.dx = canvas.width*0.05 +belka.width*0.7;
        //        obciazeniem.dy = canvas.height*0.52;
        //        obciazeniem.width = belka.width*0.2;
        //        obciazeniem.height = canvas.height*0.45;
        //
        //        $("#wybor_profilu").click(function() {
        //            context.drawImage(belka, belka.dx, belka.dy, belka.width, belka.height);
        //            context.drawImage(utwierdzenie, utwierdzenie.dx, utwierdzenie.dy, utwierdzenie.width, utwierdzenie.height);
        //            context.drawImage(podpora, podpora.dx, podpora.dy, podpora.width, podpora.height);
        //            context.drawImage(podporap, podporap.dx, podporap.dy, podporap.width, podporap.height);
        //            context.drawImage(sila, sila.dx, sila.dy, sila.width, sila.height);
        //            context.drawImage(moment, moment.dx, moment.dy, moment.width, moment.height);
        //            context.drawImage(obciazenie, obciazenie.dx, obciazenie.dy, obciazenie.width, obciazenie.height);
        //            context.drawImage(silam, silam.dx, silam.dy, silam.width, silam.height);
        //            context.drawImage(momentm, momentm.dx, momentm.dy, momentm.width, momentm.height);
        //            context.drawImage(obciazeniem, obciazeniem.dx, obciazeniem.dy, obciazeniem.width, obciazenie.height);
        //        });
        //czyszczenie
        //        context.clearRect(0, 0, canvas.width, canvas.height);
