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
                <div class="form-group">
                    <label for="giver">NPC che consegna la quest: </label>
                    <select name="giver" id="giver" class="custom-select">
                    <option selected value="-1">Nessuno</option>
                    <?php
                        $sql = "SELECT IDNPC, Nome FROM npc WHERE MondoPresenza = " . $_GET["WORLD"];
                        $result=mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            echo "<option value='" . $row["IDNPC"] . "'>" . $row["Nome"] . "</option>";
                        }
                    ?>
                    </select>
                </div>

                <?php
                for ($i=1; $i<=5; $i++) { 
                ?>
                    <div class="form-group">
                        <label for="item-<?php echo $i; ?>">Ricompensa <?php echo $i; ?>: </label>
                        <select style='width:30%; display:inline; margin-right:5%' name="item-<?php echo $i; ?>" id="item-<?php echo $i; ?>" class="custom-select">
                        <option selected value="-1">Nessun Oggetto</option>
                        <?php
                            $sql = "SELECT IDTipoOgg, Nome FROM tipi_oggetto";
                            $result=mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                echo "<option value='" . $row["IDTipoOgg"] . "'>" . $row["Nome"] . "</option>";
                            }
                        ?>
                        </select>
                        <label for="qt-<?php echo $i; ?>">Q.ta: </label>
                        <input id="qt-<?php echo $i; ?>" name="qt-<?php echo $i; ?>" style="width:8%; display:inline" type='number' min=0>
                    </div>
                <?php
                }
                ?>
                <div class="form-group">
                    <label for="classQuest">Tipo di Quest: </label>
                    <select name="classQuest" id="classQuest" class="custom-select">
                        <option value="viaggio">viaggio</option>
                        <option value="eliminazione">eliminazione</option>
                        <option value="colloquio">colloquio</option>
                        <option value="raccolto">raccolto</option>
                    </select>
                </div>

                <!-- viaggio -->
                <div class="form-group" id="travelDiv">
                    <label for="areas">Area presso cui arrivare: </label>
                    <select name="areas" id="areas" class="custom-select">
                    <?php
                        $sql = "SELECT NomeArea, IDArea FROM aree WHERE Mondo = " . $_GET["WORLD"];
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            echo "<option value='" . $row["IDArea"] . "' " . $text . ">" . $row["NomeArea"] . "</option>";
                        }
                    ?>
                    </select>
                </div>

                <!-- colloquio -->
                <div class="form-group" id="talkDiv">
                    <label for="talk">NPC con cui parlare: </label>
                    <select name="talk" id="talk" class="custom-select">
                    <?php
                        $sql = "SELECT IDNPC, Nome FROM npc WHERE MondoPresenza = " . $_GET["WORLD"];
                        $result=mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result))
                        {
                            echo "<option value='" . $row["IDNPC"] . "'>" . $row["Nome"] . "</option>";
                        }
                    ?>
                    </select>
                </div>

                <!-- raccolta -->
                <div id="getDiv">
                <?php
                for ($i=1; $i<=5; $i++) { 
                ?>
                    <div class="form-group">
                        <label for="itemGet-<?php echo $i; ?>">Oggetto da raccogliere <?php echo $i; ?>: </label>
                        <select style='width:30%; display:inline; margin-right:5%' name="itemGet-<?php echo $i; ?>" id="itemGet-<?php echo $i; ?>" class="custom-select">
                        <option selected value="-1">Nessun Oggetto</option>
                        <?php
                            $sql = "SELECT IDTipoOgg, Nome FROM tipi_oggetto";
                            $result=mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                echo "<option value='" . $row["IDTipoOgg"] . "'>" . $row["Nome"] . "</option>";
                            }
                        ?>
                        </select>
                        <label for="qtGet-<?php echo $i; ?>">Q.ta: </label>
                        <input id="qtGet-<?php echo $i; ?>" name="qtGet-<?php echo $i; ?>" style="width:8%; display:inline" type='number' min=0>
                    </div>
                <?php
                }
                ?>
                </div>

                <!-- eliminazione -->
                <div id="killDiv">
                <?php
                for ($i=1; $i<=3; $i++) { 
                ?>
                    <div class="form-group">
                        <label for="npckill-<?php echo $i; ?>">NPC da eliminare <?php echo $i; ?>: </label>
                        <select style='width:50%; display:inline; margin-right:5%' name="npckill-<?php echo $i; ?>" id="npckill-<?php echo $i; ?>" class="custom-select">
                        <option selected value="-1">Nessun NPC</option>
                        <?php
                            $sql = "SELECT IDNPC, Nome FROM npc WHERE MondoPresenza = " . $_GET["WORLD"];
                            $result=mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                echo "<option value='" . $row["IDNPC"] . "'>" . $row["Nome"] . "</option>";
                            }
                        ?>
                        </select>
                    </div>
                <?php
                }
                ?>
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

    $('#talkDiv').hide();
    $('#killDiv').hide();
    $('#getDiv').hide();

    $("#classQuest").change(function(){
        $('#travelDiv').hide();
        $('#talkDiv').hide();
        $('#killDiv').hide();
        $('#getDiv').hide();

        var value = $(this).val()
        if (value == "viaggio") {
            $('#travelDiv').show();
        }
        if (value == "raccolto") {
            $('#getDiv').show();
        }
        if (value == "eliminazione") {
            $('#killDiv').show();
        }
        if (value == "colloquio") {
            $('#talkDiv').show();
        }
    })

    $("#sendButton").click(function(e){
        e.preventDefault();
        arc = $("#arc").val()
        exp = $("#exp").val()
        name = $("#name").val()
        mLev = $("#mLev").val()
        desc = $("#desc").val()
        classQuest = $("#classQuest").val()
        giver = $("#giver").val() > -1 ?  $("#giver").val() : "null"
        //colloquio
        talk = $("#talk").val() ? $("#talk").val() : "null" 
        //ricompense
        it1 = $("#item-1").val()
        it2 = $("#item-2").val()
        it3 = $("#item-3").val()
        it4 = $("#item-4").val()
        it5 = $("#item-5").val()
        qt1 = $("#qt-1").val()
        qt2 = $("#qt-2").val()
        qt3 = $("#qt-3").val()
        qt4 = $("#qt-4").val()
        qt5 = $("#qt-5").val()
        //eliminazioni
        el1 = $("#npckill-1").val()
        el2 = $("#npckill-2").val()
        el3 = $("#npckill-3").val()
        //raccolta
        itg1 = $("#itemGet-1").val()
        itg2 = $("#itemGet-2").val()
        itg3 = $("#itemGet-3").val()
        itg4 = $("#itemGet-4").val()
        itg5 = $("#itemGet-5").val()
        qtg1 = $("#qtGet-1").val()
        qtg2 = $("#qtGet-2").val()
        qtg3 = $("#qtGet-3").val()
        qtg4 = $("#qtGet-4").val()
        qtg5 = $("#qtGet-5").val()
        //normal
        disp = $("#disp").is(":checked")
        areaToGetTo = classQuest == "viaggio" ? $("#areas").val() : "null"
        worldToGetTo = classQuest == "viaggio" ? <?php echo $_GET["WORLD"];?> : "null"
        //check for duplicates

        duplicates = false
        arIt = [it1, it2, it3, it4, it5]
        arEl = [el1, el2, el3]
        arItg = [itg1, itg2, itg3, itg4, itg5]
        for (i = 0; i < 5; i++){
            valIt = arIt[i]; valItg = arItg[i]
            if (valIt > -1) {
                for (j = i + 1; j < 5; j++){
                    if (valIt == arIt[j]) duplicates = true;
                }
            }
            if (valItg > -1) {
                for (j = i + 1; j < 5; j++){
                    if (valItg == arItg[j]) duplicates = true;
                }
            }
        }
        for (i = 0; i < 3; i++) {
            valEl = arEl[i];
            if (valEl > -1) {
                for (j = i + 1; j < 5; j++){
                    if (valEl == arEl[j]) duplicates = true;
                }
            }
        }

        if(     arc &&
                exp > 0 && 
                name.length > 0 && 
                mLev > 0 &&
                desc.length > 0 &&
                (   
                    (classQuest == "viaggio" && areaToGetTo) || 
                    (classQuest == "colloquio" && talk) ||
                    (classQuest == 'eliminazione' || classQuest == 'raccolto')
                )
                && !duplicates
                //no checks for elimination and gathering missions
            ) 
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
                    npcToTalk: talk,
                    giverNPC: giver,
                    worldToGetTo: worldToGetTo,
                    areaToGetTo: areaToGetTo,
                    item1: it1,
                    item2:it2,
                    item3: it3,
                    item4:it4,
                    item5: it5,
                    quant1:qt1,
                    quant2: qt2,
                    quant3:qt3,
                    quant4: qt4,
                    quant5:qt5,
                    npcToKill1: el1,
                    npcToKill2: el2,
                    npcToKill3: el3,
                    objToGet1: itg1,
                    quantToGet1: qtg1,
                    objToGet2: itg2,
                    quantToGet2: qtg2,
                    objToGet3: itg3,
                    quantToGet3: qtg3,
                    objToGet4: itg4,
                    quantToGet4: qtg4,
                    objToGet5: itg5,
                    quantToGet5: qtg5
                }
            }).done(function(data){
                window.location = "questmenu.php";
                alert("Operation completed succesfully! Data: " + data)
            })
        } else if (duplicates){
            $("#errors").html("<p style='color: red'>Ci sono valori duplicati</p>")
        } 
        else {
            $("#errors").html("<p style='color: red'>Ci sono aree obbligatorie da riempire</p>")
        }
    })
})
//todo: check with npcs
</script>

<?php
$conn->close();
?>