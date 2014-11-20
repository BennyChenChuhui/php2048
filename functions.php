<?php

/**
 * functions.php
 * Core Game Logic Functions
 */

/**
 * Takes an input array and removes all zeros and 
 */
function compressLine(array $line){
	$newLine = array_filter($line); // Remove all zeros from the array
	return array_pad($newLine, count($line), 0);
}

/**
 *
 */
function processLine($line){
	// Subtract 2 from count becasue the last item will not not to be processed
	for ($i=0; $i < count($line) - 2; $i++){
		if (!$line[$i]) continue; // Skip zeros
		if ($line[$i] == $line[$i+1]){
			// This tile is the same as the next tile
			$line[$i] = $line[$i] * 2;
			$line[$i+1] = 0;
		}
	}
	return $line;
}

/**
 * Returns a random value from an array
 */
function randomValue(array $input){
	$rKey = array_rand($input);
	return $input[$rKey];
}

/**
 * Adds a random value to a blank tile in the game board array
 */
function addRandomTile($gameBoard, array $values = array(2,4)){
	// Note: array_keys returns a random key not a value
	$emptyTiles = array_keys($gameBoard, 0); // Array of keys with a value of 0 from the $gameBoard array
	$randomKey = randomValue($emptyTiles); // Select a random empty key to use when adding a value to $gameBoard
	$gameBoard[$randomKey] = randomValue($values); // Set the empty tile to a value from the $values array
	return $gameBoard;
}

/**
 * Converts the game board array to array of arrays representing rows on the game board
 */
function getRows(array $gameBoard){
	$rows = array(); // Array to store rows
	$curRow = 0; // Holds the current row number
	for ($i=0;$i<count($gameBoard);$i++){
		$rows[$curRow][] = $gameBoard[$i];
		if ($i+1 % sqrt(count($gameBoard)) == 0) $curRow++; // Number is a multipul of the row length incoment row number
	}
	return $rows;
}

/**
 * Convert the game board array to array of arrays representing columns on the game board
 */
function getColumns(array $gameBoard){
	$columns = array(); // Array to hold columns arrays
	$curCol = 0; // Tracks the current columns for inserting values
	for ($i=0;$i<count($gameBoard);$i++){
		$columns[$curCol][] = $gameBoard[$i];
		if ($curCol+1 % sqrt(count($gameBoard)) == 0) $curCol = 0;
		$curCol++;
	}
	return $columns;
}

/**
 * Converts an array row arrays to a flat game board array
 */
function rowsToGameBoard(array $rows){
	$gameBoard = array();
	foreach($rows as $row){
		$gameBoard = array_merge($gameBoard, $row);
	}
	return $gameBoard;
}

/**
 * Converts an array of columns arrays to a flat game board array
 */
function columnsToGameBoard(array $columns){
	$vector = count($columns);
	$totalTiles = pow($vector, 2);
	$curCol = 0; // Index of current column in the $columns array
	$colPos = 0; // Current Index to pull from on each column
	$gameBoard = array(); // Game board to return
	for ($i=0;$i<$totalTiles;$i++){
		$gameBoard[] = $columns[$curCol][$colPos];
		$curCol++;
		if ($curCol+1 == $vector){
			$curCol = 0; // Reset back to first column
			$colPos++; // Move to the next row
		}
	}
	return $gameBoard;
}

/**
 * Chages the direction of the lines
 */
function reverseLines(array $lines){
	$reversed = array();
	foreach($lines as $line){
		$reversed[] = array_reverse($line);
	}
	return $reversed;
}

/**
 * This function will process each turn
 */
function gameTurn($gameBoard, $direction){
	/**
	 * Directions:
	 *  LEFT  - 1
	 *  UP    - 2
	 *  RIGHT - 3
	 *  DOWN  - 4
	 */
	$newGameBoard = array(); // Array to hold new game board
	$lines = array(); // Array of game board converted to rows or columns

	$vector = sqrt(count($gameBoard)); // Line Length
	$lineRows = ($direction & 1); // True if direction is a odd number, move was left or right
	$reversed = ($direction > 2); // True if the lines have been reversed
	
	if ($lineRows){ // Check if lines are rows or columns
		// Convert game board to rows
		$lines = getRows($gameBoard);
	}else{
		// Convert game board to columns
		$lines = getColumns($gameBoard);
	}

	if ($reversed) $lines = reverseLines($lines); // Reverse Lines Prior to Processing
	$lines = array_map('compressLine', $lines); // Slide Numbers before processing
	$lines = array_map('processLine', $lines);
	$lines = array_map('compressLine', $lines); // Re Slide to remove zeros in the middle
	if ($reversed) $lines = reverseLines($lines); // Return lines to origanal position
	// Convert lines array back to flat game board
	
	if ($lineRows){
		$newGameBoard = rowsToGameBoard($lines);
	}else{
		$newGameBoard = columnsToGameBoard($lines);
	}

	// Add a random value to a blank tile
	$newGameBoard = addRandomTile($newGameBoard);
	return $newGameBoard;
}

/**
 * Returns a new game board array
 */
function emptyGameBoard($vector){
	$boardSize = pow($vector, 2);
	return array_fill(0,$boardSize,0);
}