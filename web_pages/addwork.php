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
                    <label for="area">Area di lavoro: </label> 
                </div>
                <div class="form-group">
                    <select name="area" id="area" class="custom-select">
                    <?php
                        $sql = "SELECT IDArea, NomeArea FROM aree WHERE Mondo = " . $_GET["WORLD"];
                        $first = true;
                        $result=mysqli_query($conn, $sql);
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
                    <label for="obj">NPC Lavoratore: </label> 
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
                    <label for="work">Lavoro da Assegnare: </label> 
                </div>
                <div class="form-group">
                    <select name="work" id="work" class="custom-select">
                    <?php
                        $sql = "SELECT IDLavoro, NomeLavoro FROM lavori";
                        $first = true;
                        $result=mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $text = $first?"selected":"";
                            echo "<option value='" . $row["IDLavoro"] . "' " . $text . ">" . $row["NomeLavoro"] . "</option>";
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
        window.location = "npcmenu.php";
    })
    $("#sendButton").click(function(e){
        e.preventDefault();
        npc = $("#NPC").val()
        area = $("#area").val()
        work = $("#work").val()
        if(npc && work && area)
        {
            $(this).attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "operationsAPI.php",
                data: {
                    operation: "addOccupation",
                    npc: npc,
                    world: <?php echo $_GET["WORLD"]; ?>,
                    work: work,
                    area: area
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