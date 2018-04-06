<?php
/**
 * Created by PhpStorm.
 * User: lamur
 * Date: 23/03/2018
 * Time: 15:36
 */


function connexion() {
    include("params.inc.php");
    $options = array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);
    $connexion = new PDO('mysql:host='.$host.';dbname='.$dbname.';', $user, $password,$options);
    if (!$connexion) {
        die('Erreur de connexion à la base : [');
    }

    return $connexion;
}