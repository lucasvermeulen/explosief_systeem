<?php
include 'inc/header.php';
// header tags toevoegen
echo '<header class="head">';
echo '<p>Nieuw product toevoegen...</p>';
echo '</header>'; //afsluiten header

// voor gridopmaak alvast de main-content
echo '<main class="main-content">';
// FORM EDIT product...
echo '<div id="frmDetail">';

?>
<div>
    <form action ="dataverwerken.php" method="POST" class="frmDetail">
        <input type="hidden" name="action" value="NewProduct">
        <label for="fproductnr">Artikelnummer:</label>
        <input type="text" name="artikelnummer" value="" id="fproductnr">
        <label for="fproductomschrijving">Omschrijving:</label>
        <input type="text" name="omschrijving" value="" id="fproductomschrijving">
        <label for="fLeverancier">Leverancier.:</label>
        <input type="text" name="leverancier" value="" id="fLeverancier">
        <label for="fArtikelgroep">Artikelgroep:</label>
        <input type="text" name="artikelgroep" value="" id="fArtikelgroep">
        <label for="fEenheid">Eenheid:</label>
        <input type="text" name="eenheid" value="" id="fEenheid">
        <label for="fPrijs">Prijs:</label>
        <input type="text" name="prijs" value="" id="fPrijs">
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
include ("inc/footer.php");
?>
