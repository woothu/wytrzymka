<?php
//kiedy ktoś chce zapisać wyniki
if (isset($_POST['action'])){
    $id = $_SESSION["id"];
    $login = $_SESSION["nick"];
    $typ ='zginania';
    $time=time();
    $date = date("d/m/Y H:i:s");
//    "mysql.cba.pl","woothu","JCLbelieve910","woothu"
//    $conn = mysqli_connect("localhost","root","","woothu") or die("Nie mozna polaczyc sie z baza danych:". mysqli_connect_error());
    $conn = mysqli_connect("sql.5v.pl","db-user30978","JCLbelieve910","db-user30978") or die("Nie mozna polaczyc sie z baza danych:". mysqli_connect_error());
    //obliczenie liczby zapisanych na koncie wynikow
    $lscreenow = mysqli_fetch_array((mysqli_query($conn,"SELECT COUNT(*) FROM screen WHERE loginid='$id'")),MYSQLI_NUM);

    //usuwanie jednego screena gdy jego limit screenów jest osiągnięty
    while($lscreenow[0]>3){
        //wyrzucanie zdjęcia z folderu
        $czasARRAY = mysqli_fetch_array(mysqli_query($conn, "SELECT time FROM screen WHERE loginid=$id ORDER BY id LIMIT 1"));
        $czasSTRING = $czasARRAY['time'];
        unlink('users/'.$login. '/' . $czasSTRING . '.png');

        // wyrzucanie wiersza z bd
        mysqli_query($conn, "DELETE FROM screen WHERE loginid=$id ORDER BY time LIMIT 1");
        $lscreenow = mysqli_fetch_array((mysqli_query($conn,"SELECT COUNT(*) FROM screen WHERE loginid='$id'")),MYSQLI_NUM);
    }
     // wrzucanie screena do folderu
    $result = file_put_contents( 'users/'.$login. '/' . $time . '.png', base64_decode( str_replace('data:image/png;base64,','',$_POST['image'] ) ) );

    //wpisywanie do bazy
    mysqli_query($conn, "INSERT INTO screen (loginid,time,typ,date) VALUES ('$id','$time', '$typ','$date')");

}

?>

    <!--    parametry-->
    <div class="glowne" id="parametry">
        <h2>Wprowadzanie danych do obliczeń:</h2>
        <!--        dlugosc profilu-->
        <label for="dlugosc">Długość profilu [mm]:</label>
        <input type="number" name="dlugosc" id="dlugosc" size=20px>
        <br /><br />

        <select id="wybor_mat">
          <option value="-1">Wybierz materiał</option>
          <option value="0">Stal</option>
          <option value="1">Aluminium</option>
          <option value="2">Własny materiał</option>
        </select>

        <select id="wybor_profilu">
          <option value="-1">Wybierz profil</option>
          <option value="0">Kołowy</option>
          <option value="1">Kołowy z otworem</option>
          <option value="2">Prostokątny</option>
          <option value="3">Własny profil</option>
        </select>

        <select id="wybor_podpory" class="rysbelka">
          <option value="-1">Wybierz podpory</option>
          <option value="0">Stała i przesuwna</option>
          <option value="1">Utwierdzenie sztywne z lewej strony</option>
            <option value="2">Utwierdzenie sztywne z lewej strony i podpora przesuwna</option>
<!--            Opcja wyłączona gdyż nie działa poprawnie.-->
            <option value="3" style="display:none">Stała i 2 przesuwne</option>
        </select>
          <a style='display:none' id='link' href="https://skyciv.com/free-moment-of-inertia-calculator/">Kalkulator momentów bezwładności profilu</a>
        <br />
        <!--// pierwsze wyswietlanie-->
        <div id="wyswietlanie" style="display:none">

            <!--Przekroje -->
            <div id="przekroje">
                <div id="wlasny_mat" style="display:none">
                    <label for="wlasny_E">Moduł Younga [MPa]:</label>
                    <input type="number" name="wlasny_E" class="pole" id="wlasny_E" size=20px>
                    <br />
                    <br />
                </div>
                <div id="przekroj0" style="display:none">
                    <label for="srednica2">Średnica [mm]:</label>
                    <input type="number" name="srednica2" class="pole" id="srednica2" size=20px>
                    <br />
                </div>
                <div id="przekroj1" style="display:none">
                    <label for="srednica3">Średnica [mm]:</label>
                    <input type="number" name="srednica3" class="pole" id="srednica3" size=20px>
                    <br />
                    <label for="wew_srednica3">Wewnętrzna średnica [mm]:</label>
                    <input type="number" name="wew_srednica3" class="pole" id="wew_srednica3" size=20px>
                </div>
                <div id="przekroj2" style="display:none">
                    <label for="dlugosc_a">Długość boku A [mm]:</label>
                    <input type="number" name="dlugosc_a" class="pole" id="dlugosc_a" size=20px>
                    <br />
                    <label for="dlugosc_b">Długość boku B [mm]:</label>
                    <input type="number" name="dlugosc_b" class="pole" id="dlugosc_b" size=20px>
                </div>
                <div id="przekroj3" style="display:none">
<!--
                    <label for="wlasny_pole">Pole powierzchni [mm2]:</label>
                    <input type="number" name="wlasny_pole" class="pole" id="wlasny_pole" size=20px>
                    <br />
-->
                    <label for="emax">E-max [mm]:</label>
                    <input type="number" name="emax" class="pole" id="emax" size=20px>
                    <br />
                    <label for="wlasny_moment">Moment bezwładności [mm3] :</label>
                    <input type="number" name="wlasny_moment" class="pole" id="wlasny_moment" size=20px>
                </div>
            </div>

            <!--Podpory-->
            <div id="podpory">
                <div id="podpora0" style="display:none">
                    <p>Podpora stała i przesuwna</p>
                    <label for="punktpodpory1">Podpora stała w punkcie:</label>
                    <input type="number" id="punktpodpory1">
                    <br />
                    <label for="punktpodpory2">Podpora przesuwna w punkcie:</label>
                    <input type="number" id="punktpodpory2">
                    <br />
                </div>
                <div id="podpora1" style="display:none">
                    <p>Utwierdzenie sztywne z lewej strony</p>
                </div>
                <div id="podpora2" style="display:none">
                    <p>Utwierdzenie sztywne z lewej strony i podpora przesuwna</p>
                    <label for="punktpodpory3">Podpora przesuwna w punkcie:</label>
                    <input type="number" id="punktpodpory3">
                </div>
                <div id="podpora3" style="display:none">
                    <p>Podpora stała i przesuwna</p>
                    <label for="punktpodpory4">Podpora stała w punkcie:</label>
                    <input type="number" id="punktpodpory4">
                    <br />
                    <label for="punktpodpory5">Podpora przesuwna 1 w punkcie:</label>
                    <input type="number" id="punktpodpory5">
                    <label for="punktpodpory6">Podpora przesuwna 2 w punkcie:</label>
                    <input type="number" id="punktpodpory6">
                    <br />
                </div>

                <br />
            </div>

            <div id="elementy0"></div>
            <div id="przyciski_elementy">
                <input type="button" id="dodaj_sile" value="Dodaj siłę">
                <input type="button" id="dodaj_moment" value="Dodaj moment">
                <input type="button" id="dodaj_obciazenie" value="Dodaj obciazenie">
                <input type="submit" id="licz_napr" value="Oblicz">
                <input style="display:none" type="submit" name="action" id="screen1" value="Zapisz wyniki na koncie">
                <br />
            </div>
        </div>

    </div>
    <div id="screen">
        <!--wyswietlanie wynikow-->
        <div class="glowne" id="wyniki" style="display:none">
            <!--    wyswietlanie parametrow materialu oraz przekroju-->
            <div>

                <!--                <canvas id="belka2" style="margin-top:15px"></canvas>-->
                <p id="wynik_mat"></p>
