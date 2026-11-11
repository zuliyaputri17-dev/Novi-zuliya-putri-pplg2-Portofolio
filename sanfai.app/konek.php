<?php
function conn (){
    $conn = new mysqli('localhost', 'root', '', 'database');
    return $conn;
}
?>
