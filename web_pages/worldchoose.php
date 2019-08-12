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

if (!isset($_GET["callback"])){
    echo "Questa pagina va chiamata da un'altra pagina";
}
else {
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
                <h3>Scegli il mondo su cui operare</h3>
            <?php
                $sql = "SELECT NomeMondo, IDMondo FROM mondi";
                $result=mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result))
                {
            ?>
                <input type="button" class="btn btn-primary dec worldchoose" id=<?php echo $row["IDMondo"];?> value='<?php echo $row["NomeMondo"];?>'/>
            <?php
                }
            ?>
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
    $("input").click(function(){
        window.location = '<?php echo $_GET["callback"]; ?>' + "?WORLD=" + $(this).attr("id");
    })
})
</script>

<?php
}
?>