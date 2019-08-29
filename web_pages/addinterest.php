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
    <title>RolePlan: Interest Page</title>
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
                <div class="form-group">
                    <label for="NPC">Selezione NPC: </label> 
                </div>
                <div class="form-group">
                    <select name="NPC" id="NPC" class="custom-select">
                    <?php
                        $sql = "SELECT IDNPC, Nome FROM npc WHERE MondoPresenza = " . $_GET["WORLD"];
                        $first = true;
                        $result=mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $text = $first?"selected":"";
                            echo "<option value='" . $row["IDNPC"] . "' " . $text . ">" . $row["Nome"] . "</option>";
                            $first=false;
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="obj">Selezione Oggetto: </label> 
                </div>
                <div class="form-group">
                    <select name="obj" id="obj" class="custom-select">
                    <?php
                        $sql = "SELECT IDTipoOgg, Nome FROM tipi_oggetto";
                        $result=mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            echo "<option value='" . $row["IDTipoOgg"] . "'>" . $row["Nome"] . "</option>";
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Prezzo di Acquisto: </label> 
                </div>
                <div class="form-group">
                    <input type="number" min=0 class="form-control" name="price" id="price">
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
        window.location = "npcmenu.php";
    })
    $("#sendButton").click(function(e){
        e.preventDefault();
        npc = $("#NPC").val()
        obj = $("#obj").val()
        price = $("#price").val()
        if(npc && obj && price >= 0)
        {
            $(this).attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "operationsAPI.php",
                data: {
                    operation: "addInterest",
                    NPC: npc,
                    obj: obj,
                    price: price
                }
            }).done(function(data){
                window.location = "npcmenu.php";
                alert("Operation completed succesfully! " + data)
            })
        } else {
            $("#errors").html("<p style='color: red'>Ci sono aree obbligatorie da riempire</p>")
        }
    })
})
</script>

<?php
$conn->close();
?>