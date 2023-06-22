<?php
// include header.php
include 'inc/header.php';
// header tags toevoegen
echo '
<header class="head">';
// url voor handmatige voorraad...
echo "<a href='voorraad.php' class='btn-new'><i class='material-icons md-24'>list</i></a>";

//echo '<span class="material-icons-outlined">
//import_export
//</span> ';
echo '
</header>'; //afsluiten header
// voor gridopmaak alvast de main-content
echo '
<main class="main-content">';
echo '<h2>Importeren voorraad...</h2>';
?>
<div id="import">
        <div> <!-- enctype="multipart/form-data" is nodig om $_FILES terug te krijgen...-->
            <form action="dataverwerken.php" method="POST" class="frmImport" enctype="multipart/form-data">
                <!-- button!! -->
                <input type="file" name="csvbestand" id="fBestand" size="25" placeholder="selecteer bestand..." accept=".csv"><br><br>
                <input type="hidden" name="action" value="ImportVoorraad">
                <input class="importbtn" type="submit" name="submit" value="Start Import"><br>
            </form>
        </div>
    </div>


<table id="voorraad">
<tr>
    <th>id</th>
    <th>bestand</th>
    <th>medewerker</th>
    <th>datum_import</th>
    <th>updates</th>
    <th>new</th>
</tr>
<?php
//bepaling 'page' voor paginering
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}
//start vanaf
$start_from = ($page - 1) * RECORDS_PER_PAGE;
//aantal pagina's bepalen t.b.v. paginering
$sql_count = "SELECT count(id) as aantal, bestand, medewerker, datum_import, updates, new FROM log_import
ORDER BY datum_import DESC;";
$res_count = mysqli_query($dbconn, $sql_count);
$row = mysqli_fetch_assoc($res_count);
$total_rows = $row['aantal'];
$total_pages = ceil($total_rows / RECORDS_PER_PAGE);

// ophalen klantgegevens uit database
$query = "SELECT * FROM log_import
ORDER BY datum_import desc
LIMIT " . $start_from . "," . RECORDS_PER_PAGE . ";";
//$resultaat bepalen....
$result = mysqli_query($dbconn, $query);
//aantal records bepalen....
$aantal = mysqli_num_rows($result);
$contentTable = "";
// tabel aanvullen met klantgegevens
if ($aantal > 0) { //controle of er wel wat opgehaald wordt...
    while ($row = mysqli_fetch_array($result)) {
        $contentTable .= "<tr>
                    <td>" . $row['id'] . "</td>                       
                    <td>" . $row['bestand'] . "</td>                       
                    <td>" . $row['medewerker'] . "</td>                       
                    <td>" . $row['datum_import'] . "</td>                       
                    <td>" . $row['updates'] . "</td>                      
                    <td>" . $row['new'] . "</td>                      
                    
                </tr>";
    }
} else {
    $contentTable = '<tr>
                <td colspan="9">Geen gegevens om op te halen...</td>
            </tr>';
}
// weergave van de rest van de tabel;
$contentTable .= '</table><br>';
echo $contentTable;
// paginering van de tabel
$page_url = "voorraad_import.php";
include_once 'inc/paginering.php';

// include footer
echo '</div>'; //frmDetail afsluiten
echo '
</main>'; //main afsluiten
include("inc/footer.php");
?>