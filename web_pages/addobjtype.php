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
    <title>RolePlan: PG Adding Page</title>
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
                    <label for="objName">Nome Oggetto: </label> 
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="objName" id="objName">
                </div>
                <div class="form-group">
                    <label for="weight">Peso: </label> 
                </div>
                <div class="form-group">
                    <input type="number" min="0" step=".01" value="0" class="form-control" name="weight" id="weight">
                </div>
                <label for="objDesc">Descrizione: </label> 
                <div class="form-group">
                    <textarea name="objDesc" class="form-control" id="objDesc" cols="60" rows="5"></textarea>
                </div>

                <div class="form-group">
                    <label for="classObj">Tipo di Oggetto: </label>
                    <select name="classObj" id="classObj" class="custom-select">
                        <option value="vestiario">vestiario</option>
                        <option value="valuta">valuta</option>
                        <option value="scritto">scritto</option>
                        <option value="cibo">cibo</option>
                        <option value="pozione">pozione</option>
                        <option value="arma">arma</option>
                        <option value="ingrediente" selected>ingrediente</option>
                    </select>
                </div>

                <div id="defenseDiv">
                    <div class="form-group">
                        <label for="protection">Protezione: </label> 
                    </div>
                    <div class="form-group">
                        <input type="number" min="0" value="0" class="form-control" name="protection" id="protection">
                    </div>
                </div>
                <div id="valueDiv">
                    <div class="form-group">
                        <label for="value">Valore: </label> 
                    </div>
                    <div class="form-group">
                        <input type="number" min="0" value="0" class="form-control" name="value" id="value">
                    </div>
                </div>
                <div id="damageDiv">
                    <div class="form-group">
                        <label for="damage">Danno: </label> 
                    </div>
                    <div class="form-group">
                        <input type="number" min="0" value="0" class="form-control" name="damage" id="damage">
                    </div>
                </div>

                <div id="errors"></div>
                <button class="btn btn-primary dec" id="sendButton">Conferma</button>
                <button class="btn btn-danger dec" id="removeButton">Annulla</button>
            </div>
        </form>  
    </div>
</body>

</html>

<script>
$(document).ready(function(){
    $("#removeButton").click(function(e){
        e.preventDefault();
        window.location = "charmenu.php";
    })

    $('#defenseDiv').hide();
    $('#valueDiv').hide();
    $('#damageDiv').hide();

    $("#classObj").change(function(){

        $('#defenseDiv').hide();
        $('#valueDiv').hide();
        $('#damageDiv').hide();

        var value = $(this).val()
        //value is the text of the option
        if (value == "vestiario") {
            $('#defenseDiv').show();
        }
        if (value == "valuta") {
            $('#valueDiv').show();
        }
        if (value == "arma") {
            $('#damageDiv').show();
        }
    })

    $("#sendButton").click(function(e){
        e.preventDefault();
        //todo: finish this function and its api operation
    })
})
</script>

<?php
$conn->close();
?>