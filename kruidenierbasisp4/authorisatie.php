<?php
include("inc/header.php");

if ($_POST['submit']) {
    $inlognaam_check = htmlspecialchars($_POST['inlognaam'], ENT_QUOTES);
    $wachtwoord_check = htmlspecialchars($_POST['wachtwoord'], ENT_QUOTES);
    
    $inlognaam=isset($inlognaam_check) ? $inlognaam_check : '';
    $wachtwoord=isset($wachtwoord_check) ? $wachtwoord_check : '';
}
else {
    header('refresh: 1, index.php');
}
//selectquery opbouwen
$query = "SELECT m.id, m.inlognaam, m.wachtwoord, r.naam FROM medewerker m
            INNER JOIN rol r ON m.rol_id=r.id
            where m.inlognaam='" . $inlognaam . "' and m.wachtwoord='" . $wachtwoord . "';";
//$resultaat bepalen....
$result = mysqli_query($dbconn, $query);
//aantal records bepalen....
$aantal = mysqli_num_rows($result);
// echo "AANTAL: $aantal<br>";
if ($aantal == 1) {
    while ($row = mysqli_fetch_array($result)) {
        $rol = $row['naam'];
    }
    $_SESSION['inlognaam'] = $inlognaam;
    $_SESSION['wachtwoord'] = $wachtwoord;
    $_SESSION['rol'] = $rol;
    $_SESSION['ingelogd'] = true;
    header('refresh: 1; url=kassa.php');
    exit;
} else {
    echo '<div id="inlogfout">Helaas, uw inlognaam en/of wachtwoord corresponderen niet met onze gegevens. U wordt
            doorgestuurd...<br></div>';
    session_destroy();
    session_unset();
    //sluiten db-connectie
    mysqli_close($dbconn);
    header('refresh: 5; url=login.php');
    exit;
}
include("inc/footer.php");
?>