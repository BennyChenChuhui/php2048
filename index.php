<?php

/**
 * index.php
 * Main game page
 */

require 'functions.php';

$gb = emptyGameBoard(4);

for($i=0;$i<10;$i++){
	$gb = addRandomTile($gb);	
}

 print_r($gb);