<!--                <label for="polew" style='display:none'>Pole powierzchni:<p id="polew" ></p></label><br />-->
                <label for="momentw">Moment bezwładności względem osi x:<p  id="momentw"></p></label><br />
                <label for="wskaznikw">Wskaźnik wytrzymałości na zginanie względem osi x:<p  id="wskaznikw"></p></label>
            </div>
            <!--    //div do wyswietlania-->
            <div id="wyniki1"></div>
        </div>
        <canvas id="belka" style="margin:15px; width:100%"></canvas>
        <!--wyświetlanie wykresow-->

        <div id="chartContainer" style="margin:10px"></div>

        <div id="chartContainer2" style="margin:10px"></div>
        <div id="chartContainer3" style="margin:10px"></div>
    </div>


    <!--sprawdzenie wartosci parametrow podczas obliczen-->
    <div id="zapisane_parametry" style="display:none">
        <input type="number" id="E">
        <input type="number" id="G">
        <input type="number" id="pole">
        <input type="number" id="wskaznik">
        <input type="number" id="moment_bez">
        <input type="number" id="liczba_sil" value=1>
        <form method="get">
            <input type="number" id="ray1" name="ray1">
            <input type="number" id="ray2" name="ray2">
            <input type="number" id="Ma" name="Ma">
        </form>
    </div>



    <script>
        $(function() {
            przesuwplus2 = 0;
            przesuwplus3 = 0;
            przesuwplus1 = 0;
            el0=0;
            el1=0;
            el2=0;
            ell0=0;
            //canvas 2 powiela wyniki.
            //            var canvas2 = document.getElementById('belka2');
            //            var context2 = canvas2.getContext('2d');
            //            canvas2.width = $('#belka').width();
            //            canvas2.height = canvas2.width / 10;
            //rysowanie w canvas
            //ile pixeli ma widok użytkownika
            //            clientWidth = document.getElementById('parametry').clientWidth - 20;
            canvas = document.getElementById('belka');
            var context = canvas.getContext('2d');
            //ile jest pixeli w canvasie
            canvas.width = $('#belka').width();
            canvas.height = canvas.width / 10;
            //definiowanie belki
            var belka = new Image();
            belka.dx = canvas.width * 0.05;
            belka.dy = canvas.height * 0.5;
            belka.width = canvas.width * 0.90;
            belka.height = canvas.height * 0.03;
            // definiowanie obiektow
            var utwierdzenie = new Image();
            var silad = new Image();
            var momentd = new Image();
            var obciazenied = new Image();
            var silam = new Image();
            var momentm = new Image();
            var obciazeniem = new Image();
            obciazeniem.src = 'png/obciazeniem.png';
            momentm.src = 'png/momentm.png';
            silam.src = 'png/silam.png';
            obciazenied.src = 'png/obciazenie.png';
            momentd.src = 'png/moment.png';
            silad.src = 'png/sila.png';
            utwierdzenie.src = 'png/utwierdzenie.png';
            belka.src = 'png/belka.png';
            $("#parametry").on('keyup change mouseover', function() {
                //czyszczenie plotna i rysowanie belki
                context.clearRect(0, 0, canvas.width, canvas.height);
                //rysowanie belki



                context.drawImage(belka, belka.dx, belka.dy, belka.width, belka.height);
                var nr_wyboru3 = document.getElementById("wybor_podpory");
                var opcja3 = nr_wyboru3.options[nr_wyboru3.selectedIndex].value;

                if (opcja3 == 0) {
                    var przesuw = Number(document.getElementById("punktpodpory2").value);
                    var stala = Number(document.getElementById("punktpodpory1").value);
                    przesuwplus2 = przesuw;
                    przesuwplus3 = stala;
                    var podpora = new Image();
                    podpora.src = 'png/podpora.png';
                    podpora.dx = canvas.width * 0.05 - canvas.height * 0.3 / 2 + belka.width * (stala / dlugosc);
                    podpora.dy = canvas.height * 0.52;
                    podpora.width = canvas.height * 0.3;
                    podpora.height = canvas.height * 0.3;

                    var podporap = new Image();
                    podporap.src = 'png/podporap.png';
                    podporap.dx = canvas.width * 0.05 - canvas.height * 0.3 / 2 + belka.width * (przesuw / dlugosc);
                    podporap.dy = canvas.height * 0.52;
                    podporap.width = canvas.height * 0.3;
                    podporap.height = canvas.height * 0.3;

                    context.drawImage(podpora, podpora.dx, podpora.dy, podpora.width, podpora.height);
                    context.drawImage(podporap, podporap.dx, podporap.dy, podporap.width, podporap.height);
                }
                if (opcja3 == 1) {
                    utwierdzenie.dx = canvas.width * 0.011;
                    //                                  window.alert(utwierdzenie.dx);
                    utwierdzenie.dy = 0;
                    utwierdzenie.width = canvas.width * 0.04;
                    utwierdzenie.height = canvas.height;
                    context.drawImage(utwierdzenie, utwierdzenie.dx, utwierdzenie.dy, utwierdzenie.width, utwierdzenie.height);
                }
                if (opcja3 == 2) {
                    var przesuw = Number(document.getElementById("punktpodpory3").value);
                    przesuwplus1 = przesuw;
                    utwierdzenie.dx = canvas.width * 0.011;
                    //                                  window.alert(utwierdzenie.dx);
                    utwierdzenie.dy = 0;
                    utwierdzenie.width = canvas.width * 0.04;
                    utwierdzenie.height = canvas.height;

                    var podporap = new Image();
                    podporap.src = 'png/podporap.png';
                    podporap.dx = canvas.width * 0.05 - canvas.height * 0.3 / 2 + belka.width * (przesuw / dlugosc);
                    podporap.dy = canvas.height * 0.52;
                    podporap.width = canvas.height * 0.3;
                    podporap.height = canvas.height * 0.3;

                    context.drawImage(podporap, podporap.dx, podporap.dy, podporap.width, podporap.height);
                    context.drawImage(utwierdzenie, utwierdzenie.dx, utwierdzenie.dy, utwierdzenie.width, utwierdzenie.height);
                }
                if (opcja3 == 3) {
                    var przesuw1 = Number(document.getElementById("punktpodpory5").value);
                    var stala = Number(document.getElementById("punktpodpory4").value);
                    var przesuw2 = Number(document.getElementById("punktpodpory6").value);
                    var podpora = new Image();
                    podpora.src = 'png/podpora.png';
                    podpora.dx = canvas.width * 0.05 - canvas.height * 0.3 / 2 + belka.width * (stala / dlugosc);
                    podpora.dy = canvas.height * 0.52;
                    podpora.width = canvas.height * 0.3;
                    podpora.height = canvas.height * 0.3;

                    var podporap = new Image();
                    podporap.src = 'png/podporap.png';
                    podporap.dx = canvas.width * 0.05 - canvas.height * 0.3 / 2 + belka.width * (przesuw1 / dlugosc);
                    podporap.dy = canvas.height * 0.52;
                    podporap.width = canvas.height * 0.3;
                    podporap.height = canvas.height * 0.3;

                    var podporap2 = new Image();
                    podporap2.src = 'png/podporap.png';
                    podporap2.dx = canvas.width * 0.05 - canvas.height * 0.3 / 2 + belka.width * (przesuw2 / dlugosc);
                    podporap2.dy = canvas.height * 0.52;
                    podporap2.width = canvas.height * 0.3;
                    podporap2.height = canvas.height * 0.3;

                    context.drawImage(podpora, podpora.dx, podpora.dy, podpora.width, podpora.height);
                    context.drawImage(podporap, podporap.dx, podporap.dy, podporap.width, podporap.height);
                    context.drawImage(podporap2, podporap2.dx, podporap2.dy, podporap2.width, podporap2.height);
                }

                for (q = 0; q < typ.length; q++) {
                    var odleglosc = Number(document.getElementById("el" + q).value);
                    if (typ[q] == 2) {
                        var wsila = Number(document.getElementById("eln" + q).value);
                        if (wsila < 0) {
                            silad.dx = canvas.width * 0.05 - (canvas.height * 0.05 / 2) + belka.width * (odleglosc / dlugosc);
                            silad.dy = 0
                            silad.width = canvas.height * 0.1;
                            silad.height = canvas.height * 0.51;
                            context.drawImage(silad, silad.dx, silad.dy, silad.width, silad.height);

                        } else if (wsila > 0) {
                            silam.dx = canvas.width * 0.05 - (canvas.height * 0.075 / 2) + belka.width * (odleglosc / dlugosc);
                            silam.dy = canvas.height * 0.52;
                            silam.width = canvas.height * 0.1;
                            silam.height = canvas.height * 0.51;
                            context.drawImage(silam, silam.dx, silam.dy, silam.width, silam.height);

                        }
                    } else if (typ[q] == 3) {
                        var wmoment = Number(document.getElementById("elm" + q).value);
                        if (wmoment > 0) {
                            momentd.dx = canvas.width * 0.05 - canvas.height * 0.53 / 2 + belka.width * (odleglosc / dlugosc);
                            momentd.dy = canvas.height * 0.06;
                            momentd.width = canvas.height * 0.45;
                            momentd.height = canvas.height * 0.45;
                            context.drawImage(momentd, momentd.dx, momentd.dy, momentd.width, momentd.height);
                        } else if (wmoment < 0) {
                            momentm.dx = canvas.width * 0.05 - canvas.height * 0.2 / 2 + belka.width * (odleglosc / dlugosc);
                            momentm.dy = canvas.height * 0.06;
                            momentm.width = canvas.height * 0.45;
                            momentm.height = canvas.height * 0.45;
                            context.drawImage(momentm, momentm.dx, momentm.dy, momentm.width, momentm.height);
                        }
                    } else {
                        var wobciazenie = document.getElementById("elo" + q).value;
                        var odleglosc2 = document.getElementById("ell" + q).value;
                        if (wobciazenie < 0) {
                            obciazenied.dx = canvas.width * 0.05 + belka.width * (odleglosc / dlugosc);
                            obciazenied.dy = canvas.height * 0.06;
                            obciazenied.width = belka.width * ((odleglosc2 - odleglosc) / dlugosc);
                            obciazenied.height = canvas.height * 0.45;
                            context.drawImage(obciazenied, obciazenied.dx, obciazenied.dy, obciazenied.width, obciazenied.height);
                        } else if (wobciazenie > 0) {
                            obciazeniem.dx = canvas.width * 0.05 + belka.width * (odleglosc / dlugosc);
                            obciazeniem.dy = canvas.height * 0.52;
                            obciazeniem.width = belka.width * ((odleglosc2 - odleglosc) / dlugosc);
                            obciazeniem.height = canvas.height * 0.45;
                            context.drawImage(obciazeniem, obciazeniem.dx, obciazeniem.dy, obciazeniem.width, obciazeniem.height);
                        }
                    }
                }
            });

            //wybor przekroju
            $("#wybor_profilu").change(function() {
                var nr_wyboru = document.getElementById("wybor_profilu");
                opcja = nr_wyboru.options[nr_wyboru.selectedIndex].value;
                if (opcja >= 0) {
                    if (opcja == 3){
                        document.getElementById("link").style.display = 'block';
                    }else{
                        document.getElementById("link").style.display = "none";
                    }
                    document.getElementById("przekroj" + opcja).style.display = "block";
                    for (i = 0; i < 4; i++) {
                        if (i != opcja) {
                            document.getElementById("przekroj" + i).style.display = "none";
                        }
                    }
                }
            });

            //wybor materialu
            $("#wybor_mat").change(function() {
                var nr_wyboru2 = document.getElementById("wybor_mat");
                opcja2 = nr_wyboru2.options[nr_wyboru2.selectedIndex].value;
                if (opcja2 > 2) {
                    E = 0;
                } else {
                    E = Number(document.getElementById("wlasny_E").value);
                    document.getElementById("wynik_mat").innerHTML = "Moduł sprężystości podłużnej Younga materiału wynosi [MPa]: " + E + "<br />";
                    document.getElementById("E").value = E;
                }

                var material = ["Stal", "Aluminium"];
                var wlasny_G = 0;
                var modulY = [200000, 69000];
                var modulG = [80000, 25900];
                if (opcja2 == 2) {
                    document.getElementById("wlasny_mat").style.display = "block";
                } else {
                    document.getElementById("wlasny_mat").style.display = "none";
                }

                for (i = 0; i < material.length; i++) {


                    if (opcja2 == i) {
                        document.getElementById("wynik_mat").innerHTML = "Materiał to: " + material[i] + "<br />" + "Moduł sprężystości podłużnej Younga wynosi [MPa]: " + modulY[i] + "<br />";
                        document.getElementById("E").value = modulY[i];
                        document.getElementById("G").value = modulG[i];
                        E = modulY[i];
                        G = modulG[i];

                    }
                }

            });

            $("#wlasny_E").keyup(function() {
                E = Number(document.getElementById("wlasny_E").value);
                document.getElementById("wynik_mat").innerHTML = "Moduł sprężystości podłużnej Younga materiału wynosi [MPa]: " + E + "<br />";
                document.getElementById("E").value = E;
            });

            //wybor podpory
            $("#wybor_podpory").change(function() {
                var nr_wyboru3 = document.getElementById("wybor_podpory");
                opcja3 = nr_wyboru3.options[nr_wyboru3.selectedIndex].value;
                for (i = 0; i < 4; i++) {
                    if (opcja3 == i) {
                        document.getElementById("podpora" + i).style.display = "block";
                    } else {
                        document.getElementById("podpora" + i).style.display = "none";
                    }
                }
                //wyswietla dalsze parametry do wpisania
                document.getElementById("wyswietlanie").style.display = "block";
            });

            //dodaj sile
            var ii = -1;
            var typ = [];
            $("#dodaj_sile").click(function() {
                ii++;
                jj = ii + 1;
                document.getElementById("elementy" + ii).innerHTML += '<div id="element' + ii + '><p>Siła:</p><label for="eln' + ii + '">Siła [N]:</label><input type="number" id="eln' + ii + '"><br /><label for="el' + ii + '">Punkt oddziaływania siły [mm]:</label><input type="number" id="el' + ii + '"><br /><br /></div><div id="elementy' + jj + '"></div>';
                typ.push(2);
            });

            //dodaj moment skupiony
            $("#dodaj_moment").click(function() {
                ii++;
                jj = ii + 1;
                document.getElementById("elementy" + ii).innerHTML += '<div id="element ' + ii + '><p>Moment skupiony:</p><label for="elm' + ii + '">Moment skupiony[Nm]:</label><input type="number" id="elm' + ii + '"><br /><label for="el' + ii + '">Punkt oddziaływania momentu skupionego [mm]:</label><input type="number" id="el' + ii + '"><br /><br /></div><div id="elementy' + jj + '"></div>';
                typ.push(3);
            });

            //dodaj sile
            $("#dodaj_obciazenie").click(function() {
                ii++;
                jj = ii + 1;
                document.getElementById("elementy" + ii).innerHTML += '<div id="element ' + ii + '"><p>Obciążenie ciągłe:</p><label for="elo' + ii + '">Obciążenie ciągłe[N/m]:</label><input type="number" id="elo' + ii + '"><br /><label for="el' + ii + '">Początek obciążenia ciągłego [mm]:</label><input type="number" id="el' + ii + '"><label for="ell' + ii + '">Koniec obciążenia ciągłego [mm]:</label><input type="number" id="ell' + ii + '"><br /><br /></div><div id="elementy' + jj + '"></div>';
                typ.push(4);
            });
            //dzialania po wprowadzeniu danych
            $(".pole").keyup(function() {
                //obliczenie wspolczynnikow w profilach
                if (opcja == 0) {
                    var D = Number(document.getElementById("srednica2").value);
                    pole = D * D / 4 * Math.PI;
                    moment = Math.PI * D * D * D * D / 64;
                    Wx = moment / (D / 2);
                } else if (opcja == 1) {
                    var D = Number(document.getElementById("srednica3").value);
                    var d = Number(document.getElementById("wew_srednica3").value);
                    pole = D * D / 4 * Math.PI - d * d * 0.25 * Math.PI;
                    moment = (Math.PI * ((D * D * D * D) - (d * d * d * d))) / 64;
                    Wx = moment / (D / 2);
                } else if (opcja == 2) {
                    var a = Number(document.getElementById("dlugosc_a").value);
                    var b = Number(document.getElementById("dlugosc_b").value);
                    pole = a * b;
                    moment = (a * a * a * b) / 12;
                    Wx = moment / (a / 2);
                } else if (opcja == 3) {
                    var emax = Number(document.getElementById("emax").value);
                    pole = 0;
                    moment = Number(document.getElementById("wlasny_moment").value);
                    Wx = moment / emax;
                }
                document.getElementById("wskaznikw").innerHTML = Wx.toFixed(2) + ' mm<sup>3</sup>';
                document.getElementById("momentw").innerHTML = moment.toFixed(2) + ' mm<sup>4</sup>';
                document.getElementById("wskaznik").value = Wx;
                document.getElementById("moment_bez").value = moment;
            });

            $("#dlugosc").keyup(function() {
                dlugosc = Number(document.getElementById("dlugosc").value);
            });

            //Oblicznie  wynikow
            $("#licz_napr").click(function() {
                if (dlugosc > 0 && opcja && opcja2 && opcja3 && Wx > 0 && przesuwplus1>=0 && przesuwplus2>=0 && przesuwplus3>=0 && przesuwplus1<=dlugosc && przesuwplus2<=dlugosc && przesuwplus3<=dlugosc) {
                    //ponowna mozliwość zrobienia screena:
                    document.getElementById("screen1").style.borderColor = "#DDDDDD";

                    //wyswietl div wyniki
                    document.getElementById("wyniki").style.display = "block";

                    //pokaz przycisk zapisz wyniki jezeli user jest zalogowany
                    if (<?php echo $_SESSION['logged']?>) {
                        document.getElementById("screen1").style.display = "inline-block";
                    }

                    // Definiowanie/Czyszczenie tablic
                    var punkt = [];
                    var punktR = [];
                    var sila = [];
                    var silaR = [];
                    var moment = [];
                    var obciazenie = [];
                    var tn = [];
                    var mg = [];
                    var ugiecie = [];
                    var tn2 = [];
                    var mg2 = [];
                    document.getElementById("wyniki1").innerHTML = '';

                    //push punktów do tabeli
                    for (q = 0; q < typ.length; q++) {
                        if (typ[q] == 2) {
                            sila.push(document.getElementById("eln" + q).value);
                            punkt.push(document.getElementById("el" + q).value);
                            moment.push(0);
                            obciazenie.push(0);
                        } else if (typ[q] == 3) {
                            sila.push(0);
                            punkt.push(document.getElementById("el" + q).value);
                            moment.push(document.getElementById("elm" + q).value);
                            obciazenie.push(0);
                        } else {
                            var obc = Number(document.getElementById("elo" + q).value);
                            var p1 = Number(document.getElementById("el" + q).value);
                            var p2 = Number(document.getElementById("ell" + q).value);
                            var pN = p2 - p1;
                            var sila1 = (obc * pN) / 1000;
                            sila.push(0);
                            silaR.push(sila1);
                            punktR.push((p2 + p1) / 2);
                            punkt.push(p1);
                            moment.push(0);
                            obciazenie.push(obc);
                            sila.push(0);
                            punkt.push(p2);
                            moment.push(0);
                            obciazenie.push(obc * (-1));
                        }


                    }
                    //push punktu przedzialu do konca belki
                    sila.push(0);
                    punkt.push(dlugosc);
                    moment.push(0);
                    obciazenie.push(0);


                    //Opcje obliczania reakcji podporowych przy roznych przypadkach
                    //Dla podpory stałej i przegubowej
                    if (opcja3 == 0) {
                        var przesuw = Number(document.getElementById("punktpodpory2").value);
                        var stala = Number(document.getElementById("punktpodpory1").value);
                        //Obliczenie sił reakcji
                        var Ma = 0;
                        var sila_przesuw = 0;
                        for (i = 0; i < sila.length; i++) {
                            var x = ((Number(sila[i])) * ((Number(punkt[i])) - przesuw) / 1000);
                            var y = -(Number(moment[i]))
                            Ma += x;
                            Ma += y;
                        }
                        for (i = 0; i < silaR.length; i++) {
                            var x = ((Number(silaR[i])) * ((Number(punktR[i])) - przesuw) / 1000);
                            Ma += x;
                        }
                        var sila_stala = Ma / ((stala - przesuw) / (-1000));

                        for (i = 0; i < sila.length; i++) {
                            sila_przesuw -= Number(sila[i]);
                        }
                        for (i = 0; i < silaR.length; i++) {
                            sila_przesuw -= Number(silaR[i]);
                        }
                        sila_przesuw -= sila_stala;
                        document.getElementById("wyniki1").innerHTML +=
                            '<h4>Reakcja podpory stałej: ' + sila_stala + '   Reakcja podpory przesuwnej: ' + sila_przesuw + '</h4>';
                        //tworzenie danych podpór
                        sila.push(sila_przesuw);
                        punkt.push(przesuw);
                        moment.push(0);
                        obciazenie.push(0);
                        //podpora stała
                        sila.push(sila_stala);
                        punkt.push(stala);
                        moment.push(0);
                        obciazenie.push(0);
                        // Obliczenie C i D
                        ugiet11 = 0;
                        ugiet22 = 0;
                        for (i = 0; i < sila.length; i++) {
                            if ((Number(punkt[i])) <= stala) {
                                var ll1 = (stala - (Number(punkt[i]))) / 1000;
                                var sila_stala = (Number(sila[i])) * ll1 * ll1 * ll1 * (1 / 6);
                                var moment_stala = (Number(moment[i])) * ll1 * ll1 * (1 / 2);
                                var obc_stala = (Number(obciazenie[i])) * ll1 * ll1 * ll1 * ll1 * (1 / 24);
                                ugiet11 += obc_stala;
                                ugiet11 += sila_stala;
                                ugiet11 += moment_stala;
                            }
                            if ((Number(punkt[i])) <= przesuw) {
                                var ll2 = (przesuw - (Number(punkt[i]))) / 1000;
                                var sila_p1 = (Number(sila[i])) * ll2 * ll2 * ll2 * (1 / 6);
                                var moment_p1 = (Number(moment[i])) * ll2 * ll2 * (1 / 2);
                                var obc_p1 = (Number(obciazenie[i])) * ll2 * ll2 * ll2 * ll2 * (1 / 24);
                                ugiet22 += obc_p1;
                                ugiet22 += sila_p1;
                                ugiet22 += moment_p1;
                            }
                        }


                        $.ajax({
                            //                      s
                            type: "POST",
                            data: {
                                ugiet11: ugiet11,
                                ugiet22: ugiet22,
                                stala: stala,
                                przesuw: przesuw
                            },
                            success: function(data) {

                            }
                        })

                        setTimeout(function() {
                            $.ajax({
                                url: "reakcje.json",
                                dataType: "json", //the return type data is jsonn
                                success: function(data) { // <--- (data) is in json format
                                    C = data.C;
                                    D = data.D;
                                    // uruchomienie dalszych obliczen
                                    reakcje_obliczone();
//                                    window.alert(C + " , " + D)

                                }
                            });
                        }, 200);

                    }

                    //dla utwierdzenia stalego
                    if (opcja3 == 1) {
                        //Obliczenie sił reakcji
                        var Ma = 0;
                        var sila_utw = 0;
                        for (i = 0; i < sila.length; i++) {
                            var x = ((Number(sila[i])) * (Number(punkt[i])) / 1000);
                            var y = -(Number(moment[i]))
                            Ma += x;
                            Ma += y;
                        }
                        for (i = 0; i < silaR.length; i++) {
                            var x = ((Number(silaR[i])) * (Number(punktR[i])) / 1000);
                            Ma += x;
                        }
                        //                var moment_utw = Ma ;

                        for (i = 0; i < sila.length; i++) {
                            sila_utw -= Number(sila[i]);
                        }
                        for (i = 0; i < silaR.length; i++) {
                            sila_utw -= Number(silaR[i]);
                        }
                        document.getElementById("wyniki1").innerHTML +=
                            '<h4>Reakcja siły utwierdzenia: ' + sila_utw + '[N]   Reakcja momentu utwierdzenia: ' + Ma + '[Nm]</h4>';
                        //tworzenie danych utwierdzenia
                        sila.push(sila_utw);
                        punkt.push(0);
                        moment.push(Ma);
                        obciazenie.push(0);
                        reakcje_obliczone();
                    }
                    // dla utwierdzenia stałego i podpory przesuwnej
                    if (opcja3 == 2) {
                        //Obliczenie sił reakcji bez siły w podporze przesuwnej.
                        var przesuw3l = Number(document.getElementById("punktpodpory3").value);
                        var Ma = 0;
                        var sila_utw = 0;
                        for (i = 0; i < sila.length; i++) {
                            var x = ((Number(sila[i])) * (Number(punkt[i])) / 1000);
                            var y = -(Number(moment[i]))
                            Ma += x;
                            Ma += y;
                        }
                        for (i = 0; i < silaR.length; i++) {
                            var x = ((Number(silaR[i])) * (Number(punktR[i])) / 1000);
                            Ma += x;
                        }
                        //                var moment_utw = Ma ;

                        for (i = 0; i < sila.length; i++) {
                            sila_utw -= Number(sila[i]);
                        }
                        for (i = 0; i < silaR.length; i++) {
                            sila_utw -= Number(silaR[i]);
                        }
                        $.ajax({
                            //                      s
                            type: "POST",
                            data: {
                                l: przesuw3l,
                                m: Ma,
                                sila: sila_utw
                            },
                            success: function(data) {

                            }
                        })

                        setTimeout(function() {
                            $.ajax({
                                url: "reakcje.json",
                                dataType: "json", //the return type data is jsonn
                                success: function(data) { // <--- (data) is in json format
                                    var Ma3 = data.Ma * 1;
                                    var przesuw3 = data.Ray2 * -1;
                                    var sila_utw3 = data.Ray1 * -1;

                                    document.getElementById("wyniki1").innerHTML +=
                                        '<h4>Reakcja siły utwierdzenia: ' + sila_utw3 + '[N]   Reakcja momentu utwierdzenia: ' + Ma3 + '[Nm] Reakcja podpory przesuwnej: ' + przesuw3 + ' [N]</h4>';
                                    //                        tworzenie danych utwierdzenia
                                    //utwierdzenie
                                    sila.push(sila_utw3);
                                    punkt.push(0);
                                    moment.push(Ma3);
                                    obciazenie.push(0);
                                    //podpora przesuwna
                                    sila.push(przesuw3);
                                    punkt.push(przesuw3l);
                                    moment.push(0);
                                    obciazenie.push(0);

                                    // uruchomienie dalszych obliczen
                                    reakcje_obliczone();


                                }
                            });
                        }, 200);
                    }
                    // dla podpory stalej i 2 przzesuwnych
                    if (opcja3 == 3) {
                        //Obliczenie sił reakcji bez siły w podporze przesuwnej.
                        var przesuw5 = Number(document.getElementById("punktpodpory5").value);
                        var przesuw6 = Number(document.getElementById("punktpodpory6").value);
                        var stala = Number(document.getElementById("punktpodpory4").value);
                        var sila_przesuw2 = 0;
                        var Ma33 = 0;
                        var ugiet1 = 0;
                        var ugiet2 = 0;
                        var ugiet3 = 0;

                        for (i = 0; i < sila.length; i++) {
                            var x = ((Number(sila[i])) * ((Number(punkt[i])) - stala) / 1000);
                            var y = (Number(moment[i])) * (-1);
                            Ma33 += x;
                            Ma33 += y;

                            if ((Number(punkt[i])) <= przesuw6) {
                                var ll3 = (przesuw6 - (Number(punkt[i]))) / 1000;
                                var sila_p2 = (Number(sila[i])) * ll3 * ll3 * ll3 * (1 / 6);
                                var moment_p2 = (Number(moment[i])) * ll3 * ll3 * (1 / 2);
                                var obc_p2 = (Number(obciazenie[i])) * ll3 * ll3 * ll3 * ll3 * (1 / 24);
                                ugiet3 += obc_p2;
                                ugiet3 += sila_p2;
                                ugiet3 += moment_p2;
                            }
                            if ((Number(punkt[i])) <= przesuw5) {
                                var ll2 = (przesuw5 - (Number(punkt[i]))) / 1000;
                                var sila_p1 = (Number(sila[i])) * ll2 * ll2 * ll2 * (1 / 6);
                                var moment_p1 = (Number(moment[i])) * ll2 * ll2 * (1 / 2);
                                var obc_p1 = (Number(obciazenie[i])) * ll2 * ll2 * ll2 * ll2 * (1 / 24);
                                ugiet2 += obc_p1;
                                ugiet2 += sila_p1;
                                ugiet2 += moment_p1;
                            }
                            if ((Number(punkt[i])) <= stala) {
                                var ll1 = (stala - (Number(punkt[i]))) / 1000;
                                var sila_stala = (Number(sila[i])) * ll1 * ll1 * ll1 * (1 / 6);
                                var moment_stala = (Number(moment[i])) * ll1 * ll1 * (1 / 2);
                                var obc_stala = (Number(obciazenie[i])) * ll1 * ll1 * ll1 * ll1 * (1 / 24);
                                ugiet1 += obc_stala;
                                ugiet1 += sila_stala;
                                ugiet1 += moment_stala;
                            }


                        }
                        for (i = 0; i < silaR.length; i++) {
                            var x = ((Number(silaR[i])) * ((Number(punktR[i])) - stala) / 1000);
                            Ma33 += x;

                        }
                        //                        var sila_stala = Ma;

                        for (i = 0; i < sila.length; i++) {
                            sila_przesuw2 = sila_przesuw2 - Number(sila[i]);

                        }
                        for (i = 0; i < silaR.length; i++) {
                            sila_przesuw2 = sila_przesuw2 - Number(silaR[i]);

                        }


//                        window.alert(isNaN(Ma33));

                        //0,1000,2000,-2375,1500,0,125,458.3333333333333

                        $.ajax({
                            type: "POST",
                            data: {
                                pstala: Number(stala),
                                p1przesuwna: przesuw5,
                                p2przesuwna: przesuw6,
                                fmom_stala: Number(Ma33),
                                fsilaprzesuw: Number(sila_przesuw2),
                                ugieta1: Number(ugiet1),
                                ugieta2: Number(ugiet2),
                                ugieta3: Number(ugiet3)
                            },
                            success: function(data) {

                            }
                        })

                        setTimeout(function() {
                            $.ajax({
                                url: "reakcje.json",
                                dataType: "json", //the return type data is jsonn
                                success: function(data) { // <--- (data) is in json format
                                    var Ray41 = data.Ray41;
                                    var Ray42 = data.Ray42;
                                    var Ray43 = data.Ray43;
                                    C = data.C;
                                    D = data.D;

                                    document.getElementById("wyniki1").innerHTML +=
                                        '<h4>Reakcja podpory stałej: ' + Ray41 + '[N]   Reakcja podpory przesuwnej 1: ' + Ray42 + '[N] Reakcja podpory przesuwnej 2: ' + Ray43 + ' [N]</h4>';
                                    //                        tworzenie danych utwierdzenia
                                    //podpora stala
                                    sila.push(Ray41);
                                    punkt.push(stala);
                                    moment.push(0);
                                    obciazenie.push(0);
                                    //podpora przesuwna
                                    sila.push(Ray42);
                                    punkt.push(przesuw5);
                                    moment.push(0);
                                    obciazenie.push(0);
                                    //podpora przesuwna
                                    sila.push(Ray43);
                                    punkt.push(przesuw6);
                                    moment.push(0);
                                    obciazenie.push(0);

                                    // uruchomienie dalszych obliczen
                                    reakcje_obliczone();


                                }
                            });
                        }, 200);
                    }

                    // po obliczeniu reakcji podporowych mamy tablicę z układem sił powalającym na obliczenie sil tnacych i momentow gnacych
                    function reakcje_obliczone() {
                        //ustalanie wspolczynnikow C i D
                        if (opcja3 == 1 || opcja3 == 2) {
                            C = 0;
                            D = 0;
                        }
                        var lpunktow = [];
                        var k = 0;
                        var lp = -1;
                        var lpunktow_suma = 0;
                        var punkt1 = punkt.slice();

                        //kiedy nie są usunięte wszystkie przedzialy w punkt1, definiowanie punktow
                        for (j = 0; j < sila.length; j++) {
                            if (punkt1.length > 0) {
                                var minimum = Number(Math.min.apply(null, punkt1));
                            }
                            if (punkt1.length == 1) {
                                lpunktow[j] = minimum - lpunktow_suma + 1;
                            } else if (j > 0) {
                                lpunktow[j] = minimum - lpunktow_suma;
                            } else {
                                lpunktow[0] = minimum;
                            }
                            lpunktow_suma = lpunktow_suma + lpunktow[j];

                            //usunięcie punktu1 który zostanie wykorzystany jako przedział
                            for (i = 0; i < punkt1.length; i++) {
                                if (punkt1[i] == minimum) {
                                    punkt1.splice(i, 1);
                                    k++;
                                }
                            }
                            //dodawanie do sil tnacych i gnacych sil ktore sa w przedziale
                            for (l = 0; l < lpunktow[j]; l++) {
                                var t = 0;
                                var m = 0;
                                ug = 0;
                                ug = D;
                                lp++;
                                //sprawdzenie dla kazdej sily czy jest w przedziale
                                if (opcja3 == 0 || opcja3 == 3) {
                                    for (i = 0; i < sila.length; i++) {
                                        if (punkt[i] < minimum) {
                                            var ramie = lp - Number(punkt[i]);
                                            var ramiem = ramie / 1000;
                                            t += Number(sila[i]) + (Number(obciazenie[i]) / 1000 * ramie)

                                            m += Number(sila[i]) * ramie / 1000 + (Number(moment[i])) * (1) + ((Number(obciazenie[i])) / 2000000 * ramie * ramie)

                                            //ugięcie belki

                                            ug += Number(sila[i]) * ramiem * ramiem * ramiem * (-1 / 6)
                                            ug += (Number(moment[i])) * ramiem * ramiem * (-1 / 2)
                                            ug += (Number(obciazenie[i])) * ramiem * ramiem * ramiem * ramiem * (-1 / 24)

                                        }
                                    }
                                } else {
                                    for (i = 0; i < sila.length; i++) {
                                        if (punkt[i] < minimum || punkt[i] == 0) {
                                            var ramie = lp - Number(punkt[i]);
                                            var ramiem = ramie / 1000;
                                            t += Number(sila[i]) + (Number(obciazenie[i]) / 1000 * ramie)
                                            if (punkt[i] == 0) {
                                                m += Number(sila[i]) * ramie / 1000 + (Number(moment[i])) * (1) + ((Number(obciazenie[i])) / 2000000 * ramie * ramie)
                                                ug += Number(moment[i]) * ramiem * ramiem * (-1 / 2);
                                                 ug += Number(sila[i]) * ramiem * ramiem * ramiem * (-1 / 6);
                                            } else {
                                                ug += Number(moment[i]) * ramiem * ramiem * (-1 / 2);
                                                m += Number(sila[i]) * ramie / 1000 + (Number(moment[i])) * (1) + ((Number(obciazenie[i])) / 2000000 * ramie * ramie)
                                                ug += Number(sila[i]) * ramiem * ramiem * ramiem * (-1 / 6);
                                            }

                                            //ugięcie belki



                                            ug += Number(obciazenie[i]) * ramiem * ramiem * ramiem * ramiem * (-1 / 24);

                                        }
                                    }

                                }

                                ug += C * lp / 1000;
                                var m_bezwl = Number(document.getElementById("moment_bez").value)
                                var ug1 = ug / (-E * m_bezwl/1000000000);
                                // po tej pętli mamy punkt na wysokości l dla tn i mg. dalej push punktów do tablic wykresu
                                tn.push({
                                    y: t
                                });
                                mg.push({
                                    y: m
                                });
                                ugiecie.push({
                                    y: ug1
                                });
                                tn2.push(t);
                                mg2.push(m);
                            }
                        }

                        //wyswietlanie wykresow
                        var chart = new CanvasJS.Chart("chartContainer", {
                            title: {
                                text: "Wykres sił tnących"
                            },
                            data: [{
                                type: "line",
                                dataPoints: tn,
                                yValueFormatString: "0.000000",
                                xValueFormatString: "###### [mm]",
                            }],
                            axisX: {
                                title: "Długość profilu [mm]",
                                crosshair: {
                                    enabled: true
                                }
                            },

                            animationEnabled: true,
                            animationDuration: 2000,
                            axisY: {
                                title: "Siły tnące [N]",
                                interlacedColor: "#F4F4F4",
                            },
                        });
                        var chart2 = new CanvasJS.Chart("chartContainer2", {
                            title: {
                                text: "Wykres momentu gnącego"
                            },
                            data: [{
                                dataPoints: mg,
                                yValueFormatString: "0.000000",
                                xValueFormatString: "###### [mm]",
                                type: "line"
                            }],
                            axisX: {
                                title: "Długość profilu [mm]",
                                crosshair: {
                                    enabled: true
                                }
                            },

                            animationEnabled: true,
                            animationDuration: 2000,
                            axisY: {
                                title: "Moment gnący [Nm]",
                                interlacedColor: "#F4F4F4",
                            },
                        });

                        var chart3 = new CanvasJS.Chart("chartContainer3", {
                            title: {
                                text: "Ugięcie belki"
                            },
                            data: [{
                                dataPoints: ugiecie,
                                yValueFormatString: "0.000000",
                                xValueFormatString: "###### [mm]",
                                type: "line"
                            }],
                            axisX: {
                                title: "Długość profilu [mm]",
                                crosshair: {
                                    enabled: true
                                }
                            },

                            animationEnabled: true,
                            animationDuration: 2000,
                            axisY: {
                                title: "Ugięcie [mm]",
                                interlacedColor: "#F4F4F4",
                            },
                        });
                        //renderowanie wykresow i reset zmiennych
                        chart.render();
                        chart2.render();
                        if(opcja3 == 0 || opcja3 == 1){
                            chart3.render();
                        }
//                        chart3.render();
                        $("html, body").animate({
                            scrollTop: $('#screen').offset().top
                        }, 1000);
                        var naprmax = (Math.max.apply(null, mg2.map(Math.abs))) * 1000 / Wx;
                        document.getElementById('wyniki1').innerHTML += '<h4>Maksymalne naprężenia to: ' + naprmax.toFixed(3) + '[MPa]</h4>'
                    }


                } else {
                    window.alert("Źle wypełnione pola z danymi!");
                }

            });
        });

        //ponowne rysowanie poprzez kopię
        //rysowanie w canvas




        //            document.getElementById("wyniki1").innerHTML = '';
        //            //pokazanie tabeli
        //            for (k = 0; k < sila.length; k++) {
        //                document.getElementById("wyniki").innerHTML += '<p>Siła: ' + sila[k] + '   punkt:' + punkt[k] + '   moment:  ' + moment[k] + '   obciazenie:  ' + obciazenie[k] + '</p>';
        //            }

    </script>
    <script src="2canvas/html2canvas.js"></script>
    <script>
        $('#screen1').on('click', function() {
            if (document.getElementById("screen1").style.borderColor != "red") {

                //do gory strony zeby nie obcinalo screenow
                $("html, body").animate({
                    scrollTop: 0
                }, 500);

                //opoznione robienie screena(po animacji do gory strony):
                setTimeout(function() {

                    // wylaczenie buttona do nastepnych obliczen:
                    document.getElementById("screen1").style.borderColor = "red";
                    var screenshot = {};
                    var clientHeight = document.getElementById('screen').clientHeight;
                    var BtnVal = $("#screen1").val();
                    html2canvas([document.getElementById('screen')], {
                        onrendered: function(canvas) {
                            screenshot.img = canvas.toDataURL("image/png");
                            screenshot.data = {
                                'image': screenshot.img,
                                action: BtnVal
                            };
                            $.ajax({
                                data: screenshot.data,
                                type: 'post',
                                success: function(result) {

                                }
                            });
                        },
                        height: clientHeight,
                    });
                }, 501)
            } else {
                window.alert("Już zapisałeś wyniki!");
            }
        });

    </script>

    <!--do wyznaczania reakcji-->
    <?php
