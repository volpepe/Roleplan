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
        echo "New record created successfully</br>";
    } else {
        echo "Error: " . $conn->error . "</br>";
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


/*main switch*/
switch ($_POST["operation"]) {

    //M01
    case 'addRecipe':
        $sql = "INSERT INTO ricette(OggettoCreato) VALUES ('" . $_POST["toCreate"] . "')";
        do_insert_query($sql);
        $recipe = $conn->insert_id;
        for ($i=1; $i<=4 ; $i++) { 
            if ($_POST["ing" . $i] > -1) {
                $sql = "INSERT INTO parte_di(TipoOggetto, Ricetta, Quantita) VALUES(" . $_POST["ing" . $i] . ", " . $recipe .", " . $_POST['q' . $i] . ")";
                do_insert_query($sql);
            }
        }
        break;

    //M02
    case 'addArea':
        $array = json_decode($_POST["areaAdiacences"]);
        $sql = "INSERT INTO aree(Mondo, IDArea, NomeArea, Descrizione, CentroAbitato)
        VALUES (" . $_POST["areaWorld"] . ", " . $_POST["areaID"] . ", '" . $_POST["areaName"] . "', '" . $_POST["areaDesc"] . "', 0)";
        do_insert_query($sql);
        $sql = "INSERT INTO class_aree(TipoArea, Mondo, Area) VALUES (" . $_POST["areaType"] . ", " . $_POST["areaWorld"] . ", " . $_POST["areaID"] . ")";
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

    //M04
    case 'addPG':
        $sql = "INSERT INTO Personaggi_Giocanti(NomePersonaggio, Livello, PuntiVitaMax, PuntiVitaAtt, PuntiExp, NomeGiocatore, MondoPresenza, AreaPresenza, Razza) VALUES('". $_POST["charName"] . "', " . $_POST["charLevel"] . ", " . $_POST["charPVMax"] . ", " . $_POST["charPVAtt"] . ", " . $_POST["charEXP"] . ", '" . $_POST["charPlayer"]. "', " . $_POST["charWorld"]. ", " . $_POST["charArea"]. ", " . $_POST["charRace"] . ")";
        do_insert_query($sql);
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
?>