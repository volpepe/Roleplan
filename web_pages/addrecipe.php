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
    <title>RolePlan: Recipe Adding Page</title>
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
                    <label for="createObj">Oggetto da creare: </label>
                    <select name="createObj" id="createObj" class="custom-select">
                    <?php
                        $sql = "SELECT IDTipoOgg, Nome FROM tipi_oggetto WHERE CategoriaOggetto = 'cibo' OR CategoriaOggetto = 'pozione'";
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
                </div>
                <?php
                for ($i=0; $i<4; $i++) { 
                ?>
                    <div class="form-group">
                        <label for="ingredient-<?php echo $i; ?>">Ingrediente <?php echo $i; ?>: </label>
                        <select style='width:50%; display:inline; margin-right:5%' name="ingredient-<?php echo $i; ?>" id="ingredient-<?php echo $i; ?>" class="custom-select">
                        <option selected value="-1">Nessun Ingrediente</option>
                        <?php
                            $sql = "SELECT IDTipoOgg, Nome FROM tipi_oggetto WHERE CategoriaOggetto = 'ingrediente'";
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
                <div id="errors"></div>
                <button class="btn btn-primary dec" id="sendButton">Conferma</button>
                <button class="btn btn-danger dec" id="removeButton">Indietro</button>
            </div>
        </form>  
    </div>
</body>

</html>

<script>
$(document).ready(function(){
    $("#removeButton").click(function(e){
        e.preventDefault();
        window.location = "objmenu.php";
    })
    $("#sendButton").click(function(e){
        e.preventDefault();
        //there must be an object to create and at least one ingredient
        toCreate= $("#createObj").val()
        ing1= $("#ingredient-0").val()
        q1= $("#qt-0").val()
        ing2= $("#ingredient-1").val()
        q2= $("#qt-1").val()
        ing3=$("#ingredient-2").val()
        q3= $("#qt-2").val()
        ing4= $("#ingredient-3").val()
        q4= $("#qt-3").val()
        if(toCreate && ((ing1 > -1 && q1 && q1 >= 0) || (ing2 > -1 && q2 && q2 >= 0) || (ing3 > -1 && q3 && q3 >= 0) || (ing4 > -1 && q4 && q4 >= 0)))
        {
            $(this).attr("disabled", true);
            $.ajax({
                type: "POST",
                //checking for null values and repetitions done in API
                url: "operationsAPI.php",
                data: {
                    operation: "addRecipe",
                    toCreate: toCreate,
                    ing1: ing1,
                    q1:q1,
                    ing2: ing2,
                    q2:q2,
                    ing3:ing3 ,
                    q3: q3,
                    ing4: ing4,
                    q4: q4,
                }
            }).done(function(data){
                window.location = "planmenu.php";
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