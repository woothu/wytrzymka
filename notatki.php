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
