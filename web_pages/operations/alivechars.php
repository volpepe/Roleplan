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
    <link rel="stylesheet" href="../styles/choicestyle.css">
    <link rel="stylesheet" href="../styles/topbar.css">
    <link rel="stylesheet" href="../styles/formpage.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>RolePlan: Alive Chars Page</title>
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
                <div id="results">
                    <h3>Personaggi vivi in questa area:</h3>
                    <table id="chars" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Personaggio</th>
                            <th>Razza</th>
                            <th>Tipo</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="errors"></div>
            </div>
        </form>  
    </div>
</body>

</html>

<script>
$(document).ready(function(){
    $.ajax({
        type: "POST",
        url: "../API/operationsAPI.php",
        data: {
            operation: "aliveChars",
            world: <?php echo $_GET["WORLD"]; ?>,
            area: <?php echo $_GET["AREA"]; ?>
        }
    }).done(function(chars){
        chars = JSON.parse(chars)
        for (var i in chars){
            var txt = "<tr>"
            for(var k in chars[i]){
                if (!chars[i][k]) chars[i][k] = "Nessun Nome";
                txt += "<td>" + chars[i][k] + "</td>"
            }
            txt += "</tr>"
            $("#chars tbody").append(txt);
        }
    })
})
</script>

<?php
$conn->close();
?>