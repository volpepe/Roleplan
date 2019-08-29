<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "roleplan";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection to database failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/choicestyle.css">
    <link rel="stylesheet" href="styles/topbar.css">
    <link rel="stylesheet" href="styles/formpage.css">
    <link rel="stylesheet" href="styles/gamepage.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>RolePlan: Game Mode Page</title>
</head>

<body>
    <div class="container-fluid">
        <div class="jumbotron jumbotron-fluid">
            <div class="container-fluid">
                <a id="back-button" href="index.php"><< <span>Torna al menu principale</span></a>
                <p>RolePlan</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button id="fight-button" class="shadow-lg align-middle rounded"><i class="fas fa-fist-raised"></i> Battaglia</button>
            </div>
            <div class="col">
                <button id="quest-button" class="shadow-lg align-middle rounded"><i class="fas fa-book"></i> Quest</button>
            </div>
            <div class="col">
                <button id="inventory-button" class="shadow-lg align-middle rounded"><i class="fas fa-object-group"></i> Inventari</button>
            </div>
            <div class="col">
                <button id="area-button" class="shadow-lg align-middle rounded"><i class="fas fa-globe-europe"></i> Cambio Area di Gioco</button>
            </div>
        </div>
        <div class="row">
            <h3 style="width:100%; text-align:center; margin-top: 40px">Sposta personaggi in quest'area:</h3>
            <select name="chars" id="chars" multiple class="custom-select" style="width:40%; text-align:center; margin-left: 30%">
            <?php
                $sql = "SELECT IDPersonaggio, NomePersonaggio FROM personaggi_giocanti WHERE (MondoPresenza <> ".$_GET["WORLD"].") OR (MondoPresenza = ".$_GET["WORLD"]." AND AreaPresenza <> ".$_GET["AREA"].")";
                $result=mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result))
                {
                    echo "<option value='" . $row["IDPersonaggio"] . "'>" . $row["NomePersonaggio"] . "</option>";
                }
            ?>
            </select>
            <button class="btn btn-primary dec" class="nostyle" id="confirmchars" style="width: 10%; height: 10%; margin-left: 5%">Conferma</button>
        </div>
    </div>
</body>

</html>

<script>
$(document).ready(function(){
    $("#area-button").click(function(){
        window.location = "worldchoose.php?callback=areachoose.php&final_callback=gamepage.php"
    })
    $("#inventory-button").click(function(){
        window.location = "inventorymenu.php?WORLD=<?php echo $_GET["WORLD"];?>&AREA=<?php echo $_GET["AREA"];?>"
    })
    $("#quest-button").click(function(){
        window.location = "gamequestmenu.php?WORLD=<?php echo $_GET["WORLD"];?>&AREA=<?php echo $_GET["AREA"];?>"
    })
    $("#fight-button").click(function(){
        window.location = "fightpage.php?WORLD=<?php echo $_GET["WORLD"];?>&AREA=<?php echo $_GET["AREA"];?>"
    })
    $("#confirmchars").click(function(){
        var charsToMove = $("#chars").val()
        for (var i in charsToMove){
            $.ajax({
            type: "POST",
            url: "operationsAPI.php",
            data: {
                operation: "changePGLocation",
                idpg: charsToMove[i],
                world:<?php echo $_GET["WORLD"];?>,
                area:<?php echo $_GET["AREA"];?>
            }
            }).done(function(){
                location.reload()
            })
        }
    })
})
</script>