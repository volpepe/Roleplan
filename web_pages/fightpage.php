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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>RolePlan: Fight Page</title>
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
            <h3>Personaggi in combattimento</h3>
            <div style="width:50%; float: left">
                <h5>PG</h5>
                <?php
                for ($i=1; $i<8; $i++) { 
                ?>
                    <div class="form-group">
                        <label for="pg-<?php echo $i; ?>">PG <?php echo $i; ?>: </label>
                        <select style='width:50%; display:inline; margin-right:5%' name="pg-<?php echo $i; ?>" id="pg-<?php echo $i; ?>" class="custom-select pg-select">
                        <option selected value="-1">Nessun PG</option>
                        <?php
                            $sql = "SELECT IDPersonaggio, NomePersonaggio, Livello, PuntiVitaAtt, PuntiVitaMax FROM personaggi_giocanti WHERE MondoPresenza =" . $_GET["WORLD"] . " AND AreaPresenza = " . $_GET["AREA"];
                            $result=mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                echo "<option value='" . $row["IDPersonaggio"] . "' pg-name='". $row["NomePersonaggio"]. "'>" . $row["NomePersonaggio"] . ", Liv. " . $row["Livello"] . ", " . $row["PuntiVitaAtt"] . "/" . $row["PuntiVitaMax"] . " PV </option>";
                            }
                        ?>
                        </select>
                    </div>
                <?php
                }
                ?>
            </div>
            <div style="width:50%; display: inline-block">
                <h5>NPC</h5>
                <?php
                for ($i=1; $i<8; $i++) { 
                ?>
                    <div class="form-group">
                        <label for="npc-<?php echo $i; ?>">  NPC <?php echo $i; ?>: </label>
                        <select style='width:50%; display:inline; margin-right:5%' name="npc-<?php echo $i; ?>" id="npc-<?php echo $i; ?>" class="custom-select npc-select">
                        <option selected value="-1">Nessun NPC</option>
                        <?php
                            $sql = "SELECT n.IDNPC, n.Nome, n.Livello, n.PuntiVitaAtt, n.PuntiVitaMax, r.NomeRazza FROM npc n, razze r WHERE r.IDRazza = n.Razza AND MondoPresenza =" . $_GET["WORLD"] . " AND AreaPresenza = " . $_GET["AREA"];
                            $result=mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                if (is_null($row["Nome"])) $row["Nome"] = $row["NomeRazza"];
                                echo "<option value='" . $row["IDNPC"] . "' npc-name='". $row["Nome"]. "'>" . $row["Nome"] . ", Liv. " . $row["Livello"] . ", " . $row["PuntiVitaAtt"] . "/" . $row["PuntiVitaMax"] . " PV </option>";
                            }
                        ?>
                        </select>
                    </div>
                <?php
                }
                ?>
            </div>
            <div id="results">
            </div>
            <div id="errors"></div>
            <button class="btn btn-primary dec" id="sendButton">Conferma</button>
            <button class="btn btn-danger dec" id="removeButton">Annulla</button>
        </form>  
    </div>
</body>

</html>

<script>
$(document).ready(function(){
    $("#removeButton").click(function(e){
        e.preventDefault();
        window.location = "gamepage.php";
    })
    $(".pg-select").change(function(){
        var idpg = $(this).val()
        var idselect = $(this).attr("id").substring(3, 4)
        if (idpg >= 0) {
            var name = $("#pg-" + idselect + " option:selected").attr("pg-name")
            $("#results").append("<div class='form-group ch' id='pgdiv" + idselect + "'><label for='endhppg" + idpg + "'>Punti vita finali di " + name + ": </label><div class='form-group'>    <input type='number' class='form-control' name='endhppg" + idpg +"' id='endhppg" + idpg +"'></div></div>")
        } else {
            $("#pgdiv" + idselect).remove()
        }
    })
    $(".npc-select").change(function(){
        var idnpc = $(this).val()
        var idselect = $(this).attr("id").substring(4, 5)
        if (idnpc >= 0) {
            var name = $("#npc-" + idselect + " option:selected").attr("npc-name")
            $("#results").append("<div class='form-group ch' id='npcdiv" + idselect + "'><label for='endhpnpc" + idnpc + "'>Punti vita finali di " + name + ": </label><div class='form-group'>    <input type='number' class='form-control' name='endhpnpc" + idnpc +"' id='endhpnpc" + idnpc +"'></div></div>")
        } else {
            $("#npcdiv" + idselect).remove()
        }
    })
    $("#sendButton").click(function(e){
        e.preventDefault();
        $(this).attr("disabled", true);
        $("#results div.ch").each(function(){
            if($(this).attr("id").substring(0, 2) == "pg"){
                //pg
                hpVal = $(this).find("input").val()
                id = $(this).find("input").attr("id").substring(7)
                if(hpVal) {
                    console.log("hp: " + hpVal + " id: " + id)
                    $.ajax({
                        type: "POST",
                        url: "operationsAPI.php",
                        data: {
                            operation: "updateHP",
                            typechar: "pg",
                            idchar: id,
                            newHP: hpVal
                        }
                    })
                } else {
                    $("#errors").html("<p style='color: red'>Ci sono aree obbligatorie da riempire</p>")
                }
            } else {
                //npc
                hpVal = $(this).find("input").val()
                id = $(this).find("input").attr("id").substring(8)
                if(hpVal) {
                    console.log("hp: " + hpVal + " id: " + id)
                    $.ajax({
                        type: "POST",
                        url: "operationsAPI.php",
                        data: {
                            operation: "updateHP",
                            typechar: "npc",
                            idchar: id,
                            newHP: hpVal
                        }
                    })
                } else {
                    $("#errors").html("<p style='color: red'>Ci sono aree obbligatorie da riempire</p>")
                }                
            }
        })
    })
})
</script>

<?php
$conn->close();
?>