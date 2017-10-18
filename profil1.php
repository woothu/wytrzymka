<div class="glowne" id="przekroj">
    <div id="wybor_materialu">
        <h2>Wprowadzanie danych do obliczeń:</h2>
        <p>Wybór materiału</p>
        <select id="wybor_mat">
          <option value="-1">Wybierz materiał</option>
          <option value="0">Stal</option>
          <option value="1">Aluminium</option>
                </select>
        <button type="button" value="Wybierz" id="wyswietl_mat">Wybierz</button>
    </div>
    <p id="wynik_mat"></p>
    <div id="wybor_profilu" style="display:none">
        <p>Wybór profilu</p>
        <select id="wybor">
          <option value="-1">Wybierz profil</option>
          <option value="0">Kołowy</option>
          <option value="1">Kołowy z otworem</option>
                </select>
        <button type="button" value="Wybierz" id="wyswietl">Wybierz</button>
    </div>
    <div id="przekroj0" style="display:none">
        <label for="srednica2">Średnica [mm]:</label>
        <input type="number" name="srednica2" id="srednica2" size=20px>
        <br />
        <input type="button" id="licz_parametry2" value="Oblicz">
        <br />
        <label for="wynik21">Pole powierzchni:<p id="wynik21"></p></label><br />
        <!--
        <label for="wynik22">Moment bezwładności względem osi x:<p  id="wynik22"></p></label><br />
        <label for="wynik23">Wskaźnik wytrzymałości na zginanie względem osi x:<p  id="wynik23"></p></label>
-->
    </div>
    <div id="przekroj1" style="display:none">
        <label for="srednica3">Średnica [mm]:</label>
        <input type="number" name="srednica3" id="srednica3" size=20px>
        <br />
        <label for="wew_srednica3">Wewnętrzna średnica [mm]:</label>
        <input type="number" name="wew_srednica3" id="wew_srednica3" size=20px>
        <input type="button" id="licz_parametry3" value="Oblicz">
        <br />
        <label for="wynik31">Pole powierzchni:<p id="wynik31"></p></label><br />
        <!--
        <label for="wynik32">Moment bezwładności względem osi x:<p  id="wynik32"></p></label><br />
        <label for="wynik33">Wskaźnik wytrzymałości na zginanie względem osi x:<p  id="wynik33"></p></label>
-->

    </div>

    <div id="dane1" style="display:none">
        <label for="dlugosc">Długość profilu [mm]:</label>
        <input type="number" name="dlugosc" id="dlugosc" size=20px>
        <br />
        <label for="sila1">Siła oddziaływania nr 1 [N]:</label>
        <input type="number" name="sila1" id="sila1" size=20px>
        <br />
        <label for="sila1mm">Punkt oddziaływania siły nr1 [mm]:</label>
        <input type="number" name="sila1mm" id="sila1mm" size=20px>
        <br />

        <div id="dane2" style="display:none">
            <label for="sila2">Siła oddziaływania nr 2 [N]:</label>
            <input type="number" name="sila2" id="sila2" size=20px>
            <br />
            <label for="sila2mm">Punkt oddziaływania siły nr2 [mm]:</label>
            <input type="number" name="sila2mm" id="sila2mm" size=20px>
        </div>

        <div id="dane3" style="display:none">
            <label for="sila3">Siła oddziaływania nr 3 [N]:</label>
            <input type="number" name="sila3" id="sila3" size=20px>
            <br />
            <label for="sila3mm">Punkt oddziaływania siły nr3 [mm]:</label>
            <input type="number" name="sila3mm" id="sila3mm" size=20px>
        </div>

        <input type="button" id="dodaj_sile" value="Dodaj Siłę">
        <input type="button" id="licz_napr" value="Oblicz" onclick="ScrollToBottom()">
        <br />
    </div>


    <div class="glowne" id="drukuj" style="display:none">
        <p>Naprężenie w Pa wynosi:</p>
        <p id="napr"></p>
        <p>Wydłużenie w mm wynosi:</p>
        <p id="wydl"></p>
    </div>

    <!--wyświetlanie wykresu-->
    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
    <div id="chartContainer2" style="height: 300px; width: 100%;"></div>

    <div id="zapisane_parametry" style="display:none">
        <input type="number" id="E">
        <input type="number" id="G">
        <input type="number" id="pole">
        <input type="number" id="liczba_sil" value=1>
        <!--
    <input type="number" id="dlugosc">
    <input type="number" id="sila">
