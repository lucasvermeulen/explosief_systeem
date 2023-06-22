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
    $productId = $_GET["id"];
} else {
    echo 'Product niet gevonden...';
    header('refresh: 2; url=voorraad.php');
}

$qryProduct = "SELECT id, artikelnummer, omschrijving, leverancier, artikelgroep, eenheid, prijs, aantal
        FROM product
        WHERE id={$productId}";
//result...
$resultProduct = mysqli_query($dbconn, $qryProduct);
if (!mysqli_num_rows($resultProduct) == 1) {
    echo 'Er zijn meerdere producten geselecteerd. Dit gaat niet goed!';
    header('refresh: 2; url=voorraad.php');
}
//1 record...
$product = mysqli_fetch_assoc($resultProduct);
?>
<div>
    <form action="dataverwerken.php" method="POST" class="frmDetail">
    <div id="product_custum_label">Bestellen</div>
        <input type="hidden" name="action" value="BestelProduct">
        
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <input type="hidden" name="reden" value="">
        <label for="freden">Reden:</label>
        <input type="text" name="reden" value="" id="freden">
        <label for="fAantal">Aantal:</label>
        <input type="text" name="aantal" value="" id="fAantal">
        <div class="submitbtn">
            <input type="submit" name="submit" value="bewaren..." class="btnDetailSubmit">
        </div>
    </form>
</div>
<?php
echo '</div>'; //frmDetail
echo '</main>'; //main-content
include("inc/footer.php");
?>