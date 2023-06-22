<?php
// include header.php
include 'inc/header.php';
// header tags toevoegen
echo '<header class="head">';
// url voor handmatige voorraad...


$_SESSION['omschrijving_voorraad'] =  isset($_POST['omschrijving']) ? ($_POST['omschrijving']) :  $_SESSION['omschrijving_voorraad']; 
$_SESSION['artikelgroep_voorraad'] = isset($_POST['artikelgroep']) ? ($_POST['artikelgroep']) :  $_SESSION['artikelgroep_voorraad'];

$omschrijving = $_SESSION['omschrijving_voorraad'];
$artikelgroep = $_SESSION['artikelgroep_voorraad'];

?>

<form method="POST" id="magazijn_zoekopdrachten_form">
<a href='product_new.php' class='btn-new'><i class='material-icons md-24'>add</i></a>
<a href='voorraad_import.php' class='btn-new'><i class='material-icons md-24'>file_upload</i></a>
<a href='minimum.php' class='btn-new'><i class='material-icons md-24'>refresh</i></a>
    Omschrijving: <input type="text" name="omschrijving" id="inp_voorraad_zoeken" value="<?php echo ($omschrijving) ?>">
    Artikelgroep: <input type="text" name="artikelgroep" id="inp_voorraad_zoeken" value="<?php echo ($artikelgroep) ?>">
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
        <?php
if($_SESSION['rol'] != 'kassiere'){?>
    
    <th>artikelnummer</th>
    <th>omschrijving</th>
    <th>leverancier</th>
    <th>artikelgroep</th>
    <th>eenheid</th>
    <th>prijs</th>
    <th>aantal</th>
    <th>actie</th>
        
            </tr><?php
}else{?>
    <th>artikelnummer</th>
    <th>omschrijving</th>
    <th>leverancier</th>
    <th>artikelgroep</th>
    <th>eenheid</th>
    <th>prijs</th>
    <th>aantal</th>
    <th>actie</th>
    <?php
}
        ?>
        <tr>
            
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
        $sql_count = "SELECT count(id) as aantal FROM product WHERE (omschrijving LIKE '%$omschrijving%' OR '$omschrijving' = '') AND (artikelgroep LIKE '%$artikelgroep%' OR '$artikelgroep' = '');";
        $res_count = mysqli_query($dbconn, $sql_count);
        $row = mysqli_fetch_assoc($res_count);
        $total_rows = $row['aantal'];
        $total_pages = ceil($total_rows / RECORDS_PER_PAGE);

        // ophalen klantgegevens uit database
        $query = "SELECT id, artikelnummer, omschrijving, leverancier, artikelgroep, eenheid, prijs, aantal FROM product
        WHERE (omschrijving LIKE '%$omschrijving%' OR '$omschrijving' = '') AND (artikelgroep LIKE '%$artikelgroep%' OR '$artikelgroep' = '')
        ORDER BY omschrijving, artikelgroep, artikelnummer
        LIMIT " . $start_from . "," . RECORDS_PER_PAGE . ";";
        //$resultaat bepalen....
        $result = mysqli_query($dbconn, $query);
        //aantal records bepalen....
        $aantal = mysqli_num_rows($result);
        $contentTable = "";
        // tabel aanvullen met klantgegevens
        if ($aantal > 0) { //controle of er wel wat opgehaald wordt...
            while ($row = mysqli_fetch_array($result)) {
                if($_SESSION['rol'] != 'kassiere'){
                    $contentTable .= "<tr>
                    
                            <td>" . $row['artikelnummer'] . "</td>                       
                            <td>" . $row['omschrijving'] . "</td>                       
                            <td>" . $row['leverancier'] . "</td>                       
                            <td>" . $row['artikelgroep'] . "</td>                       
                            <td>" . $row['eenheid'] . "</td>                      
                            <td>" . $row['prijs'] . "</td>                      
                            <td>" . $row['aantal'] . "</td>
                            <td>
                                <a href='product_edit.php?id={$row['id']}' class='btn-edit'><i class='material-icons md-24'>edit</i></a>
                                <a href='product_delete.php?id={$row['id']}' class='btn-delete'><i class='material-icons md-24'>delete</i></a>
                                <a href='product_afschrijven.php?id={$row['id']}' class='btn-afschrijf'><i class='material-icons md-24'>close</i></a>
                                <a href='product_bestel.php?id={$row['id']}' class='btn-bestel'><i class='material-icons md-24'>add_box</i></a>
                            </td>
                            </tr>";
                }else{
                    $contentTable .= "<tr>
                            <td>" . $row['artikelnummer'] . "</td>                       
                            <td>" . $row['omschrijving'] . "</td>                       
                            <td>" . $row['leverancier'] . "</td>                       
                            <td>" . $row['artikelgroep'] . "</td>                       
                            <td>" . $row['eenheid'] . "</td>                      
                            <td>" . $row['prijs'] . "</td>                      
                            <td>" . $row['aantal'] . "</td>";
                }
                
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
        $page_url = "voorraad.php";
        include_once 'inc/paginering.php';


        // include footer
        //echo '</div>'; //frmDetail afsluiten
        echo '</main>'; //main afsluiten
        include("inc/footer.php");
        ?>