-->

    </div>

    <script>
        document.getElementById("licz_parametry2").onclick = function() {
            var D = Number(document.getElementById("srednica2").value);
            var pole = D * D / 4 * Math.PI;
            document.getElementById("wynik21").innerHTML = pole.toFixed(2) + ' mm<sup>2</sup>';
            document.getElementById("pole").value = pole;
            var moment = Math.PI * D * D * D * D / 64;
            var wskaznik = moment / (D / 2);
            document.getElementById("dane1").style.display = "block";
            //            document.getElementById("wynik22").innerHTML = moment.toFixed(2) + ' mm<sup>4</sup>';
            //            document.getElementById("wynik23").innerHTML = wskaznik.toFixed(2) + ' mm<sup>3</sup>';


        };

        document.getElementById("licz_parametry3").onclick = function() {
            var D = Number(document.getElementById("srednica3").value);
            var d = Number(document.getElementById("wew_srednica3").value);
            var pole = D * D / 4 * Math.PI - d * d * 0.25 * Math.PI;
            var moment = (Math.PI * ((D * D * D * D) - (d * d * d * d))) / 64;
            var wskaznik = moment / (D / 2);
            document.getElementById("wynik31").innerHTML = pole.toFixed(2) + ' mm<sup>2</sup>';
            document.getElementById("pole").value = pole;
            document.getElementById("dane1").style.display = "block";
            //             document.getElementById("wynik32").innerHTML = moment.toFixed(2) + ' mm<sup>4</sup>';
            //                document.getElementById("wynik33").innerHTML = wskaznik.toFixed(2) + ' mm<sup>3</sup>';

        };

        document.getElementById("wyswietl").onclick = function() {
            var nr_wyboru = document.getElementById("wybor");
            var opcja = nr_wyboru.options[nr_wyboru.selectedIndex].value;
            document.getElementById("przekroj" + opcja).style.display = "inline-block";
            for (i = 0; i < 2; i++) {
                if (i != opcja) {
                    document.getElementById("przekroj" + i).style.display = "none";
                }
            }
        }

        document.getElementById("wyswietl_mat").onclick = function() {
            var material = ["Stal", "Aluminium"];
            var modulY = [200000, 69000];
            var modulG = [80000, 25900];
            var lpoissona = [0.3, 0.33];
            var nr_wyboru = document.getElementById("wybor_mat");
            var opcja = nr_wyboru.options[nr_wyboru.selectedIndex].value;
            for (i = 0; i < material.length; i++) {
                if (opcja == i) {
                    document.getElementById("wynik_mat").innerHTML = "Materiał to: " + material[i] + "<br />" + "Moduł sprężystości podłużnej Younga wynosi [MPa]: " + modulY[i] + "<br />" + "Moduł sprężystości poprzecznej Kirchoffa [MPa]: " + modulG[i];
                    document.getElementById("E").value = modulY[i];
                    document.getElementById("G").value = modulG[i];
                }
            }
            if (opcja > -1) {
                document.getElementById("wybor_profilu").style.display = "block";
            }
        }

        document.getElementById("dodaj_sile").onclick = function() {
            document.getElementById("dane2").style.display = "block";
            document.getElementById("liczba_sil").value += 1;
            document.getElementById("dodaj_sile").onclick = function() {
                document.getElementById("dane3").style.display = "block";
                document.getElementById("liczba_sil").value += 1;

            }
        }
