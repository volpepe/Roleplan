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

function do_insert_query($sql) {
    global $conn;
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}

function do_delete_query($sql) {
    global $conn;
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}

function print_PG_tabled_info($row) {
    $idPers = $row["IDPersonaggio"];
    global $conn;
    echo '  <table class="table-striped" style="width:100%; text-align: left; margin-bottom: 20px;">
                <th style="text-align:center; font-size:20px; padding: 10px;" colspan=2>'. $row["NomePersonaggio"] .'</th>
                    <tr>
                        <td>Livello:</td>
                        <td>' . $row["Livello"] . '</td>
                    </tr>
                    <tr>
                        <td>Razza:</td>
                        <td>'. $row["NomeRazza"] .'</td>
                    </tr>
                    <tr>
                        <td>PV Max:</td>
                        <td>'. $row["PuntiVitaMax"] .'</td>
                    </tr>
                    <tr>
                        <td>PV Attuali:</td>
                        <td>'. $row["PuntiVitaAtt"] .'</td>
                    </tr>
                    <tr>
                        <td>Punti Esperienza:</td>
                        <td>'. $row["PuntiExp"] .'</td>
                    </tr>
                    <tr>
                        <td>Area in cui si trova:</td>
                        <td>'. $row["NomeArea"] .'</td>
                    </tr>
                    <th style="text-align:center; font-size:17px; padding: 10px;" colspan=2>Inventario</th>
                    <tr>
                        <th>Nome Oggetto</th>
                        <th>Quantità</th>
                    </tr>';

    /* display inventory*/
    $sql = "CREATE VIEW Inventari_Tot(Personaggio, Nome, Quantita, PesoUnitario, PesoTotale) AS 
                SELECT i.Personaggio, o.Nome, i.Quantita, o.Peso, o.Peso*i.Quantita AS PesoTotale
                FROM inventari_pg i, tipi_oggetto o
                WHERE i.TipoOggetto = o.IDTipoOgg";
    $conn->query($sql);
    $sql = "SELECT i.* FROM Inventari_Tot i WHERE Personaggio = " .$idPers;
    $result = $conn->query($sql);
    while($item = $result->fetch_assoc())
    {
        echo "<tr>
                <td>". $item["Nome"]. "</td>
                <td>". $item["Quantita"]."</td>
            </tr>";
    }
    $sql = "SELECT SUM(i.PesoTotale) AS PesoTrasportato FROM Inventari_Tot i WHERE Personaggio = " . $idPers;
    $result = $conn->query($sql);
    if ($result != FALSE){  
        $pesoTot = $result->fetch_assoc();
        echo '<th style="text-align:right; padding: 10px;" colspan=2>Peso totale trasportato: '. $pesoTot["PesoTrasportato"] . '</th>';
    } else {
        echo '<th style="text-align:right; padding: 10px;" colspan=2>Peso totale trasportato: 0.00 </th>';
    }
    /* display quests*/
    echo "</table>
            <h5>Quest in corso:</h5>
            <ul style='text-align:left'>";
    $sql = "CREATE VIEW Quest_Tot(Personaggio, Nome, Terminata) AS
            SELECT p.Personaggio, q.Nome, p.Terminata
            FROM Quest q, Partecipazioni p
            WHERE p.Arco = q.Arco
            AND p.NumQuest = q.NumQuest";
    $result = $conn->query($sql);
    $sql = "SELECT Nome FROM Quest_Tot WHERE Personaggio = " .$idPers . " AND Terminata = 0";
    $result = $conn->query($sql);
    while($quest = $result->fetch_assoc()) {
        echo "<li>".$quest['Nome']."</li>";
    }
    echo "</ul>
        <h5>Quest terminate:</h5>
        <ul style='text-align:left' >";
        $sql = "SELECT Nome FROM Quest_Tot WHERE Personaggio = " .$idPers . " AND Terminata = 1";
    $result = $conn->query($sql);
    while($quest = $result->fetch_assoc()) {
        echo "<li>".$quest['Nome']."</li>";
    }
    echo "</ul>";
}

