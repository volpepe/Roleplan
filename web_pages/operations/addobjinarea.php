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

if(!isset($_GET["WORLD"]) || !isset($_GET["AREA"])){
    echo "Non si può aggiungere un oggetto senza indicare il mondo o l'area in cui deve essere aggiunto.";
} else {
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
    <link rel="stylesheet" href="../styles/nostyle.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>RolePlan: Objects Adding Page</title>
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
                <label for="addobj">Tipo di Oggetto da Inserire: </label>
                <select name="addobj" id="addobj" class="custom-select form-control">
                <?php
                    $sql = "SELECT Nome, IDTipoOgg FROM tipi_oggetto";
                    $result=mysqli_query($conn, $sql);
                    $first = true;
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        $text = $first?"selected":"";
                        echo "<option value='" . $row["IDTipoOgg"] . "' " . $text . ">" . $row["Nome"] . "</option>";
                        $first=false;
                    }
                ?>
                </select>
                <button class="btn btn-primary dec" id="sendButton">Conferma</button>
                <button class="btn btn-danger dec" id="removeButton">Indietro</button>
                <table class="table-striped" style="width:100%">
                    <tr class="header">
                        <th style="width:80%">Nome Oggetto</th>
                        <th></th>
                    </tr>
                <?php
                //first initialization of the table
                $sql = "SELECT t.Nome, o.IDOggetto
                        FROM tipi_oggetto t, oggetti o 
                        WHERE o.MondoPresenza = " . $_GET["WORLD"] . "
                        AND o.AreaPresenza = " . $_GET["AREA"] . "
                        AND o.TipoOggetto = t.IDTipoOgg";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()){
                    echo "<tr id=" . $row["IDOggetto"] . ">
                            <td class='first'>" . $row["Nome"] . "</td>
                            <td><button class='nostyle deleteobj'>Elimina Oggetto</button></td>
                        </tr>";
                }
                ?>
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
        window.location = "../menus/worldmenu.php";
    })
    $("#sendButton").click(function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "../API/operationsAPI.php",
            data: {
                operation: "addItemInArea",
                objType: $("#addobj").val(),
                world: <?php echo $_GET["WORLD"]; ?>,
                area: <?php echo $_GET["AREA"]; ?>
            }
        }).done(function(data){
            $("table tr.header").after("<tr id=" + data + "><td class='first'>" + $("#addobj :selected").text() + "</td><td><button class='nostyle deleteobj'>Elimina Oggetto</button></td></tr>")
        })
    })
    $("table ").on("click", "button", function(e){
        e.preventDefault();
        id = $(this).parent().parent().attr("id")
        $.ajax({
            type: "POST",
            url: "../API/operationsAPI.php",
            data: {
                operation: "removeItemFromArea",
                id: id
            }                
        }).done(function(){
            $("#" + id).remove()
        })
    })
})
</script>

<?php
$conn->close();
}
?>