class numeric
{
    public static function LU($A)
    {
        $n = count($A);
        $n1 = $n - 1;
        $P = array();
        for ($k = 0; $k < $n; ++$k)
        {
            $Pk = $k;
            $Ak = $A[$k];
            $max = abs($Ak[$k]);
            for ($j = $k + 1; $j < $n; ++$j)
            {
                $absAjk = abs($A[$j][$k]);
                if ($max < $absAjk)
                {
                    $max = $absAjk;
                    $Pk = $j;
                }
            }
            $P[$k] = $Pk;
            if ($Pk != $k)
            {
                $A[$k] = $A[$Pk];
                $A[$Pk] = $Ak;
                $Ak = $A[$k];
            }

            $Akk = $Ak[$k];
            for ($i = $k + 1; $i < $n; ++$i)
            {
                $A[$i][$k] /= $Akk;
            }
            for ($i = $k + 1; $i < $n; ++$i)
            {
                $Ai = &$A[$i];
                for ($j = $k + 1; $j < $n1; ++$j)
                {
                    $Ai[$j] -= $Ai[$k] * $Ak[$j];
                    ++$j;
                    $Ai[$j] -= $Ai[$k] * $Ak[$j];
                }
                if ($j === $n1)
                    $Ai[$j] -= $Ai[$k] * $Ak[$j];
            }
        }

        return array('LU' => $A, 'P' => $P);
    }


