<?php

/**
 * test.php
 * Testing some of the core game functions
 */

 require 'functions.php';

echo "<h3>compressLine</h3>";
$compressLineArgs = array(0,0,4,4);
print_r($compressLineArgs);
$compressLineRes = compressLine($compressLineArgs);
echo "<br />";
print_r($compressLineRes);
echo "<hr><h3>compressLine</h3>";
print_r(processLine($compressLineRes));

echo "<hr><h3></h3>";