function get_quest_num($arc){
    global $conn;
    $sql = "SELECT MAX(NumQuest) AS LastID FROM quest WHERE Arco = " . $arc;
    $result=mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return ($row["LastID"] === NULL) ? 0: $row["LastID"] + 1; 
}

/*main switch*/
switch ($_POST["operation"]) {

    //M01
    case 'addRecipe':
        
        $sql = "INSERT INTO ricette(OggettoCreato) 
                VALUES ('" . $conn->escape_string($_POST['toCreate']) . "')";
        do_insert_query($sql);
        $recipe = $conn->insert_id;
        for ($i=1; $i<=4 ; $i++) { 
            if ($_POST["ing" . $i] > -1) {
                $sql = "INSERT INTO parte_di(TipoOggetto, Ricetta, Quantita) 
                        VALUES(" . $_POST["ing" . $i] . ", " . $recipe .", " . $_POST['q' . $i] . ")";
                do_insert_query($sql);
            }
        }
        break;

    //M02
    case 'addArea':
        $array = json_decode($_POST["areaAdiacences"]);
        $sql = "INSERT INTO aree(Mondo, IDArea, NomeArea, Descrizione, CentroAbitato)
                VALUES (" . $_POST["areaWorld"] . ", " . $_POST["areaID"] . ", '" . $conn->escape_string($_POST["areaName"]) . "', '" . $conn->escape_string($_POST["areaDesc"]) . "', 0)";
        do_insert_query($sql);
        $sql = "INSERT INTO class_aree(TipoArea, Mondo, Area) 
                VALUES (" . $_POST["areaType"] . ", " . $_POST["areaWorld"] . ", " . $_POST["areaID"] . ")";
        do_insert_query($sql);
        foreach ($array as $ad_ar) {
            $sql = "INSERT INTO adiacenze(Mondo, Area, AdiacenteAdArea)
                    VALUES (" . $_POST["areaWorld"] . ", " . $_POST["areaID"] . ", " . $ad_ar . ")";
            do_insert_query($sql);
            $sql = "INSERT INTO adiacenze(Mondo, Area, AdiacenteAdArea)
                    VALUES (" . $_POST["areaWorld"] . ", " . $ad_ar . ", " . $_POST["areaID"] . ")";
            do_insert_query($sql);
        }
        break;

    //M03
    case 'addItemInArea':
        $sql = "INSERT INTO oggetti(MondoPresenza, AreaPresenza, TipoOggetto)
                VALUES (" . $_POST["world"] . ", " . $_POST["area"] . ", " . $_POST["objType"] . ")";
        if ($conn->query($sql) === TRUE) {
            echo $conn->insert_id;
        } else {
            echo "Error: " . $conn->error;
        }
        break;

    //M04
    case 'addPG':
        $sql = "INSERT INTO personaggi_giocanti(NomePersonaggio, Livello, PuntiVitaMax, PuntiVitaAtt, PuntiExp, NomeGiocatore, MondoPresenza, AreaPresenza, Razza) 
                VALUES('" . $conn->escape_string($_POST['charName']) . "', " . $_POST["charLevel"] . ", " . $_POST["charPVMax"] . ", " . $_POST["charPVAtt"] . ", " . $_POST["charEXP"] . ", '" . $conn->escape_string($_POST["charPlayer"]) . "', " . $_POST["charWorld"]. ", " . $_POST["charArea"]. ", " . $_POST["charRace"] . ")";
        do_insert_query($sql);
        break;

    //M05
    case 'addNPC':
        $sql = "INSERT INTO npc(Nome, Livello, PuntiVitaMax, PuntiVitaAtt, PuntiExp, MondoPresenza, AreaPresenza, Razza) 
                VALUES('" . $conn->escape_string($_POST['charName']) . "', " . $_POST["charLevel"] . ", " . $_POST["charPVMax"] . ", " . $_POST["charPVAtt"] . ", " . $_POST["charEXP"] . ", " . $_POST["charWorld"]. ", " . $_POST["charArea"]. ", " . $_POST["charRace"] . ")";
        do_insert_query($sql);
        break;
    
    //M06
    case 'addObjType':
        $sql = "INSERT INTO tipi_oggetto(Nome, Peso, Descrizione, CategoriaOggetto, Protezione, Danni, ValoreRelativo) 
                VALUES('" . $conn->escape_string($_POST["name"]) . "', " . $_POST['weight'] . ", '" . $conn->escape_string($_POST['description']) . "', '" . $_POST['type'] . "', " . $_POST['protection'] . ", " . $_POST['damage'] . ", " .  $_POST['value'] .")";
        do_insert_query($sql);
        break;

    //M07
    case 'addQuest':
        $numQuest = get_quest_num($_POST["arc"]);
        $sql = "INSERT INTO quest(Arco, NumQuest, RicompensaExp, Nome, LivelloConsigliato, Descrizione, Disponibile, TipoQuest, NPCDialogo, NPCConsegna, MondoDestinazione, AreaDestinazione) 
                VALUES (" . $_POST["arc"] . ", " . $numQuest . ", " . $_POST["exp"] . ", '" . $conn->escape_string($_POST["questName"]) . "', " . $_POST["minLev"] . ", '" . 
                            $conn->escape_string($_POST["questDesc"]) . "', " . $_POST["disp"] . ", '" . $_POST["questType"] . "', " . $_POST["npcToTalk"] . ", " . $_POST["giverNPC"] . ", " 
                            . $_POST["worldToGetTo"] . ", " . $_POST["areaToGetTo"] . ")";
        do_insert_query($sql);
        //maximum of 5 items
        for ($i=1; $i<=5 ; $i++) { 
            if ($_POST["item" . $i] > -1) {
                $sql = "INSERT INTO ricompense(TipoOggetto, Arco, NumQuest, Quantita)
                        VALUES(" . $_POST["item" . $i] . ", " . $_POST["arc"] .", " . $numQuest . ", " . $_POST["quant" . $i] . ")";
                do_insert_query($sql);
            }
        }
        if ($_POST["questType"] == 'eliminazione') {
            //maximum of 3 npcs to kill
            for ($i=1; $i<=3 ; $i++) { 
                if ($_POST["npcToKill" . $i] > -1) {
                    $sql = "INSERT INTO eliminazioni_necessarie(Arco, NumQuest, NPCDaEliminare) 
                            VALUES (" . $_POST["arc"] .", " . $numQuest . ", " . $_POST["npcToKill" . $i] . ")";
                    do_insert_query($sql);
                }
            }
        }
        else if ($_POST["questType"] == 'raccolto') {
            //maximum of 5 items to obtain
            for ($i=1; $i<=5 ; $i++) { 
                if ($_POST["itemToObtain" . $i] > -1) {
                    $sql = "INSERT INTO raccolti_necessari(OggettoDaRaccogliere, Arco, NumQuest, Quantita) 
                            VALUES (" . $_POST["objToGet" . $i] .", " . $_POST["arc"] . ", " . $numQuest . ", " . $_POST["quantToGet" . $i] . ")";
                    do_insert_query($sql);
                }
            }
        }
        break;
    
    //M08
    case 'addArc':
        $sql = "INSERT INTO Archi(NomeArco) VALUES ('" . $_POST["arc"] . "')";
        do_insert_query($sql);
        break;

    
    //M11
    case 'removeItemFromArea':
        $sql = "DELETE FROM oggetti
                WHERE IDOggetto = " . $_POST["id"];
        do_delete_query($sql);
        break;

    //E01
    case 'viewPG':
        $sql = "SELECT p.IDPersonaggio, p.NomePersonaggio, p.Livello, p.PuntiVitaMax, p.PuntiVitaAtt, p.PuntiExp, a.NomeArea, r.NomeRazza 
                FROM personaggi_giocanti p, razze r, aree a 
                WHERE p.NomeGiocatore='" . $_POST["name"] . "' 
                AND p.MondoPresenza = ". $_POST["world"] . "
                AND p.AreaPresenza = a.IDArea
                AND p.Razza = r.IDRazza";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            //for each charachter
            print_PG_tabled_info($row);
        }
        break;
    
    default:
        echo "no operation";
        break;
}

//TODO: test E01 on quests
?>

