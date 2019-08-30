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
    <link rel="stylesheet" href="../styles/choicestyle.css">
    <link rel="stylesheet" href="../styles/topbar.css">
    <link rel="stylesheet" href="../styles/formpage.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>RolePlan: Pickup Page</title>
</head>
<body>
    <div class="container-fluid">
        <div class="jumbotron jumbotron-fluid">
            <div class="container-fluid">
                <a id="back-button" href="../index.php"><< <span>Torna al menu principale</span></a>
                <p>RolePlan</p>
            </div>
        </div>
        <form>
            <div class="container">
                <div class="form-group">
                    <label for="pg">Personaggio: </label> 
                </div>
                <div class="form-group">
                    <select name="pg" id="pg" class="custom-select">
                    <?php
                        $sql = "SELECT IDPersonaggio, NomePersonaggio FROM personaggi_giocanti WHERE MondoPresenza = " . $_GET["WORLD"] . " AND AreaPresenza = " . $_GET["AREA"];
                        $first = true;
                        $result=mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $text = $first?"selected":"";
                            echo "<option value='" . $row["IDPersonaggio"] . "' " . $text . ">" . $row["NomePersonaggio"] . "</option>";
                            $first=false;
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="obj">Oggetto raccolto: </label> 
                </div>
                <div class="form-group">
                    <select name="obj" id="obj" class="custom-select">
                    <?php
                        $sql = "SELECT o.IDOggetto, t.IDTipoOgg, t.Nome FROM tipi_oggetto t JOIN oggetti o ON t.IDTipoOgg = o.TipoOggetto WHERE o.AreaPresenza = ".$_GET["AREA"]." AND o.MondoPresenza = ".$_GET["WORLD"];
                        $first = true;
                        $result=mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $text = $first?"selected":"";
                            echo "<option value='" . $row["IDOggetto"] . "' " . $text . " obj-type=".$row["IDTipoOgg"].">" . $row["Nome"] . "</option>";
                            $first=false;
                        }
                    ?>
                    </select>
                </div>
                <div id="errors"></div>
                <button class="btn btn-primary dec" id="sendButton">Conferma</button>
                <button class="btn btn-danger dec" id="removeButton">Indietro</button>
            </div>
        </form>  
    </div>
</body>

</html>

<script>
$(document).ready(function(){
    $("#removeButton").click(function(e){
        e.preventDefault();
        window.location = "../menus/gamepage.php?WORLD=<?php echo $_GET["WORLD"];?>&AREA=<?php echo $_GET["AREA"];?>";
    })
    $("#sendButton").click(function(e){
        e.preventDefault();
        $(this).attr("disabled", true);
        pg = $("#pg").val()
        obj = $("#obj").val()
        objtype = $("#obj option:selected").attr("obj-type")
        //remove object from area first
        $.ajax({
            type: "POST",
            url: "../API/operationsAPI.php",
            data: {
                operation: "removeItemFromArea",
                id: obj
            }
        })
        //then put it into pg's inventory
        $.ajax({
            type: "POST",
            url: "../API/operationsAPI.php",
            data: {
                operation: "addObjInInventory",
                objtype: objtype,
                charid: pg,
                quant: 1,
                type: 'pg'
            }
        })
        //then reload the page
        location.reload();
    })
})
</script>

<?php
$conn->close();
?>