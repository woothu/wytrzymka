<?php
//kiedy ktoś chce zapisać wyniki
if (isset($_POST['action'])){
    $id = $_SESSION["id"];
    $login = $_SESSION["nick"];
    $typ ='skręcanie';
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
    <label for="dlugosc">Długość pręta [mm]:</label>
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
<!--          <option value="2">Prostokątny</option>-->
<!--          <option value="3">Własny profil</option>-->
        </select>
     <a style='display:none' id='link' href="https://skyciv.com/free-moment-of-inertia-calculator/">Kalkulator momentów bezwładności profilu</a>

    <!--// pierwsze wyswietlanie-->
    <div id="wyswietlanie" style="display:none">

        <!--Przekroje -->
        <div id="przekroje">
            <div id="wlasny_mat" style="display:none">
                <label for="wlasny_E">Moduł G [MPa]:</label>
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
                <label for="wlasny_pole">Pole powierzchni [mm2]:</label>
                <input type="number" name="wlasny_pole" class="pole" id="wlasny_pole" size=20px>
                <br />
                <label for="emax">E-max [mm]:</label>
                <input type="number" name="emax" class="pole" id="emax" size=20px>
                <br />
                <label for="wlasny_moment">Moment bezwładności [mm3] :</label>
                <input type="number" name="wlasny_moment" class="pole" id="wlasny_moment" size=20px>
            </div>
        </div>

        <div id="elementy0"></div>
        <div id="przyciski_elementy">
            <input type="button" id="dodaj_moment" value="Dodaj moment">
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
<!--            <label for="momentw">Moment bezwładności względem osi x:<p  id="momentw"></p></label><br />-->
            <label for="wskaznikw">Wskaźnik wytrzymałości na skręcanie x:<p  id="wskaznikw"></p></label>
        </div>
        <!--    //div do wyswietlania-->
        <div id="wyniki1"></div>
    </div>
    <canvas id="pret" style="margin:15px; width:100%"></canvas>

    <!--wyświetlanie wykresow-->


        <div id="chartContainer" style="margin:10px" ></div>

        <div id="chartContainer2" style="margin:10px"></div>
        <div id="chartContainer3" style="margin:10px"></div>

</div>
<br />

<!--sprawdzenie wartosci parametrow podczas obliczen-->
<div id="zapisane_parametry" style="display:none">
    <input type="number" id="E">
    <input type="number" id="G">
    <input type="number" id="pole">
    <input type="number" id="wskaznik">
    <input type="number" id="moment_bez">
    <input type="number" id="liczba_sil" value=1>
</div>

<script>
    $(function() {

        // rysowanie preta
        canvas = document.getElementById('pret');
            var context = canvas.getContext('2d');
            //ile jest pixeli w canvasie
            canvas.width = $('#pret').width();
            canvas.height = canvas.width / 8;
            //definiowanie belki
           var belka = new Image();
            belka.dx = canvas.width * 0.05;
            belka.dy = canvas.height * 0.05;
            belka.width = canvas.width * 0.80;
            belka.height = canvas.height * 0.90;
            // definiowanie obiektow
            var utwierdzenie = new Image();
            var silad = new Image();
            var momentd = new Image();
            var silam = new Image();
            var momentm = new Image();
            momentm.src = 'png/moment_skrecm.png';
            silam.src = 'png/silam.png';
            momentd.src = 'png/moment_skrecd.png';
            silad.src = 'png/sila.png';
            utwierdzenie.src = 'png/utwierdzenie.png';
            belka.src = 'png/pret.png';
            $("#parametry").on('keyup change mouseover', function() {
                //czyszczenie plotna i rysowanie belki
                context.clearRect(0, 0, canvas.width, canvas.height);
                //rysowanie preta

                context.drawImage(belka, belka.dx, belka.dy, belka.width, belka.height);
                    utwierdzenie.dx = canvas.width * 0.011;
                    //                                  window.alert(utwierdzenie.dx);
                    utwierdzenie.dy = 0;
                    utwierdzenie.width = canvas.width * 0.04;
                    utwierdzenie.height = canvas.height;
                    context.drawImage(utwierdzenie, utwierdzenie.dx, utwierdzenie.dy, utwierdzenie.width, utwierdzenie.height);

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
                            silam.dx = canvas.width * 0.05 - (canvas.height * 0.05 / 2) + belka.width * (odleglosc / dlugosc);
                            silam.dy = canvas.height * 0.52;
                            silam.width = canvas.height * 0.1;
                            silam.height = canvas.height * 0.51;
                            context.drawImage(silam, silam.dx, silam.dy, silam.width, silam.height);

                        }
                    } else if (typ[q] == 3) {
                        var wmoment = Number(document.getElementById("elm" + q).value);
                        if (wmoment > 0) {
                            momentd.dx = belka.width * (odleglosc / dlugosc) + canvas.height * 0.17;
                            momentd.dy = canvas.height*0.15 ;
                            momentd.width = canvas.height * 0.4;
                            momentd.height = canvas.height*0.7 ;
                            context.drawImage(momentd, momentd.dx, momentd.dy, momentd.width, momentd.height);
                        } else if (wmoment < 0) {
                            momentm.dx = belka.width * (odleglosc / dlugosc) + canvas.height * 0.17;
                            momentm.dy = canvas.height*0.15;
                            momentm.width = canvas.height * 0.4;
                            momentm.height = canvas.height*0.7;
                            context.drawImage(momentm, momentm.dx, momentm.dy, momentm.width, momentm.height);
                        }
                    }
                }
            });

        //wybor przekroju
                   $("#wybor_profilu").change(function() {
                var nr_wyboru = document.getElementById("wybor_profilu");
                opcja = nr_wyboru.options[nr_wyboru.selectedIndex].value;
                if (opcja >= 0) {
                    if (opcja == 3) {
                        document.getElementById("link").style.display = 'block';
                    } else {
                        document.getElementById("link").style.display = "none";
                    }
                    document.getElementById("przekroj" + opcja).style.display = "block";
                    for (i = 0; i < 4; i++) {
                        if (i != opcja) {
                            document.getElementById("przekroj" + i).style.display = "none";
                        }
                    }
                }
                document.getElementById("wyswietlanie").style.display = "block";
            });



        //wybor materialu Ustala
        $("#wybor_mat").change(function() {
            var nr_wyboru2 = document.getElementById("wybor_mat");
            opcja2 = nr_wyboru2.options[nr_wyboru2.selectedIndex].value;
            if (opcja2 > 2) {
                G = 0;
            } else {
                G = Number(document.getElementById("wlasny_E").value);
                document.getElementById("wynik_mat").innerHTML = "Moduł sprężystości poprzecznej Kirchoffa materiału wynosi [GPa]: " + G + "<br />";
                document.getElementById("G").value = G;
            }

            var material = ["Stal", "Aluminium"];
            var wlasny_E = 0;
            var modulY = [200000, 69000];
            var modulG = [80000, 25900];
            if (opcja2 == 2) {
                document.getElementById("wlasny_mat").style.display = "block";
            } else {
                document.getElementById("wlasny_mat").style.display = "none";
            }

            for (i = 0; i < material.length; i++) {


                if (opcja2 == i) {
                    document.getElementById("wynik_mat").innerHTML = "Materiał to: " + material[i] + "<br />" + "Moduł sprężystości poprzecznej Kirchoffa materiału wynosi [GPa]: " + modulG[i] + "<br />";
                    document.getElementById("E").value = modulY[i];
                    document.getElementById("G").value = modulG[i];
                    E = modulY[i];
                    G = modulG[i];

                }
            }

        });

        $("#wlasny_E").keyup(function() {
            G = Number(document.getElementById("wlasny_E").value);
            document.getElementById("wynik_mat").innerHTML = "Moduł sprężystości poprzecznej Kirchoffa materiału wynosi [GPa] " + G + "<br />";
            document.getElementById("G").value = G;
        });

        //dodaj sile
        var ii = -1;
        var typ = [];
        //dodaj moment skupiony
        $("#dodaj_moment").click(function() {
            ii++;
            jj = ii + 1;
            document.getElementById("elementy" + ii).innerHTML += '<div id="element ' + ii + '><p>Moment skupiony:</p><label for="elm' + ii + '">Moment skupiony[Nm]:</label><input type="number" id="elm' + ii + '"><br /><label for="el' + ii + '">Punkt oddziaływania momentu skupionego [mm]:</label><input type="number" id="el' + ii + '"><br /><br /></div><div id="elementy' + jj + '"></div>';
            typ.push(3);
        });

        //dodaj moment skupiony

        //dzialania po wprowadzeniu danych
        $(".pole").keyup(function() {
            //obliczenie wspolczynnikow w profilach
            if (opcja == 0) {
                var D = Number(document.getElementById("srednica2").value);
                pole = D * D / 4 * Math.PI;
                moment = Math.PI * D * D * D * D / 32;
                Wx = moment / (D / 2);
            } else if (opcja == 1) {
                var D = Number(document.getElementById("srednica3").value);
                var d = Number(document.getElementById("wew_srednica3").value);
                pole = D * D / 4 * Math.PI - d * d * 0.25 * Math.PI;
                moment = (Math.PI * ((D * D * D * D) - (d * d * d * d))) / 32;
                Wx = moment / (D / 2);
            } else if (opcja == 2) {
                var a = Number(document.getElementById("dlugosc_a").value);
                var b = Number(document.getElementById("dlugosc_b").value);
                pole = a * b;
                moment = (a * a * a * b) / 12;
                Wx = moment / (a / 2);
            } else if (opcja == 3) {
                var emax = Number(document.getElementById("emax").value);
                pole = Number(document.getElementById("wlasny_pole").value);;
                moment = Number(document.getElementById("wlasny_moment").value);
                Wx = moment / emax;
            }
            document.getElementById("wskaznikw").innerHTML = Wx.toFixed(2) + ' mm<sup>3</sup>';
//            document.getElementById("momentw").innerHTML = moment.toFixed(2) + ' mm<sup>4</sup>';
            document.getElementById("wskaznik").value = Wx;
            document.getElementById("moment_bez").value = moment;
        });

        $("#dlugosc").keyup(function() {
            dlugosc = Number(document.getElementById("dlugosc").value);
        });

        //Oblicznie  wynikow
        $("#licz_napr").click(function() {

            if (dlugosc > 0 && opcja && opcja2 && Wx > 0) {

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
                var moment = [];
                var ug1 = 0;
                var m_bezwl = Number(document.getElementById("moment_bez").value)

                var naprezenia = [];
                var mg = [];
                var ugiecie = [];
                var naprezenia2 = [];
                document.getElementById("wyniki1").innerHTML = '';

                //push punktów do tabeli
                for (q = 0; q < typ.length; q++) {
                    punkt.push(document.getElementById("el" + q).value);
                    moment.push(document.getElementById("elm" + q).value);
                }
                //push punktu przedzialu do konca belki
                punkt.push(dlugosc);
                moment.push(0);

                // reakcja podpory to Ma
                var Ma = 0;
                for (i = 0; i < moment.length; i++) {
                    var y = (Number(moment[i]))
                    Ma -= y;
                }

                document.getElementById("wyniki1").innerHTML +=
                    '<h4>Reakcja utwierdzenia wynosi: ' + Ma + ' [Nm]</h4>';

                //podpora stała
                punkt.push(0);
                moment.push(Ma);

                // po obliczeniu reakcji podporowych mamy tablicę z układem momentów powalającym na obliczenie momentow
                var lpunktow = [];
                var k = 0;
                var lp = -1;
                var lpunktow_suma = 0;
                var punkt1 = punkt.slice();

                //kiedy nie są usunięte wszystkie przedzialy w punkt1, definiowanie punktow
                for (j = 0; j < moment.length; j++) {
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
                        m = 0;
                        n = 0;
                        lp++;
                        //sprawdzenie dla kazdej sily czy jest w przedziale
                        for (i = 0; i < moment.length; i++) {
                            if (punkt[i] < minimum) {
                                var ramie = lp - Number(punkt[i]);
                                var ramiem = ramie / 1000;

                                m -= (Number(moment[i]));
                                n -= (Number(moment[i]))*1000 / Wx;
                            }
                        }
                        ug1 += m * 0.001 / (G*1000000 * (m_bezwl/1000000000000));

                        // po tej pętli mamy punkt na wysokości l dla tn i mg. dalej push punktów do tablic wykresu

                        mg.push({
                            y: m
                        });
                        ugiecie.push({
                            y: ug1
                        });
                        naprezenia.push({
                            y: n
                        });
                        naprezenia2.push(n);
                    }
                }

                //wyswietlanie wykresow
                var chart = new CanvasJS.Chart("chartContainer", {
                    title: {
                        text: "Wykres wartości naprężeń"
                    },
                    data: [{
                        type: "line",
                        dataPoints: naprezenia,
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
                        title: "Naprążenia [MPa]",
                        interlacedColor: "#F4F4F4",
                    },
                });
                var chart2 = new CanvasJS.Chart("chartContainer2", {
                    title: {
                        text: "Wykres momentu skręcającego"
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
                        title: "Wartość momentu [Nm]",
                        interlacedColor: "#F4F4F4",
                    },
                });

                var chart3 = new CanvasJS.Chart("chartContainer3", {
                    title: {
                        text: "Kąt skręcenia"
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
                        title: "Skręcenie [rad]",
                        interlacedColor: "#F4F4F4",
                    },
                });
                //renderowanie wykresow i reset zmiennych
                chart.render();
                chart2.render();
                chart3.render();
                $("html, body").animate({
                    scrollTop: $('#screen').offset().top
                }, 1000);
                var naprmax = (Math.max.apply(null, naprezenia2.map(Math.abs)));
                document.getElementById('wyniki1').innerHTML += '<h4>Maksymalne naprężenia to: ' + naprmax.toFixed(3) + '[MPa]</h4>'
            } else {
                window.alert("Źle wypełnione pola z danymi!");
            }
        });
    });

</script>
<script src="2canvas/html2canvas.js"></script>
    <script>
        $('#screen1').on('click', function() {
            if (document.getElementById("screen1").style.borderColor != "red") {
                // wylaczenie buttona do nastepnych obliczen:
                    document.getElementById("screen1").style.borderColor = "red";
                //do gory strony zeby nie obcinalo screenow
                $("html, body").animate({
                    scrollTop: 0
                }, 500);

                //opoznione robienie screena(po animacji do gory strony):
                setTimeout(function() {


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
