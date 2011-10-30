<?php

class PHPSet
{
	
	private $_array_i = 0;
	private $_array = array();
	
	private $_true_key = NULL;
	private $_false_key = NULL;

	private $_null_key = NULL;
	
	private $_int_set = array();
	private $_float_set = array();
	private $_string_set = array();
	private $_array_set = array();
	private $_object_set = array();
	private $_resource_set = array();
	
	function __construct()
	{
		foreach( func_get_args() as $value ) {
			$this->add( $value );
		}
	}
	
	function add( $value )
	{
		if( $this->has( $value ) ) {
			return;
		}		
		if( is_bool( $value ) ) {
			if( $value ) {
				$this->_true_key = $this->_array_i;
			} else {
				$this->_false_key = $this->_array_i;
			}
		} else if( is_null( $value ) ) {
			$this->_null_key = $this->_array_i;
		} else if( is_int( $value ) ) {
			$this->_int_set[$value] = $this->_array_i;
		} else if( is_float( $value ) ) {
			$this->_float_set[(string)$value] = $this->_array_i;
		} else if( is_string( $value ) ) {
			$this->_string_set[$value] = $this->_array_i;
		} else if( is_array( $value ) ) {
			$this->_array_set[serialize( $value )] = $this->_array_i;
		} else if( is_object( $value ) ) {
			$this->_array_set[spl_object_hash( $value )] = $this->_array_i;
		} else if( is_resource( $value ) ) {
			$this->_resource_set[(int)$value] = $this->_array_i;
		} else throw new Exception( "This case should be handled in the has() call." );
		
		$this->_array[$this->_array_i++] = $value;
	}
	
	function remove( $value )
	{
		if( ! $this->has( $value ) ) {
			return;
		}
		if( is_bool( $value ) ) {
			if( $value ) {
				unset( $this->_array[$this->_true_key] );
				$this->_true_key = NULL;
			} else {
				unset( $this->_array[$this->_false_key] );
				$this->_false_key = NULL;
			}
		} else if( is_null( $value ) ) {
			unset( $this->_array[$this->_null_key] );
			$this->_null_key = NULL;
		} else if( is_int( $value ) ) {
			unset( $this->_array[$this->_int_set[$value]] );
			unset( $this->_int_set[$value] );
		} else if( is_float( $value ) ) {
			unset( $this->_array[$this->_float_set[(string)$value]] );
			unset( $this->_float_set[(string)$value] );
		} else if( is_string( $value ) ) {
			unset( $this->_array[$this->_string_set[$value]] );
			unset( $this->_string_set[$value] );
		} else if( is_array( $value ) ) {
			$serialized_value = serialize( $value );
			unset( $this->_array[$this->_array_set[$serialized_value]] );
			unset( $this->_array_set[$serialized_value] );
		} else if( is_object( $value ) ) {
			$object_hash = spl_object_hash( $value );
			unset( $this->_array[$this->_array_set[$object_hash]] );
			unset( $this->_array_set[$object_hash] );
		} else if( is_resource( $value ) ) {
			unset( $this->_array[$this->_resource_set[(int)$value]] );
			unset( $this->_resource_set[(int)$value] );
		} else throw new Exception( "This case should be handled in the has() call." );
	}
	
	function has( $value )
	{
		if( is_bool( $value ) ) {
			if( $value ) {
				return ! is_null( $this->_true_key );
			} else {
				return ! is_null( $this->_false_key );
			}
		} else if( is_null( $value ) ) {
			return ! is_null( $this->_null_key );
		} else if( is_int( $value ) ) {
			return isset( $this->_int_set[$value] );
		} else if( is_float( $value ) ) {
			return isset( $this->_float_set[(string)$value] );
		} else if( is_string( $value ) ) {
			return isset( $this->_string_set[$value] );
		} else if( is_array( $value ) ) {
			return isset( $this->_array_set[serialize( $value )] );
		} else if( is_object( $value ) ) {
			return isset( $this->_array_set[spl_object_hash( $value )] );
		} else if( is_resource( $value ) ) {
			return isset( $this->_resource_set[(int)$value] );
		} else {
			throw new InvalidArgumentException( "unknown type" );
		}
	}
	
	function addSet( PHPSet $set )
	{
		foreach( $set->getArray() as $value ) {
			$this->add( $value );
		}
	}
	
	function removeSet( PHPSet $set )
	{
		foreach( $set->getArray() as $value ) {
			$this->remove( $value );
		}
	}
	
	function getArray()
	{
		return array_values( $this->_array );
	}
	
}

?>