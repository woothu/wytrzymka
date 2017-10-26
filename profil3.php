<?php

//kiedy ktoś chce zapisać wyniki
if (isset($_POST['action'])){
    $id = $_SESSION["id"];
    $login = $_SESSION["nick"];
    $typ ='zginania';
    $time=time();
    $date = date("d/m/Y H:i:s");
//    $data = funkcja(time) może jeszcze typ przekroju
    $conn = mysqli_connect("localhost","root","","woothu") or die("Nie mozna polaczyc sie z baza danych:". mysqli_connect_error());
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
        </select>

        <select id="wybor_profilu">
          <option value="-1">Wybierz profil</option>
          <option value="0">Kołowy</option>
          <option value="1">Kołowy z otworem</option>
        </select>

        <select id="wybor_podpory">
          <option value="-1">Wybierz podpory</option>
          <option value="0">Stała i przesuwna</option>
          <option value="1">Utwierdzenie sztywne z lewej strony</option>
        </select>
        <br />
        <!--// pierwsze wyswietlanie-->
        <div id="wyswietlanie" style="display:none">

            <!--Przekroje -->
            <div id="przekroje">
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
            </div>

            <!--Podpory-->
            <div id="podpory">
                <div id="podpora0" style="display:none">
                    <P>Podpora stała i przesuwna</P>
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
            </div>

            <div id="elementy0"></div>
            <div id="przyciski_elementy">
                <input type="button" id="dodaj_sile" value="Dodaj siłę">
                <input type="button" id="dodaj_moment" value="Dodaj moment">
                <input type="button" id="dodaj_obciazenie" value="Dodaj obciazenie">
                <input type="button" id="licz_napr" value="Oblicz">
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
                <p id="wynik_mat"></p>
                <label for="polew">Pole powierzchni:<p id="polew"></p></label><br />
                <label for="momentw">Moment bezwładności względem osi x:<p  id="momentw"></p></label><br />
                <label for="wskaznikw">Wskaźnik wytrzymałości na zginanie względem osi x:<p  id="wskaznikw"></p></label>
            </div>
            <!--    //div do wyswietlania-->
            <div id="wyniki1"></div>
        </div>
        <!--wyświetlanie wykresow-->

        <div id="chartContainer"></div>

        <div id="chartContainer2"></div>
    </div>

    <!--sprawdzenie wartosci parametrow podczas obliczen-->
    <div id="zapisane_parametry" style="display:none">
        <input type="number" id="E">
        <input type="number" id="G">
        <input type="number" id="pole">
        <input type="number" id="wskaznik">
        <input type="number" id="pole">
        <input type="number" id="liczba_sil" value=1>
        <!--
    <input type="number" id="dlugosc">
    <input type="number" id="sila">
-->

    </div>



    <script>
        $(function() {
            //wybor przekroju
            $("#wybor_profilu").change(function() {
                var nr_wyboru = document.getElementById("wybor_profilu");
                opcja = nr_wyboru.options[nr_wyboru.selectedIndex].value;
                if (opcja >= 0) {
                    document.getElementById("przekroj" + opcja).style.display = "block";
                    for (i = 0; i < 2; i++) {
                        if (i != opcja) {
                            document.getElementById("przekroj" + i).style.display = "none";
                        }
                    }
                }
            });

            //wybor materialu
            $("#wybor_mat").change(function() {
                var material = ["Stal", "Aluminium"];
                var modulY = [200000, 69000];
                var modulG = [80000, 25900];
                //var lpoissona = [0.3, 0.33];
                var nr_wyboru2 = document.getElementById("wybor_mat");
                opcja2 = nr_wyboru2.options[nr_wyboru2.selectedIndex].value;
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

            //wybor podpory
            $("#wybor_podpory").change(function() {
                var nr_wyboru3 = document.getElementById("wybor_podpory");
                opcja3 = nr_wyboru3.options[nr_wyboru3.selectedIndex].value;
                if (opcja3 == 0) {
                    document.getElementById("podpora0").style.display = "block";
                    document.getElementById("podpora1").style.display = "none";
                }
                if (opcja3 == 1) {
                    document.getElementById("podpora1").style.display = "block";
                    document.getElementById("podpora0").style.display = "none";
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
                document.getElementById("elementy" + ii).innerHTML += '<div id="element' + ii + '"><p>Siła:</p><label for="eln' + ii + '">Siła [N]:</label><input type="number" id="eln' + ii + '"><br /><label for="el' + ii + '">Punkt oddziaływania siły [mm]:</label><input type="number" id="el' + ii + '"><br /><br /></div><div id="elementy' + jj + '"></div>';
                typ.push(2);
            });

            //dodaj moment skupiony
            $("#dodaj_moment").click(function() {
                ii++;
                jj = ii + 1;
                document.getElementById("elementy" + ii).innerHTML += '<div id="element ' + ii + '"><p>Moment skupiony:</p><label for="elm' + ii + '">Moment skupiony[Nm]:</label><input type="number" id="elm' + ii + '"><br /><label for="el' + ii + '">Punkt oddziaływania momentu skupionego [mm]:</label><input type="number" id="el' + ii + '"><br /><br /></div><div id="elementy' + jj + '"></div>';
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
                }
                document.getElementById("wskaznikw").innerHTML = Wx.toFixed(2) + ' mm<sup>3</sup>';
                document.getElementById("polew").innerHTML = pole.toFixed(2) + ' mm<sup>2</sup>';
                document.getElementById("momentw").innerHTML = moment.toFixed(2) + ' mm<sup>4</sup>';
                document.getElementById("wskaznik").value = Wx;
                document.getElementById("pole").value = pole;
            });

            $("#dlugosc").keyup(function() {
                dlugosc = Number(document.getElementById("dlugosc").value);
            });

            //Oblicznie  wynikow
            $("#licz_napr").click(function() {
                if (dlugosc > 0 && opcja && opcja2 && opcja3 && pole > 0) {
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
                            var y = (Number(moment[i]))
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
                        //wykresy
                    }

                    //dla utwierdzenia stalego
                    if (opcja3 == 1) {
                        //Obliczenie sił reakcji
                        var Ma = 0;
                        var sila_utw = 0;
                        for (i = 0; i < sila.length; i++) {
                            var x = ((Number(sila[i])) * (Number(punkt[i])) / 1000);
                            var y = (Number(moment[i]))
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
                            lp++;
                            //sprawdzenie dla kazdej sily czy jest w przedziale
                            for (i = 0; i < sila.length; i++) {
                                if (punkt[i] < minimum) {
                                    t += Number(sila[i]) + (Number(obciazenie[i]) / 1000 * (lp - Number(punkt[i])))
                                }
                                if (punkt[i] < minimum && k < 150) {
                                    m += Number(sila[i]) * (lp - Number(punkt[i])) / 1000 + (Number(moment[i])) + ((Number(obciazenie[i])) / 2000000 * (lp - Number(punkt[i])) * (lp - Number(punkt[i])))
                                }
                            }
                            // po tej pętli mamy punkt na wysokości l dla tn i mg. dalej push punktów do tablic wykresu
                            tn.push({
                                y: t
                            });
                            mg.push({
                                y: m
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
                    //renderowanie wykresow i reset zmiennych
                    chart.render();
                    chart2.render();
                    $("html, body").animate({
                        scrollTop: $('#screen').offset().top
                    }, 1000);
                    var naprmax = (Math.max.apply(null, mg2.map(Math.abs))) * 1000 / Wx;
                    document.getElementById('wyniki1').innerHTML += '<h4>Maksymalne naprężenia to: ' + naprmax.toFixed(3) + '[MPa]</h4>'
                } else {
                    window.alert("Źle wypełnione pola z danymi!");
                }
            });
        });

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
                        height: clientHeight
                    });
                }, 501)
            } else {
                window.alert("Już zapisałeś wyniki!");
            }
        });

    </script>
