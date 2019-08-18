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
    echo "Non si può aggiungere un'area senza indicarne il mondo di provenienza.";
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
                    <select multiple name="closeAreas" id="closeAreas" class="custom-select">
                    <?php
                        $sql = "SELECT NomeArea, IDArea FROM aree WHERE Mondo = " . $_GET["WORLD"];
                        $result=mysqli_query($conn, $sql);
                        $first = true;
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $text = $first?"selected":"";
                            echo "<option value='" . $row["IDArea"] . "' " . $text . ">" . $row["NomeArea"] . "</option>";
                            $first=false;
                        }
                    ?>
                    </select>
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
        window.location = "planmenu.php";
    })
    $("#sendButton").click(function(e){
        e.preventDefault();
        var areaAds = $("#closeAreas").val();
        console.log(areaAds)
        if($("#areaName").val().length > 0 && $("#areaDesc").val().length > 0 && $("#classArea").val().length > 0)
        {
            $(this).attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "operationsAPI.php",
                data: {
                    operation: "addArea",
                    areaWorld: "<?php echo $_GET["WORLD"];?>",
                    areaName: $("#areaName").val(),
                    areaDesc: $("#areaDesc").val(),
                    areaType: $("#classArea").val(),
                    areaAdiacences: JSON.stringify(areaAds),
                    areaID: <?php
                        $sql = "SELECT MAX(IDArea) AS LastID FROM aree WHERE Mondo = " . $_GET["WORLD"];
                        $result=mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        if ($row["LastID"] === NULL) echo 0; else echo $row["LastID"] + 1; 
                    ?>
                }
            }).done(function(data){
                window.location = "planmenu.php";
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
}
?>