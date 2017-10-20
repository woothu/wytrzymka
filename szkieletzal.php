<style>
    /*
    html {
        background-color: #b9abab;
    }
*/
    #chartContainer,#chartContainer2{
    height:300px;



    }
    .glowne {
        margin: 10px;
        padding: 10px;
        height: auto;
        border-color: #e6d8d8;
        border-width: 2px;
        border-radius: 17px;
        background-color: #FAFAFB;
        border-style: solid;

    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        -moz-appearance: textfield;
        margin: 0;
        /* <-- Apparently some margin are still there even though it's hidden */
    }

    #naglowek {
        margin: 10px;
    }

    li {
        font-family: "serif", sans-serif, cursive, fantasy, inherit;
    }

    #custom-bootstrap-menu.navbar-default .navbar-brand {
        color: rgba(119, 119, 119, 1);
    }

    #custom-bootstrap-menu.navbar-default {
        font-size: 19px;
        background-color: rgba(237, 242, 242, 1);
        border-width: 2px;
        border-radius: 17px;
    }

    #custom-bootstrap-menu.navbar-default .navbar-nav>li>a {
        color: rgba(110, 106, 106, 1);
        background-color: rgba(248, 248, 248, 0);
    }

    #custom-bootstrap-menu.navbar-default .navbar-nav>li>a:hover,
    #custom-bootstrap-menu.navbar-default .navbar-nav>li>a:focus {
        color: rgba(51, 51, 51, 1);
        background-color: rgba(230, 216, 216, 1);
    }

    #custom-bootstrap-menu.navbar-default .navbar-nav>.active>a,
    #custom-bootstrap-menu.navbar-default .navbar-nav>.active>a:hover,
    #custom-bootstrap-menu.navbar-default .navbar-nav>.active>a:focus {
        color: rgba(0, 0, 0, 1);
        background-color: rgba(230, 216, 216, 1);
        background: -webkit-linear-gradient(top, rgba(207, 206, 214, 1) 0%, rgba(230, 216, 216, 1) 100%);
        background: linear-gradient(to bottom, rgba(207, 206, 214, 1) 0%, rgba(230, 216, 216, 1) 100%);
    }

    #custom-bootstrap-menu.navbar-default .navbar-toggle {
        border-color: #e6d8d8;

    }

    #custom-bootstrap-menu.navbar-default .navbar-toggle:hover,
    #custom-bootstrap-menu.navbar-default .navbar-toggle:focus {
        background-color: #e6d8d8;
    }

    #custom-bootstrap-menu.navbar-default .navbar-toggle .icon-bar {
        background-color: #e6d8d8;
    }

    #custom-bootstrap-menu.navbar-default .navbar-toggle:hover .icon-bar,
    #custom-bootstrap-menu.navbar-default .navbar-toggle:focus .icon-bar {
        background-color: #edf2f2;
    }

</style>
<div id="naglowek">
    <div id="custom-bootstrap-menu" class="navbar navbar-default " role="navigation">
        <div class="container-fluid">
            <div class="navbar-header"><a class="navbar-brand" href="#">Cześć!</a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-menubuilder"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
            </div>
            <div class="collapse navbar-collapse navbar-menubuilder">
                <ul class="nav navbar-nav navbar-left">
                    <li id="naglowek1"><a href="index.php">Strona startowa</a></li>
                    <li id="naglowek2"><a href="rozciaganie.php">Rozciąganie</a></li>
                    <li id="naglowek3"><a href="skrecanie.php">Skręcanie</a></li>
                    <li id="naglowek4"><a href="zginanie.php">Zginanie</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="wyloguj.php"><span class="glyphicon glyphicon-log-out"></span> Wyloguj</a></li>
                    <li id="konto"><a href="konto.php"><span class="glyphicon glyphicon-user"></span>Moje konto</a></li>
                </ul>

            </div>
        </div>
    </div>
</div>
