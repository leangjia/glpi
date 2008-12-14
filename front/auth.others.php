<?php


/*
 * @version $Id: setup.auth.php 7326 2008-09-23 10:18:17Z tsmr $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

$NEEDED_ITEMS = array (
	"setup",
	"auth",
	"ldap",
	"user"
);

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

checkRight("config", "w");
$config = new Config();

//Update CAS configuration
if (isset ($_POST["update"])) {
	$config->update($_POST);
	glpi_header($CFG_GLPI["root_doc"] . "/front/auth.others.php");
}

if (!isset ($_SESSION['glpi_authconfig'])){
	$_SESSION['glpi_authconfig'] = 1;
}
if (isset ($_GET['onglet'])){
	$_SESSION['glpi_authconfig'] = $_GET['onglet'];
}

if (!isset($_GET["ID"])){
	$_GET["ID"]="";	
}

if (!isset($_GET["next"])){
	$_GET["next"]="";	
}

if (!isset($_GET["preconfig"])){
	$_GET["preconfig"]="";	
}

commonHeader($LANG["title"][14], $_SERVER['PHP_SELF'],"config","extauth","others");

$tabs[3]=array('title'=>$LANG["common"][67],
'url'=>$CFG_GLPI['root_doc']."/ajax/auth.tabs.php",
'params'=>"target=".$_SERVER['PHP_SELF']."&ID=".$_GET["ID"]."&next=".$_GET["next"]."&preconfig=".$_GET["preconfig"]."&auth_tab=3");
			
echo "<div id='tabspanel' class='center-h'></div>";
createAjaxTabs('tabspanel','tabcontent',$tabs,$_SESSION['glpi_authconfig']);
echo "<div id='tabcontent'></div>";
echo "<script type='text/javascript'>loadDefaultTab();</script>";

commonFooter();
?>