    public static function LUsolve($LUP, $b)
    {
        $LU = $LUP['LU'];
        $n = count($LU);
        $x = $b;
        $P = $LUP['P'];
        for ($i = $n - 1; $i !== -1; --$i)
            $x[$i] = $b[$i];
        for ($i = 0; $i < $n; ++$i)
        {
            $Pi = $P[$i];
            if ($P[$i] !== $i)
            {
                $tmp = $x[$i];
                $x[$i] = $x[$Pi];
                $x[$Pi] = $tmp;
            }

            $LUi = $LU[$i];
            for ($j = 0; $j < $i; ++$j)
            {
                $x[$i] -= $x[$j] * $LUi[$j];
            }
        }
        for ($i = $n - 1; $i >= 0; --$i)
        {
            $LUi = $LU[$i];
            for ($j = $i + 1; $j < $n; ++$j)
            {
                $x[$i] -= $x[$j] * $LUi[$j];
            }
            $x[$i] /= $LUi[$i];
        }
        return $x;
    }

    public static function solve($A, $b)
    {
        return self::LUsolve(self::LU($A), $b);
    }
}


if(isset($_POST['l'])){
    $l = ($_POST['l'])/1000;
    $wynik_sil = $_POST['sila']*(-1);
    $wynik_momentow = $_POST['m'];

    $A = array(
        array(1, 1, 0), //rownanie sil
        array(0, $l, 1),//rownanie momentow
        array(-(1/6)*$l*$l*$l, 0, 0.5*$l*$l),//rownanie osi ugietej
      );
$b = array($wynik_sil, $wynik_momentow, 0);

$wynn = numeric::solve($A, $b);
//zapisywanie wyników do json
$reakcje = array();
$reakcje['Ray1'] = $wynn[0];
$reakcje['Ray2'] = $wynn[1];
$reakcje['Ma'] = $wynn[2];

$json_data = json_encode($reakcje);
file_put_contents('reakcje.json', $json_data);

}



