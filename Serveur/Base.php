<?php
require "Add/define.php";
$base = mysql_connect ($SQL_Cdw_serveur, $SQL_Cdw_login, $SQL_Cdw_pass);
mysql_select_db ($SQL_Cdw_name, $base);
$sql = 'CREATE TABLE operator
(
ID_operator int NOT NULL AUTO_INCREMENT,
login_operator text NOT NULL,
email_operator text DEFAULT NULL,
firstName_operator text DEFAULT NULL,
lastName_operator text DEFAULT NULL,
type_operator text DEFAULT NULL,
pass_operator text NOT NULL,
PRIMARY KEY (ID_operator)
);';
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
$sql = 'INSERT INTO operator (login_operator,type_operator,pass_operator)
VALUES ("admin","admin",md5("admin"));';
$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
mysql_close();
?>