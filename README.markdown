# PHPSet: when you just want a set.

##Is PHPSet for you?

  * Sick of doing `array( "key1" => NULL, "key2" => NULL, ... )`?
  * Annoyed with array indexes being typecast without your permission?
  * Want to store objects, resources or arrays in a set?
  * Love O(1) lookups?

##PHPSet is for you!

	$set = new PHPSet( 1, 1., "1", TRUE, NULL ); //set are type sensitive
    $set->has( 1 ); // true
	$set->has( 1. ); // true
	$set->remove( 1 );
	$set->has( 1 ); // false
	$set->has( 1. ); // true!