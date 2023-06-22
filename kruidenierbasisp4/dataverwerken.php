<?php
include 'inc/header.php';
// header tags toevoegen
echo '<header class="head">';

echo '</header>'; //afsluiten header

// voor gridopmaak alvast de main-content
echo '<main class="main-content">';
// Begin FORM
//echo '<div id="frmDetail">';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = isset($_POST["action"]) ? $_POST["action"] : 'LEEG';
    switch ($action) {
        case "UpdateProduct":
            updateProductDetail();
            break;
        case "AfschrijfProduct":
            AfschrijfProduct();
            break;
        case "BestelProduct":
            BestelProduct();
            break;
        case "NewProduct":
            newProduct();
            break;
        case "ImportVoorraad":
            importCSV();
            break;
        case "DeleteProduct":
            //deleteProduct();
            $delValue = false;
            $delValue = isset($_POST["verwijderen"]) ? true : false;
            $idProduct = isset($_POST["id"]) ? $_POST['id'] : 0;
            if ($delValue) {
                deleteProduct($idProduct);
                header('refresh: 1; url=voorraad.php');
            } else {
                echo 'Product ' . $idProduct . ' is niet verwijderd...<br>';
                header('refresh: 1; url=voorraad.php');
            }
            break;
        case "LEEG":
        default:
            echo "geen geldige actie...";
    }
} else {
    header('url=index.php');
}
function BestelProduct()
{
    global $dbconn;
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    $qryProduct = "SELECT prijs, omschrijving, aantal
                    FROM product
                    WHERE id={$_POST['id']}";
    $resultProduct = mysqli_query($dbconn, $qryProduct);
    $product = mysqli_fetch_assoc($resultProduct);
    $product_id = $_POST['id'];
    $reden = $_POST['reden'];
    $aantal = $_POST['aantal'];
    $prijs = $product['prijs'] * $aantal;

    $qryInsertProduct = "INSERT INTO financiel 
                        (product_id, reden, aantal, prijs, omschrijving, medenwerker)
                        values('{$id}', '{$reden}', '{$aantal}', '{$prijs}' , '{$product['omschrijving']}', '{$_SESSION['inlognaam']}');";
    $nieuwAantal = $product['aantal'] + $aantal;
    $qryUpdateProduct = "update product
                        set aantal={$nieuwAantal} 
                            where id={$id}";

    mysqli_query($dbconn, $qryUpdateProduct);
    if (mysqli_query($dbconn, $qryInsertProduct)) {
        echo "<p>Product {$product['omschrijving']} is besteld</p><br>";
        header('refresh: 1; url=voorraad.php');
        exit();
    } else {
        echo "<p>Product {$product['omschrijving']} is NIET toegevoegd...</p><br>";
        header('refresh: 10; url=voorraad.php');
        exit();
    }
}
function AfschrijfProduct()
{
    global $dbconn;
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    $qryProduct = "SELECT prijs, omschrijving, aantal
                    FROM product
                    WHERE id={$_POST['id']}";
    $resultProduct = mysqli_query($dbconn, $qryProduct);
    $product = mysqli_fetch_assoc($resultProduct);
    $product_id = $_POST['id'];
    $reden = $_POST['reden'];
    $aantal = $_POST['aantal'];
    $prijs = $product['prijs'] * $aantal;

    $qryInsertProduct = "INSERT INTO financiel 
                        (product_id, reden, aantal, prijs, omschrijving, medenwerker)
                        values('{$id}', '{$reden}', '-{$aantal}', '{$prijs}' , '{$product['omschrijving']}', '{$_SESSION['inlognaam']}');";
    $nieuwAantal = $product['aantal'] - $aantal;
    $qryUpdateProduct = "update product
                        set aantal={$nieuwAantal} 
                            where id={$id}";

    mysqli_query($dbconn, $qryUpdateProduct);
    if (mysqli_query($dbconn, $qryInsertProduct)) {
        echo "<p>Product {$product['omschrijving']} is afgeschreven</p><br>";
        header('refresh: 1; url=voorraad.php');
        exit();
    } else {
        echo "<p>Product {$product['omschrijving']} is NIET toegevoegd...</p><br>";
        header('refresh: 10; url=voorraad.php');
        exit();
    }
}
function updateProductDetail()
{
    global $dbconn;

    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $artikelnr = isset($_POST['artikelnummer']) ? addslashes($_POST['artikelnummer']) : "";
    $omschrijving = isset($_POST['omschrijving']) ? addslashes($_POST['omschrijving']) : "";
    $leverancier = isset($_POST['leverancier']) ? addslashes($_POST['leverancier']) : "";
    $artikelgroep = isset($_POST['artikelgroep']) ? $_POST['artikelgroep'] : "";
    $eenheid = isset($_POST['eenheid']) ? $_POST['eenheid'] : "";
    $prijs = isset($_POST['prijs']) ? addslashes($_POST['prijs']) : "";
    $prijs = str_replace(",", ".", $prijs);
    $aantal = isset($_POST['aantal']) ? $_POST['aantal'] : "";
    $qryUpdateProduct = "update product
                set artikelnummer='{$artikelnr}', omschrijving='{$omschrijving}', leverancier='{$leverancier}', artikelgroep='{$artikelgroep}', eenheid='{$eenheid}', 
                    prijs='{$prijs}', aantal={$aantal} 
                    where id={$id}";

    if (mysqli_query($dbconn, $qryUpdateProduct)) {
        echo "<p>Product {$omschrijving} ({$artikelnr}) is aangepast</p><br>";
        header('refresh: 1; url=voorraad.php');
        exit();
    } else {
        echo "<p>Product {$omschrijving} ({$artikelnr}) is NIET aangepast</p><br>
                $qryUpdateProduct<br>";
        header('refresh: 4; url=voorraad.php');
        exit();
    }
}

