<?php
include 'inc/header.php';
// header tags toevoegen
echo '<header class="head">';

echo '</header>'; //afsluiten header
// voor gridopmaak alvast de main-content
echo '<main class="main-content">';
// FORM EDIT product...
echo '<div id="frmDetail">';
if (isset($_GET["id"])) {
    $productId=$_GET["id"];
}
else {
    echo 'Product niet gevonden...';
    header('refresh: 2; url=voorraad.php');
}

$qryProduct = "SELECT id, artikelnummer, omschrijving, leverancier, artikelgroep, eenheid, prijs, aantal
        FROM product
        WHERE id={$productId}";
//result...
$resultProduct = mysqli_query($dbconn, $qryProduct);
if(!mysqli_num_rows($resultProduct)==1) {
    echo 'Er zijn meerdere producten geselecteerd. Dit gaat niet goed!';
    header('refresh: 2; url=voorraad.php');
}
//1 record...
$product=mysqli_fetch_assoc($resultProduct);
?>
<div>
    <form action ="dataverwerken.php" method="POST" class="frmDetail">
    <div id="product_custum_label">aanpassen</div>
        <input type="hidden" name="action" value="UpdateProduct">
        <input type="hidden" name="id" value="<?php echo $productId;?>">
        <label for="fproductnr">Artikelnummer:</label>
        <input type="text" name="artikelnummer" value="<?php echo $product["artikelnummer"];?>" id="fproductnr">
        <label for="fproductomschrijving">Omschrijving:</label>
        <input type="text" name="omschrijving" value="<?php echo $product["omschrijving"];?>" id="fproductomschrijving">
        <label for="fLeverancier">Leverancier.:</label>
        <input type="text" name="leverancier" value="<?php echo $product["leverancier"];?>" id="fLeverancier">
        <label for="fArtikelgroep">Artikelgroep:</label>
        <input type="text" name="artikelgroep" value="<?php echo $product["artikelgroep"];?>" id="fArtikelgroep">
        <label for="fEenheid">Eenheid:</label>
        <input type="text" name="eenheid" value="<?php echo $product["eenheid"];?>" id="fEenheid">
        <label for="fPrijs">Prijs:</label>
        <input type="text" name="prijs" value="<?php echo $product["prijs"];?>" id="fPrijs">
        <label for="fAantal">Aantal:</label>
        <input type="text" name="aantal" value="<?php echo $product["aantal"];?>" id="fAantal">
        <div class="submitbtn">
            <input type="submit" name="submit" value="bewaren..." class="btnDetailSubmit">
        </div>
    </form>
</div>
<?php
echo '</div>'; //frmDetail
echo '</main>'; //main-content
include ("inc/footer.php");
?>
