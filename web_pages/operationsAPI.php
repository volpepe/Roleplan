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

function do_query($sql) {
    global $conn;
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully</br>";
    } else {
        echo "Error: " . $conn->error . "</br>";
    }
}

switch ($_POST["operation"]) {

    //M01
    case 'addRecipe':
        $sql = "INSERT INTO ricette(OggettoCreato) VALUES ('" . $_POST["toCreate"] . "')";
        do_query($sql);
        $recipe = $conn->insert_id;
        for ($i=1; $i<=4 ; $i++) { 
            if ($_POST["ing" . $i] > -1) {
                $sql = "INSERT INTO parte_di(TipoOggetto, Ricetta, Quantita) VALUES(" . $_POST["ing" . $i] . ", " . $recipe .", " . $_POST['q' . $i] . ")";
                do_query($sql);
            }
        }
        break;

    //M02
    case 'addArea':
        $array = json_decode($_POST["areaAdiacences"]);
        $sql = "INSERT INTO aree(Mondo, IDArea, NomeArea, Descrizione, CentroAbitato)
        VALUES (" . $_POST["areaWorld"] . ", " . $_POST["areaID"] . ", '" . $_POST["areaName"] . "', '" . $_POST["areaDesc"] . "', 0)";
        do_query($sql);
        $sql = "INSERT INTO class_aree(TipoArea, Mondo, Area) VALUES (" . $_POST["areaType"] . ", " . $_POST["areaWorld"] . ", " . $_POST["areaID"] . ")";
        do_query($sql);
        foreach ($array as $ad_ar) {
            $sql = "INSERT INTO adiacenze(Mondo, Area, AdiacenteAdArea)
            VALUES (" . $_POST["areaWorld"] . ", " . $_POST["areaID"] . ", " . $ad_ar . ")";
            do_query($sql);
            $sql = "INSERT INTO adiacenze(Mondo, Area, AdiacenteAdArea)
            VALUES (" . $_POST["areaWorld"] . ", " . $ad_ar . ", " . $_POST["areaID"] . ")";
            do_query($sql);
        }
        break;

    //M04
    case 'addPG':
        $sql = "INSERT INTO Personaggi_Giocanti(NomePersonaggio, Livello, PuntiVitaMax, PuntiVitaAtt, PuntiExp, NomeGiocatore, MondoPresenza, AreaPresenza, Razza) VALUES('". $_POST["charName"] . "', " . $_POST["charLevel"] . ", " . $_POST["charPVMax"] . ", " . $_POST["charPVAtt"] . ", " . $_POST["charEXP"] . ", '" . $_POST["charPlayer"]. "', " . $_POST["charWorld"]. ", " . $_POST["charArea"]. ", " . $_POST["charRace"] . ")";
        do_query($sql);
        break;
    
    default:
        echo "no operation";
        break;
}
?>