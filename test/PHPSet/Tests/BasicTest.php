<?php

class PHPSet_Tests_BasicTest extends PHPUnit_Framework_TestCase
{
	
	function dataProviderForTestBasic()
	{
		$different_values = array(
			NULL, TRUE, FALSE,
			0, 1, -1,
			0., 1.00000000001, -1., 1.,
			"", " ", "1", "-1", str_repeat( "1", 10000 ), str_repeat( "1", 10001 ),
			array(), array( array() ), array( 1 ), array( 0 ), array( "key" => "value" ),
			new stdClass(), new stdClass(), (object)array(), (object)array( 1 ),
			fopen( "php://memory", "w" ), fopen( "php://memory", "w" ), socket_create_listen( 0 ), socket_create_listen( 0 )
		);
		$tests = array();
		foreach( $different_values as $i => $value1 ) {
			foreach( $different_values as $j => $value2 ) {
				if( $i < $j ) {
					$tests[] = array( $value1, $value2 );				
				}
			}
		}
		return $tests;
	}
	
	/**
	 * @dataProvider dataProviderForTestBasic
	 */
	
	function testBasic( $value1, $value2 )
	{
		$set = new PHPSet();
		
		$this->assertFalse( $set->has( $value1 ) );
		$this->assertFalse( $set->has( $value2 ) );
		
		$set->add( $value1 );
		$this->assertTrue( $set->has( $value1 ) );
		$this->assertFalse( $set->has( $value2 ) );
		
		$set->add( $value2 );
		$this->assertTrue( $set->has( $value1 ) );
		$this->assertTrue( $set->has( $value2 ) );
		
		$set->add( $value1 );
		$this->assertTrue( $set->has( $value1 ) );
		$this->assertTrue( $set->has( $value2 ) );
		$set->add( $value2 );
		$this->assertTrue( $set->has( $value1 ) );
		$this->assertTrue( $set->has( $value2 ) );
		
		$set->remove( $value2 );
		$this->assertTrue( $set->has( $value1 ) );
		$this->assertFalse( $set->has( $value2 ) );
		
		$set->remove( $value1 );
		$this->assertFalse( $set->has( $value1 ) );
		$this->assertFalse( $set->has( $value2 ) );
		
		$set->remove( $value2 );
		$this->assertFalse( $set->has( $value1 ) );
		$this->assertFalse( $set->has( $value2 ) );
		$set->remove( $value1 );
		$this->assertFalse( $set->has( $value1 ) );
		$this->assertFalse( $set->has( $value2 ) );
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage unknown type
	 */
	
	function testAddUnknown()
	{
		$h = fopen( "php://memory", "rw" );
		fclose( $h );
		$set = new PHPSet();
		$set->add( $h );
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage unknown type
	 */
	
	function testRemoveUnknown()
	{
		$h = fopen( "php://memory", "rw" );
		fclose( $h );
		$set = new PHPSet();
		$set->remove( $h );
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage unknown type
	 */
	
	function testHasUnknown()
	{
		$h = fopen( "php://memory", "rw" );
		fclose( $h );
		$set = new PHPSet();
		$set->has( $h );
	}
	
	function testAddSet()
	{
		$set1 = new PHPSet();
		$set1->add( 1 );
		$set2 = new PHPSet();
		$set2->add( 2 );
		$set3 = new PHPSet();
		$set3->add( 1 );
		
		$set1->addSet( $set2 );
		$this->assertTrue( $set1->has( 1 ) );
		$this->assertTrue( $set1->has( 2 ) );
		$set1->removeSet( $set3 );
		$this->assertFalse( $set1->has( 1 ) );
		$this->assertTrue( $set1->has( 2 ) );
	}
	
	function testConstructor()
	{
		$set = new PHPSet( 1, 2, 3, 1 );
		$this->assertTrue( $set->has( 1 ) );
		$this->assertTrue( $set->has( 2 ) );
		$this->assertTrue( $set->has( 3 ) );
	}
	
}

?>