//id, artikelnummer, omschrijving, leverancier, artikelgroep, eenheid, prijs, aantal FROM product
function newProduct()
{
    global $dbconn;
    $artikelnr = isset($_POST['artikelnummer']) ? addslashes($_POST['artikelnummer']) : "";
    $omschrijving = isset($_POST['omschrijving']) ? addslashes($_POST['omschrijving']) : "";
    $leverancier = isset($_POST['leverancier']) ? addslashes($_POST['leverancier']) : "";
    $artikelgroep = isset($_POST['artikelgroep']) ? $_POST['artikelgroep'] : "";
    $eenheid = isset($_POST['eenheid']) ? $_POST['eenheid'] : "";
    $prijs = isset($_POST['prijs']) ? addslashes($_POST['prijs']) : "";
    $prijs = str_replace(",", ".", $prijs);
    $aantal = isset($_POST['aantal']) ? $_POST['aantal'] : "";
    $qryInsertProduct = "INSERT INTO product 
                            (artikelnummer, omschrijving, leverancier, artikelgroep, eenheid, prijs, aantal)
                             values('{$artikelnr}', '{$omschrijving}', '{$leverancier}', '{$artikelgroep}', '{$eenheid}','{$prijs}',{$aantal});";
    if (mysqli_query($dbconn, $qryInsertProduct)) {
        echo "<p>Product {$omschrijving} is toegevoegd</p><br>";
        header('refresh: 1; url=voorraad.php');
        exit();
    } else {
        echo "<p>Product {$omschrijving} is NIET toegevoegd...</p><br>";
        header('refresh: 10; url=voorraad.php');
        exit();
    }
}

function importCSV()
{

    //    $filename = $_FILES["csvbestand"]["name"];
    $extension = getFileExtension($_FILES["csvbestand"]["name"]);
    $filename = $_FILES['csvbestand']['tmp_name'];
    $fileInfo = pathinfo($filename);
    echo '<h2>Voorraad bijwerken</h2>';
    if ($extension == 'csv' and $_FILES['csvbestand']['size'] > 0) { // importeren...
        // bestand openen
        $importFile = fopen($filename, "r");
        $update = 0;
        $new = 0;
        while (!feof($importFile)) {

            //[0] => artikelnummer [1] => omschrijving [2] => leverancier
            //[3] => artikelgroep [4] => eenheid [5] => prijs [6] => aantal
            $record = fgetcsv($importFile, 255, ";");
            if (is_array($record)) { //lege regels vermijden...
                $field_one = $record[0];
                $field_one = substr($field_one, -13); // artikelnummer...

                if ($field_one != 'artikelnummer') {
                    // controle voorraad product; bestaat niet=>-1 anders >=0
                    $voorraad = getProductVoorraad($record[0]); 
                    if ($voorraad >= 0) { //bestaat...
                        $voorraadTotaal = $voorraad + $record[6];
                        updateProduct($record[0], str_replace(",", ".", $record[5]), $voorraadTotaal);
                        $update++;
                    } else { // bestaat niet=>insert
                        $minimum = $record[6] * 0.2;
                        insertProduct($record[0], $record[1], $record[2], $record[3], $record[4], str_replace(",", ".", $record[5]), $record[6], $minimum);
                        $new++;
                    }
                }
            }
        }
        // msg update/new
        $strMsg = '<p>Vooraadverwerking geslaagd</p>';
        $strMsg .= '<dl>
                        <dt>Producten:</dt>
                        <dd>Bijgewerkt: ' . $update . '</dd>
                        <dd>Nieuw:' . $new . '</dd>
                    </dl>';
        logImport($_FILES['csvbestand']['name'], $_SESSION['inlognaam'], $update, $new);
        
    } else { // niet het juiste format...
        $strMsg = '<p>Helaas, geen juist bestand</p>';
    }
    fclose($importFile);
    echo $strMsg;
    
    echo('<a href="voorraad_import.php">Terug</a>');
}

?>

<?php
//echo '</div>'; //frmDetail afsluiten
echo '</main>'; //main afsluiten 
include("inc/footer.php");
?>