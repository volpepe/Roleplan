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

if(!isset($_GET["WORLD"]) || !isset($_GET["AREA"])){
    echo "Non si può aggiungere un oggetto senza indicare il mondo o l'area in cui deve essere aggiunto.";
} else {
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
    <link rel="stylesheet" href="styles/nostyle.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>RolePlan: Inventory Page</title>
</head>
<body>
    <div class="container-fluid">
        <div class="jumbotron jumbotron-fluid">
            <div class="container-fluid">
                <a id="back-button" href="index.php"><< <span>Torna al menu principale</span></a>
                <p>RolePlan</p>
            </div>
        </div>
        <form>
            <div class="container">
                <label for="char">Personaggio a cui cambiare inventario: </label>
                <select style="margin-bottom: 20px" name="char" id="char" class="custom-select form-control">
                <?php
                    $sql = "SELECT pg.IDPersonaggio AS ID, pg.NomePersonaggio AS Nome, 'pg' AS Tipo, r.NomeRazza FROM personaggi_giocanti pg, razze r WHERE pg.AreaPresenza = ". $_GET["AREA"] ." AND pg.MondoPresenza = ". $_GET["WORLD"] ." AND r.IDRazza = pg.Razza
                            UNION 
                            SELECT n.IDNPC AS ID, n.Nome AS Nome, 'npc' AS Tipo, r.NomeRazza FROM npc n, razze r WHERE n.AreaPresenza = ". $_GET["AREA"] ." AND n.MondoPresenza = ". $_GET["WORLD"] . " AND r.IDRazza = n.Razza";
                    $result=mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        if (is_null($row["Nome"])) $row["Nome"] = $row["NomeRazza"];
                        echo "<option value='" . $row["ID"] . "' name='". $row["Nome"]. "' type='" . $row["Tipo"] . "'>" . $row["Nome"] . "</option>";
                    }
                ?>
                </select>
                <label for="addobj">Tipo di Oggetto da Inserire: </label>
                <select name="addobj" id="addobj" class="custom-select form-control">
                <?php
                    $sql = "SELECT Nome, IDTipoOgg FROM tipi_oggetto";
                    $result=mysqli_query($conn, $sql);
                    $first = true;
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $text = $first?"selected":"";
                        echo "<option value='" . $row["IDTipoOgg"] . "' " . $text . ">" . $row["Nome"] . "</option>";
                        $first=false;
                    }
                ?>
                </select>
                <label for="quant">Quantità: </label>
                <input min=1 value=1 type="number" name="quant" id="quant" class="form-control">
                <button class="btn btn-primary dec" id="sendButton">Conferma</button>
                <button class="btn btn-danger dec" id="removeButton">Annulla</button>
                <h3>Inventario di <span id="nomechar"></span></h3>
                <table class="table-striped" style="width:100%">
                    <thead>
                        <tr class="header">
                            <th style="width:60%">Nome Oggetto</th>
                            <th>Quantità</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</body>

</html>

<script>
$(document).ready(function(){

    //initialization:
    $("#nomechar").html($("#char option:selected").attr("name"))

    $("#removeButton").click(function(e){
        e.preventDefault();
        window.location = "gamepage.php?WORLD=<?php echo $_GET["WORLD"];?>&AREA=<?php echo $_GET["AREA"];?>";
    })

    $("#char").change(function(){
        $("#nomechar").html($("#char option:selected").attr("name"))
        $.ajax({
            type: "POST",
            url: "operationsAPI.php",
            data: {
                operation: "getInv",
                charid: $("#char").val(),
                type: $("#char option:selected").attr("type")
            }
        }).done(function(inv){
            buildInventory(inv)
        })
    })

    $("#sendButton").click(function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "operationsAPI.php",
            data: {
                operation: "addObjInInventory",
                objtype: $("#addobj").val(),
                charid: $("#char").val(),
                quant: $("#quant").val(),
                type: $("#char option:selected").attr("type")
            }
        }).done(function(inv){
            console.log(inv)
            buildInventory(inv)
        })
    })

    $("table tbody .deleteobj").on("click", "button", function(e){
        e.preventDefault()
        id = $(this).parent().parent().attr("id")
        console.log(id)
        var objtype = (this).parent().parent().attr("id-obj")
        var type = $("#char option:selected").attr("type")
        var charid = $("#char").val()
        var quant = 1
        $.ajax({
            type: "POST",
            url: "operationsAPI.php",
            data: {
                operation: "removeObjFromInventory",
                objtype: objtype,
                type: type,
                charid: charid,
                quant: quant
            }                
        }).done(function(inv){
            buildInventory(inv)
        })
    })

    //inventory initialization
    $.ajax({
            type: "POST",
            url: "operationsAPI.php",
            data: {
                operation: "getInv",
                charid: $("#char").val(),
                type: $("#char option:selected").attr("type")
            }
    }).done(function(inv){
        buildInventory(inv)
    })

    function buildInventory(inv){
        var inv = JSON.parse(inv)
        console.log(inv)
        counter = 0
        $("table tbody").empty()
        for(var i in inv){
            var txt = "<tr id=" + counter++ + " id-obj=" + inv[i]["IDOggetto"] + "><td class='first'>" + inv[i]["NomeOggetto"] + "</td><td>" + inv[i]["Quantita"] + "</td><td><button class='nostyle deleteobj'>Elimina 1 unità</button></td></tr>"
            $("table tbody").append(txt);
        }
    }
})
</script>

<?php
$conn->close();
}
?>