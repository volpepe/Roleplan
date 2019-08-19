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
                    <label for="arc">Arco: </label>
                    <select name="arc" id="arc" class="custom-select">
                    <?php
                        $sql = "SELECT NomeArco, IDArco FROM archi";
                        $result = mysqli_query($conn, $sql);
                        $first = true;
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            $text = $first?"selected":"";
                            echo "<option value='" . $row["IDArco"] . "' " . $text . ">" . $row["NomeArco"] . "</option>";
                            $first=false;
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Nome Quest: </label> 
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name">
                </div>
                <div class="form-group">
                    <label for="exp">Punti Esperienza garantiti: </label> 
                </div>
                <div class="form-group">
                    <input type="number" min=1 class="form-control" name="exp" id="exp">
                </div>
                <div class="form-group">
                    <label for="mLev">Livello minimo consigliato: </label> 
                </div>
                <div class="form-group">
                    <input type="number" min=1 class="form-control" name="mLev" id="mLev">
                </div>
                <div class="form-group">
                    <label for="desc">Descrizione Quest: </label> 
                </div>
                <div class="form-group">
                    <textarea name="desc" class="form-control" id="desc" cols="60" rows="5"></textarea>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="disp" name="example1">
                    <label class="custom-control-label" for="disp">Già disponibile?</label>
                </div>
                <<div class="form-group">
                    <label for="classQuest">Tipo di Quest: </label>
                    <select name="classQuest" id="classQuest" class="custom-select">
                        <option value="viaggio">viaggio</option>
                        <option value="eliminazione">eliminazione</option>
                        <option value="colloquio">colloquio</option>
                        <option value="raccolto">raccolto</option>
                    </select>
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
        arc = $("#arc").val()
        exp = $("#exp").val()
        name = $("#name").val()
        mLev = $("#mLev").val()
        desc = $("#desc").val()
        classQuest = $("#classQuest").val()
        disp = $("#disp").is(":checked")
        if(true)
        {
            $(this).attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "operationsAPI.php",
                data: {
                    operation: "addQuest",
                    arc: arc,
                    exp: exp,
                    questName: name,
                    minLev: mLev,
                    questDesc: desc,
                    disp: disp,
                    questType: classQuest,
                    npcToTalk: ,
                    giverNPC: ,
                    worldToGetTo: <?php echo $_GET["WORLD"];?>,
                    areaToGetTo: ,
                    item1: ,
                    quant1: ,
                    item2: ,
                    quant2: ,
                    item3: ,
                    quant3: ,
                    item4: ,
                    quant4: ,
                    item5: ,
                    quant5: ,
                    npcToKill1: ,
                    npcToKill2: ,
                    npcToKill3: ,
                    objToGet1: ,
                    quantToGet1: ,
                    objToGet2: ,
                    quantToGet2: ,
                    objToGet3: ,
                    quantToGet3: ,
                    objToGet4: ,
                    quantToGet4: ,
                    objToGet5: ,
                    quantToGet5: ,
                }
            }).done(function(data){
                window.location = "questmenu.php";
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