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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>RolePlan: Area Adding Page</title>
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
                    <label for="areaName">Nome Area: </label> 
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="areaName" id="areaName">
                </div>
                <label for="areaDesc">Descrizione Area: </label> 
                <div class="form-group">
                    <textarea name="areaDesc" class="form-control" id="areaDesc" cols="60" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="classArea">Tipo di Area: </label>
                    <select name="classArea" id="classArea" class="custom-select">
                    <?php
                        $sql = "SELECT NomeTipo, IDTipoArea FROM tipi_aree";
                        $result=mysqli_query($conn, $sql);
                        $first = true;
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $text = $first?"selected":"";
                            echo "<option value='" . $row["IDTipoArea"] . "' " . $text . ">" . $row["NomeTipo"] . "</option>";
                            $first=false;
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="closeAreas">Adiacenze (premi ctrl, shift o trascina col mouse per selezionare più Aree): </label>
                    <select multiple name="closeAreas" id="closeArea" class="custom-select">
                        <option selected></option>
                        <option value=""></option>
                    </select>
                </div>
                <button class="btn btn-primary dec" id="sendButton">Conferma</button>
                <button class="btn btn-danger dec" id="removeButton">Annulla</button>
            </div>
        </form>  
    </div>
</body>
<?php
$conn->close();
?>
</html>

<script>
$(document).ready(function(){

})
</script>