var max=[];
        document.getElementById("licz_napr").onclick = function() {
            var sila1 = Number(document.getElementById("sila1").value);
            var punkt1 = Number(document.getElementById("sila1mm").value);
            var dlugosc = Number(document.getElementById("dlugosc").value);
            var E = Number(document.getElementById("E").value);
            var pole = Number(document.getElementById("pole").value);
            //Jedna siła
            if (document.getElementById("liczba_sil").value == 1) {
                var napr = Math.abs(((sila1 / pole) * 1000000).toFixed());
                var wydl1 = ((sila1 * punkt1) / (E * pole) * 1000).toFixed(2);
                document.getElementById("napr").innerHTML = napr;
                document.getElementById("wydl").innerHTML = wydl1;
                document.getElementById("drukuj").style.display = "block";
                var dataPoints = [];
                for (var i = 0; i < document.getElementById("dlugosc").value; i++) {
                    if (document.getElementById("sila1mm").value >= i) {
                        var p = ((sila1 * i) / (E * pole) * 1000);
                    } else {
                        //toFixed nie chce działać...
                        var p = ((sila1 * punkt1) / (E * pole) * 1000);
                    }
                    dataPoints.push({
                        y: p
                    });
                }

                var dataPoints2 = [];
                for (var i = 0; i < document.getElementById("dlugosc").value; i++) {
                    if (document.getElementById("sila1mm").value > i) {
                        i = Number(i);
                        var p2 = (sila1 / pole);
                    } else {
                        var p2 = 0
                    }
                    dataPoints2.push({
                        y: p2
                    });
                }
            } else if (document.getElementById("liczba_sil").value == 11) {
                var sila2 = Number(document.getElementById("sila2").value);
                var R = sila1 + sila2;
                var punkt2 = Number(document.getElementById("sila2mm").value);
                var napr1 = Math.abs((R / pole) * 1000000);
                var napr2 = Math.abs(((R - sila1) / pole) * 1000000);
                if (napr1 > napr2) {
                    document.getElementById("napr").innerHTML = napr1.toFixed();
                } else {
                    document.getElementById("napr").innerHTML = napr2.toFixed();
                }

                var wydl1 = ((R * punkt1) / (E * pole) * 1000);
                var wydl2 = wydl1 + (((sila2 * (punkt2 - punkt1)) / (E * pole)) * 1000);

                document.getElementById("wydl").innerHTML = wydl2;
                document.getElementById("drukuj").style.display = "block";
                var dataPoints = [];
                for (var i = 0; i < document.getElementById("dlugosc").value; i++) {
                    if (document.getElementById("sila1mm").value > i) {
                        var p = ((R * i) / (E * pole) * 1000);
                        i = Number(i);
                    } else if (document.getElementById("sila2mm").value > i) {
                        i = Number(i);
                        var p = wydl1 + ((sila2 * (i - punkt1)) / (E * pole) * 1000);
                    } else {
                        var p = wydl2;
                    }
                    max.push(p);
                    dataPoints.push({
                        y: p
                    });

                }
                var dataPoints2 = [];
                for (var i = 0; i < document.getElementById("dlugosc").value; i++) {
                    if (document.getElementById("sila1mm").value > i) {
                        i = Number(i);
                        var p = (R / pole);
                    } else if (document.getElementById("sila2mm").value > i) {
                        var p = ((R - sila1) / pole);
                        i = Number(i);
                    } else {
                        var p = 0;
                    }
                    dataPoints2.push({
                        y: p
                    });
                }
            } else {
                var sila2 = Number(document.getElementById("sila2").value);
                var punkt2 = Number(document.getElementById("sila2mm").value);
                var punkt3 = Number(document.getElementById("sila3mm").value);
                var sila3 = Number(document.getElementById("sila3").value);
                var R = sila1 + sila2 + sila3;
                var napr1 = Math.abs((R / pole) * 1000000);
                var napr2 = Math.abs(((R - sila1) / pole) * 1000000);
                var napr3 = Math.abs(((R - sila1 - sila2) / pole) * 1000000);
                //                var napr3 = Math.abs(((sila3) / pole) * 1000000);
                if (napr1 > napr2 && napr1 > napr3) {
                    document.getElementById("napr").innerHTML = napr1.toFixed();
                } else if (napr2 > napr1 && napr2 > napr3) {
                    document.getElementById("napr").innerHTML = napr2.toFixed();
                } else {
                    document.getElementById("napr").innerHTML = napr3.toFixed();
                }

                var wydl1 = ((R * punkt1) / (E * pole) * 1000);
                var wydl2 = wydl1 + ((((sila2 + sila3) * (punkt2 - punkt1)) / (E * pole)) * 1000);
                var wydl3 = wydl2 + (((sila3 * (punkt3 - punkt2)) / (E * pole)) * 1000);

                document.getElementById("wydl").innerHTML = wydl3;
                document.getElementById("drukuj").style.display = "block";
                var dataPoints = [];
                for (var i = 0; i < document.getElementById("dlugosc").value; i++) {
                    if (document.getElementById("sila1mm").value > i) {
                        var p = ((R * i) / (E * pole) * 1000);
                    } else if (document.getElementById("sila2mm").value > i) {
                        i = Number(i);
                        var p = wydl1 + (((sila2 + sila3) * (i - punkt1)) / (E * pole) * 1000);
                    } else if (document.getElementById("sila3mm").value > i) {
                        i = Number(i);
                        var p = wydl2 + ((sila3 * (i - punkt2)) / (E * pole) * 1000);
                    } else {
                        var p = wydl3;
                    }
                    dataPoints.push({
                        y: p
                    });

                }

                var dataPoints2 = [];
                for (var i = 0; i < document.getElementById("dlugosc").value; i++) {
                    if (document.getElementById("sila1mm").value > i) {
                        i = Number(i);
                        var p = (R / pole);

                    } else if (document.getElementById("sila2mm").value > i) {
                        var p = ((R - sila1) / pole);
                        i = Number(i);
                    } else if (document.getElementById("sila3mm").value > i) {
                        var p = ((R - sila1 - sila2) / pole)
                        i = Number(i);
                    } else {
                        var p = 0;
                    }
                    dataPoints2.push({
                        y: p
                    });
                }

            }

            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "Wydłużenie"
                },
                data: [{
                    type: "line",
                    dataPoints: dataPoints,
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
                    title: "Wydłużenie [mm]",
                    interlacedColor: "#F4F4F4",
                },
            });

            chart.render();

            var chart2 = new CanvasJS.Chart("chartContainer2", {
                title: {
                    text: "Wykres naprężeń"
                },
                data: [{
                    dataPoints: dataPoints2,
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
                    title: "Naprężenia w [MPa]",
                    interlacedColor: "#F4F4F4",
                },
            });
            chart2.render();
            window.scrollTo(0, document.body.scrollHeight);
        }

    </script>
