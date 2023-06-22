<?php
// include header.php
include 'inc/header.php';
// header tags toevoegen
echo '<header class="head">';
// url voor handmatige voorraad...




$_SESSION['omschrijving_fin'] =  isset($_POST['omschrijving']) ? ($_POST['omschrijving']) :  $_SESSION['omschrijving_fin']; 
$_SESSION['artikelgroep_fin'] = isset($_POST['artikelgroep']) ? ($_POST['artikelgroep']) :  $_SESSION['artikelgroep_fin'];
$_SESSION['finacieel_begindate'] =  isset($_POST['finacieel_begindate']) ? ($_POST['finacieel_begindate']) :  $_SESSION['finacieel_begindate']; 
$_SESSION['finacieel_einddate'] = isset($_POST['finacieel_einddate']) ? ($_POST['finacieel_einddate']) :  $_SESSION['finacieel_einddate'];

$omschrijving = $_SESSION['omschrijving_fin'];
$artikelgroep = $_SESSION['artikelgroep_fin'];
$finacieel_begindate = $_SESSION['finacieel_begindate'];
$finacieel_einddate = $_SESSION['finacieel_einddate'];

$date =  isset($finacieel_begindate) ? ($finacieel_begindate) :  date("Y-m-d"); 


?>


<form method="POST" id="magazijn_zoekopdrachten_form">
<a href='gekocht_check.php' class='btn-new'><i class='material-icons md-24'>refresh</i></a>
    Omschrijving: <input type="text" name="omschrijving" id="inp_voorraad_zoeken" value="<?php echo ($omschrijving) ?>">
    Artikelgroep: <input type="text" name="artikelgroep" id="inp_voorraad_zoeken" value="<?php echo ($artikelgroep) ?>">
        
    <input type="date" id="finacieel_begindate" value="<?php echo  $date;?>" name="finacieel_begindate">
    <input type="date" id="finacieel_einddate" name="finacieel_einddate" value="<?php echo ($finacieel_einddate) ?>">
    
    <button id="zoekbutton_magazijn" type="submit" name="submit" value="Zoeken ">Zoeken</button>
    <?php

    //echo '<span class="material-icons-outlined">
    //import_export
    //</span> ';
    echo '</header>'; //afsluiten header
    // voor gridopmaak alvast de main-content
    echo '<main class="main-content">';


   
    ?>

    <!-- tabelkop met Voorraad als HTML-->
    <table id="voorraad">
        <tr>
            <th>id</th>
            <th>product_id</th>
            <th>omschrijving</th>
            <th>artikelgroep</th>
            <th>reden</th>
            <th>aantal</th>
            <th>prijs</th>
            <th>medenwerker</th>
            <th>create_time</th>
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
        $sql_count = "SELECT count(f.id) as aantal, f.omschrijving, p.artikelgroep, f.create_time FROM financiel as f
        INNER JOIN product as p ON f.product_id = p.id
        WHERE (LOWER( f.omschrijving ) LIKE LOWER('%$omschrijving%') OR '$omschrijving' = '') AND (LOWER(p.artikelgroep) LIKE LOWER('%$artikelgroep%') OR '$artikelgroep' = '') 
        AND (f.create_time < '$finacieel_einddate' OR '$finacieel_einddate' = '') AND (f.create_time > '$finacieel_begindate' OR '$finacieel_begindate' = '');";
        $res_count = mysqli_query($dbconn, $sql_count);
        $row = mysqli_fetch_assoc($res_count);
        $total_rows = $row['aantal'];
        $total_pages = ceil($total_rows / RECORDS_PER_PAGE);

        // ophalen klantgegevens uit database
        $query = "SELECT f.id, f.product_id, f.omschrijving, f.reden, f.aantal, f.prijs, f.create_time, f.medenwerker, p.artikelgroep FROM financiel as f
        INNER JOIN product as p ON f.product_id = p.id
        WHERE (LOWER( f.omschrijving ) LIKE LOWER('%$omschrijving%') OR '$omschrijving' = '') AND (LOWER(p.artikelgroep) LIKE LOWER('%$artikelgroep%') OR '$artikelgroep' = '')
        AND (f.create_time <= '$finacieel_einddate' OR '$finacieel_einddate' = '') AND (f.create_time >= '$finacieel_begindate' OR '$finacieel_begindate' = '')
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
                            <td>" . $row['id'] . "</td>                       
                            <td>" . $row['product_id'] . "</td>                       
                            <td>" . $row['omschrijving'] . "</td>     
                            <td>" . $row['artikelgroep'] . "</td>                  
                            <td>" . $row['reden'] . "</td>                       
                            <td>" . $row['aantal'] . "</td>                      
                            <td>" . $row['prijs'] . "</td>                      
                            <td>" . $row['medenwerker'] . "</td>
                            <td>" . $row['create_time'] . "</td>
                            
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
        $page_url = "financieel.php";
        include_once 'inc/paginering.php';


        // include footer
        //echo '</div>'; //frmDetail afsluiten
        echo '</main>'; //main afsluiten
        include("inc/footer.php");
        ?>