<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/choicestyle.css">
    <link rel="stylesheet" href="../styles/topbar.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>RolePlan: NPC Page</title>
</head>

<body>
    <div class="container-fluid">
        <div class="jumbotron jumbotron-fluid">
            <div class="container-fluid">
                <a id="back-button" href="../index.php"><< <span>Torna al menu principale</span></a>
                <p>RolePlan</p>
            </div>
        </div>
        <div class="row">
            <div class="col mh-100"> 
                <button id="npc-button" class="shadow-lg align-middle rounded"><i class="fas fa-tasks"></i> Inserimento NPC</button>
            </div>
            <div class="col">
                <button id="interest-button" class="shadow-lg align-middle rounded"><i class="fas fa-search-dollar"></i> Modifica Interessi</button>
            </div>
            <div class="col">
                <button id="work-button" class="shadow-lg align-middle rounded"><i class="fas fa-briefcase"></i> Modifica Occupazioni</button>
            </div>
        </div>
    </div>
</body>

</html>

<script>
$(document).ready(function(){
    $("#npc-button").click(function(){
        window.location = "../selections/worldchoose.php?callback=../operations/addNPC.php"
    })
    $("#interest-button").click(function(){
        window.location = "../selections/worldchoose.php?callback=../operations/addinterest.php"
    })
    $("#work-button").click(function(){
        window.location = "../selections/worldchoose.php?callback=../operations/addwork.php"
    })
})
</script>