<?php
namespace Test\Unit;
abstract class UnitTestBase extends \PHPUnit_Framework_TestCase
{
	/**
	 * Uses reflection to set a value - useful for testing get/set methods
	 * without having to test each of those implementations. Makes private/protected
	 * properties accessible
	 * @param mixed $object the object to set the value on
	 * @param string $property the name of the property to update
	 * @param mixed $value the value to set the property to
	 */
	protected function setObjectValue($object, $property, $value)
	{
		$refl = new \ReflectionObject($object);
		$prop = $refl->getProperty($property);
		$prop->setAccessible(true);
		$prop->setValue($object, $value);
	}
}