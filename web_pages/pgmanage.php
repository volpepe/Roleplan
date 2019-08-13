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
                    <label for="pgName">Nome Personaggio: </label> 
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="pgName" id="pgName">
                </div>
                <div class="form-group">
                    <label for="pgLev">Livello: </label> 
                </div>
                <div class="form-group">
                    <input type="number" min=1 class="form-control" name="pgLev" id="pgLev">
                </div>
                <div class="form-group">
                    <label for="pgCurPV">Punti Vita Attuali: </label> 
                </div>
                <div class="form-group">
                    <input type="number" min=-10 class="form-control" name="pgCurPV" id="pgCurPV">
                </div><div class="form-group">
                    <label for="pgMaxPV">Punti Vita Massimi: </label> 
                </div>
                <div class="form-group">
                    <input type="number"  min=-10 class="form-control" name="pgMaxPV" id="pgMaxPV">
                </div>
                <div class="form-group">
                    <label for="pgEXP">Punti Esperienza: </label> 
                </div>
                <div class="form-group">
                    <input type="number" min=0 class="form-control" name="pgEXP" id="pgEXP">
                </div>
                <div class="form-group">
                    <label for="pgPlayerName">Nome Giocatore: </label> 
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="pgPlayerName" id="pgPlayerName">
                </div>
                <div class="form-group">
                    <label for="areas">Area presso cui si trova: </label>
                    <select name="areas" id="areas" class="custom-select">
                    <?php
                        $sql = "SELECT NomeArea, IDArea FROM aree WHERE Mondo = " . $_GET["WORLD"];
                        $result = mysqli_query($conn, $sql);
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
                <div class="form-group">
                    <label for="races">Razza: </label>
                    <select name="races" id="races" class="custom-select">
                    <?php
                        $sql = "SELECT NomeRazza, IDRazza FROM razze";
                        $result = mysqli_query($conn, $sql);
                        $first = true;
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $text = $first?"selected":"";
                            echo "<option value='" . $row["IDRazza"] . "' " . $text . ">" . $row["NomeRazza"] . "</option>";
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
        window.location = "charmenu.php";
    })
    $("#sendButton").click(function(e){
        e.preventDefault();
        area = $("#areas").val()
        name=$("#pgName").val()
        lev=$("#pgLev").val()
        pvMax=$("#pgMaxPV").val()
        pvAtt=$("#pgCurPV").val()
        exp=$("#pgEXP").val()
        playerName=$("#pgPlayerName").val()
        race=$("#races").val()
        if(area && name && lev && pvMax && pvAtt && exp && playerName && race)
        {
            $(this).attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "operationsAPI.php",
                data: {
                    operation: "addPG",
                    charWorld: "<?php echo $_GET["WORLD"];?>",
                    charArea: area,
                    charName: name,
                    charLevel: lev,
                    charPVMax: pvMax,
                    charPVAtt: pvAtt,
                    charEXP: exp,
                    charPlayer: playerName,
                    charRace: race
                }
            }).done(function(data){
                window.location = "charmenu.php";
                alert("Operation completed succesfully! Data: " + data)
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