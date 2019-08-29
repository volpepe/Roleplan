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

if(!isset($_GET["WORLD"])){
    echo "Non si possono cercare gli ingredienti senza indicare il mondo in cui devono essere cercati.";
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
    <title>RolePlan: Ingredients Finding Page</title>
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
                <label for="recipe">Ricetta da utilizzare nella ricerca: </label>
                <select name="recipe" id="recipe" class="custom-select form-control">
                <?php
                    $sql = "SELECT r.IDRicetta, o.Nome FROM ricette r JOIN tipi_oggetto o ON r.OggettoCreato = o.IDTipoOgg";
                    $result=mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        echo "<option value='" . $row["IDRicetta"] . "' > Ricetta per " . $row["Nome"] . "</option>";
                    }
                ?>
                </select>
                <button class="btn btn-primary dec" id="sendButton">Conferma</button>
                <button class="btn btn-danger dec" id="removeButton">Indietro</button>
                <table class="table-striped" style="width:100%">
                <thead>
                    <tr class="header">
                        <th style="width:50%">Area</th>
                        <th>Pianta</th>
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
    $("#removeButton").click(function(e){
        e.preventDefault();
        window.location = "userpage.php?WORLD=<?php echo $_GET["WORLD"];?>";
    })
    $("#sendButton").click(function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "operationsAPI.php",
            data: {
                operation: "searchRecipePlant",
                recipe: $("#recipe").val(),
                world: <?php echo $_GET["WORLD"]; ?>,
            }
        }).done(function(plants){
            console.log(plants)
            plants = JSON.parse(plants)
            $("table tbody").empty()
            console.log(plants)
            for (var i = 0; i < plants.length; i++){
                $("table tbody").append("<tr><td>" + plants[i]["NomeArea"] + "</td><td>" + plants[i]["NomePianta"] + "</td></tr>")
            }
        })
    })
})
</script>

<?php
$conn->close();
}
?>