<?php
// include header.php
include('inc/header.php');
// header tags toevoegen
echo '<header class="head">';
// url voor handmatige voorraad...


$_SESSION['omschrijving_gekocht'] =  isset($_POST['omschrijving']) ? ($_POST['omschrijving']) :  $_SESSION['omschrijving_gekocht']; 
$_SESSION['artikelgroep_gekocht'] = isset($_POST['artikelgroep']) ? ($_POST['artikelgroep']) :  $_SESSION['artikelgroep_gekocht'];
$_SESSION['finacieel_begindate'] =  isset($_POST['finacieel_begindate']) ? ($_POST['finacieel_begindate']) :  $_SESSION['finacieel_begindate']; 
$_SESSION['finacieel_einddate'] = isset($_POST['finacieel_einddate']) ? ($_POST['finacieel_einddate']) :  $_SESSION['finacieel_einddate'];

$omschrijving = $_SESSION['omschrijving_gekocht'];
$artikelgroep = $_SESSION['artikelgroep_gekocht'];

$finacieel_begindate = $_SESSION['finacieel_begindate'];
$finacieel_einddate = $_SESSION['finacieel_einddate'];

$date =  isset($finacieel_begindate) ? ($finacieel_begindate) :  date("Y-m-d"); 

?>

<form method="POST" id="magazijn_zoekopdrachten_form">
<a href='financieel.php' class='btn-new'><i class='material-icons md-24'>arrow_back_ios</i></a>
    Omschrijving: <input type="text" name="omschrijving" id="inp_voorraad_zoeken" value="<?php echo ($omschrijving) ?>">
    Artikelgroep: <input type="text" name="artikelgroep" id="inp_voorraad_zoeken" value="<?php echo ($artikelgroep) ?>">
    <input type="date" id="finacieel_begindate" value="<?php echo  $date;?>" name="finacieel_begindate">
    <input type="date" id="finacieel_einddate" name="finacieel_einddate" value="<?php echo ($finacieel_einddate) ?>">
    
    <button id="zoekbutton_magazijn" type="submit" name="submit" value="Zoeken ">Zoeken</button>
    <?php
echo '</header>'; //afsluiten header
// voor gridopmaak alvast de main-content
echo '<main class="main-content">';
?>

    <!-- zoekform maken -->
    

    <!-- tabelkop met Voorraad als HTML-->
    <table id="voorraad">
        <tr>
            <th>artikelnummer</th>
            <th>omschrijving</th>
            <th>leverancier</th>
            <th>artikelgroep</th>
            <th>eenheid</th>
            <th>prijs</th>
            <th>BTW</th>
            <th>aantal</th>
            
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
$sql_count = "SELECT count(b.id) as id, artikelgroep, b.omschrijving FROM bestelling as b
INNER JOIN product as p ON b.product_id = p.id 
WHERE (b.omschrijving LIKE '%$omschrijving%' OR '$omschrijving' = '') AND (artikelgroep LIKE '%$artikelgroep%' OR '$artikelgroep' = '');";
$res_count = mysqli_query($dbconn, $sql_count);
$row = mysqli_fetch_assoc($res_count);
$total_rows = $row['id'];
$total_pages = ceil($total_rows / RECORDS_PER_PAGE);


// ophalen klantgegevens uit database
$query = "SELECT b.id, b.transactie_id, b.product_id, b.omschrijving, b.prijs, b.aantal, b.create_time, p.artikelnummer, p.omschrijving, p.leverancier, p.artikelgroep, p.eenheid, b.create_time FROM bestelling as b
INNER JOIN product as p ON b.product_id = p.id
        WHERE (b.omschrijving LIKE '%$omschrijving%' OR '$omschrijving' = '') AND (p.artikelgroep LIKE '%$artikelgroep%' OR '$artikelgroep' = '')
        AND (b.create_time <= '$finacieel_einddate' OR '$finacieel_einddate' = '') AND (b.create_time >= '$finacieel_begindate' OR '$finacieel_begindate' = '')
        ORDER BY create_time DESC
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
                            <td>" . $row['artikelnummer'] . "</td>                       
                            <td>" . $row['omschrijving'] . "</td>                       
                            <td>" . $row['leverancier'] . "</td>                       
                            <td>" . $row['artikelgroep'] . "</td>                       
                            <td>" . $row['eenheid'] . "</td>                      
                            <td>" . $row['prijs'] . "</td>          
                            <td>" . round($row['prijs'] * 0.21 , 2)  . "</td>                
                            <td>" . $row['aantal'] . "</td>        

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
$page_url = "gekocht_check.php";
include_once 'inc/paginering.php';

// include footer
//echo '</div>'; //frmDetail afsluiten
echo '</main>'; //main afsluiten
include("inc/footer.php") ;
?>
