<?php	
include_once("ConnectionManager/ConnectionManagerFactory.php");
include_once("DBTypes.php");

 /**
  * Get the corresponding ConnectionManager object w.r.t to database specified in DB_TYPE constant(DBTypes.php).
  * Factory Method.
  */
$objConnectionClass = ConnectionManagerFactory::getInstanceOf(DB_TYPE);

/** 
 * Invoke the doConnection object to make a connection to the specified database
 */
$objConnectionClass->doConnection();

/**
 * Get the connectionHandle (Base Class Method).
 */
$conn = $objConnectionClass->getConnectionHandle();

/**
 * Select the database.
 */
$objConnectionClass->selectDatabase();
unset($objConnectionClass);