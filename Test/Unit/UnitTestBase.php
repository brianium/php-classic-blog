<?php
namespace Test\Unit;
abstract class UnitTestBase extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		date_default_timezone_set('America/New_York');
	}

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
		$prop = $this->getAccessibleProperty($object, $property);
		$prop->setValue($object, $value);
	}

	protected function loadFixture($fixtureType, $mustBe)
	{
		$fixture = new $fixtureType();

		if(!is_a($fixture, $mustBe))
			throw new \Exception("$fixtureType must be of type $mustBe");

		return $fixture;
	}

	/**
	 * Uses reflection to fetch a value from an object
	 * Makes private/protected properties accessible
	 * @return mixed the value of the property
	 */
	protected function getObjectValue($object, $property)
	{
		$prop = $this->getAccessibleProperty($object, $property);
		return $prop->getValue($object);
	}

	private function getAccessibleProperty($object, $property)
	{
		$refl = new \ReflectionObject($object);
		$prop = $refl->getProperty($property);
		$prop->setAccessible(true);
		return $prop;
	}
}