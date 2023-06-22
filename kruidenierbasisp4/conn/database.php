<?php
//initialiseren
define('HOST', 'localhost');
define('DATABASE', 'kruidenierp4');
define('USER', 'root');
define('PASSWORD','');
$dbconn='';
//connectie maken
try {

    $dbconn=mysqli_connect(HOST, USER, PASSWORD, DATABASE);
    //voor ondersteuning van diacritische tekens gebruiken we onderstaande functie.
    mysqli_set_charset($dbconn, 'utf8');
}
catch (exception $e) {
    $dbconn=$e;
}
//functie om de database te sluiten
function fnCloseDb($conn){
    if (!$conn==false)
    {
        mysqli_close($conn)
        or die('Sluiten MySQL-db niet gelukt...');
    }
} //end of fnCloseDb

?>