if(isset($_POST['ugiet11'])){
    $ugiet11 = ($_POST['ugiet11']);
    $ugiet22 = ($_POST['ugiet22']);
    $przesuw = ($_POST['przesuw'])/1000;
    $stala = ($_POST['stala'])/1000;

    $A = array(
        array(1, $stala), //rownanie sil
        array(1, $przesuw),//rownanie momentow
      );
$b = array($ugiet11, $ugiet22);

$wynn = numeric::solve($A, $b);
//zapisywanie wyników do json
$CD = array();
$CD['D'] = $wynn[0];
$CD['C'] = $wynn[1];


$json_data = json_encode($CD);
file_put_contents('reakcje.json', $json_data);
}

if(isset($_POST['pstala'])){
    $ls = ($_POST['pstala'])/1000;
    $lp1 = ($_POST['p1przesuwna'])/1000;
    $lp2 = ($_POST['p2przesuwna'])/1000;
    $wynik_stalej = $_POST['fmom_stala']*(-1);
    $wynik_przesuwnej = $_POST['fsilaprzesuw']*1;
    $wynik_ugietej1 = $_POST['ugieta1']*1;
     $wynik_ugietej2 = $_POST['ugieta2']*1;
     $wynik_ugietej3 = $_POST['ugieta3']*1;

    $lp1odls = ($lp1-$ls);
    $lp2odls = ($lp2-$ls);
    $lp1przedlp2= ($lp2-$lp1);
//    $C3=0;
//    $ray413 = 0;
//    $ray423 = 0;
//    $ray433 = 0;
//    $C4=0;
//    $ray414 = 0;
//    $ray424 = 0;
//    $ray434 = 0;
//    $C5=0;
//    $ray415 = 0;
//    $ray425 = 0;
//    $ray435 = 0;
    if($ls<$lp1 && $ls<$lp2){
        if($lp1< $lp2){
            $C3=$ls;
            $ray413 = 0;
            $ray423 = 0;
            $ray433 = 0;
            $C4=$lp1;
            $ray414 = (-1/6)*$lp1odls*$lp1odls*$lp1odls;
            $ray424 = 0;
            $ray434 = 0;
            $C5=$lp2;
            $ray415 = (-1/6)*$lp2odls*$lp2odls*$lp2odls;
            $ray425 = (-1/6)*$lp1przedlp2*$lp1przedlp2*$lp1przedlp2;
            $ray435 = 0;
            $wynik1 = $wynik_ugietej1;
            $wynik2 = $wynik_ugietej2;
            $wynik3 = $wynik_ugietej3;



        }else if ($lp2< $lp1) {
            $C3=$ls;
            $ray413 = 0;
            $ray423 = 0;
            $ray433 = 0;
            $C4=$lp2;
            $ray414 = (-1/6)*$lp2*$lp2*$lp2;
            $ray424 = 0;
            $ray434 = 0;
            $C5=$lp1;
            $ray415 = (-1/6)*$lp1*$lp1*$lp1;
            $ray425 = (-1/6)*$lp1*$lp1*$lp1;
            $ray435 = 0;
            $wynik1 = $wynik_ugietej1;
            $wynik2 = $wynik_ugietej3;
            $wynik3 = $wynik_ugietej2;

        }

    }
//    if($lp1<$ls || $lp1<$lp2){
//          if($ls<$lp2){
//
//        }else{
//
//        }
//    }
//    if($lp2<$lp1 || $lp2<$ls){
//          if($lp1<$ls){
//
//        }else{
//
//        }
//    }

    $A = array(
        array(0,0, 0, $lp1odls, $lp2odls), //rownanie momo
        array(0,0, 1, 1, 1),//rownanie sil
        array(1,$C3, $ray413, $ray423, $ray433),//rownanie osi ugietej 1 dla x = stala
        array(1,$C4, $ray414, $ray424, $ray434), //rownanie osi ugietej 2 dla x= przesuw1
        array(1,$C5, $ray415, $ray425, $ray435), //rownanie osi ugietej 3 dla x= przesuw2
      );
$b = array($wynik_stalej, $wynik_przesuwnej, $wynik1, $wynik2 ,$wynik3);

// {"C":-151.0416666666663,"D":0,"Ray41":-1656.249999999999,"Ray42":8687.499999999998,"Ray43":-5531.249999999999,"C33":0,"C44":1,"C5":2,"Ray413":0,"Ray423":0,"Ray433":0,"Ray414":-0.16666666666666666,"Ray424":0,"Ray434":0,"Ray415":-1.3333333333333333,"Ray425":-0.16666666666666666,"Ray435":0,"$wynik_stalej":-2375,"$wynik_przesuwnej":1500,"$wynik1":0,"$wynik2":125,"$wynik3":458.3333333333333,"$lp1odls":1,"$lp2odls":2,"$lp2":2}


$wynn = numeric::solve($A, $b);
//zapisywanie wyników do json
$reakcje = array();
$reakcje['C'] = $wynn[1];
$reakcje['D'] = $wynn[0];
$reakcje['Ray41'] = $wynn[2];
$reakcje['Ray42'] = $wynn[3];
$reakcje['Ray43'] = $wynn[4];

$reakcje['C33'] = $C3;
$reakcje['C44'] = $C4;
$reakcje['C5'] = $C5;
$reakcje['Ray413'] = $ray413;
    $reakcje['Ray423'] = $ray423;
    $reakcje['Ray433'] = $ray433;
    $reakcje['Ray414'] = $ray414;
    $reakcje['Ray424'] = $ray424;
    $reakcje['Ray434'] = $ray434;
    $reakcje['Ray415'] = $ray415;
    $reakcje['Ray425'] = $ray425;
    $reakcje['Ray435'] = $ray435;

    $reakcje['$wynik_stalej'] = $wynik_stalej;
    $reakcje['$wynik_przesuwnej'] = $wynik_przesuwnej;
    $reakcje['$wynik1'] = $wynik1;
    $reakcje['$wynik2'] = $wynik2;
    $reakcje['$wynik3'] = $wynik3;

    $reakcje['$lp1odls'] = $lp1odls;
    $reakcje['$lp2odls'] = $lp2odls;
    $reakcje['$lp2'] = $lp2;



$json_data = json_encode($reakcje);
file_put_contents('reakcje.json', $json_data);

}
?>
