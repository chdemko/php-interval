<?php

/**
 * chdemko\Interval\IntervalTest class
 *
 * @author     Christophe Demko <chdemko@gmail.com>
 * @copyright  Copyright (C) 2014 Christophe Demko. All rights reserved.
 *
 * @license    http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.html The CeCILL B license
 *
 * This file is part of the php-interval package https://github.com/chdemko/php-interval
 */

// Declare chdemko\Interval namespace
namespace chdemko\Interval;

/**
 * Interval class test
 *
 * @package  Interval
 *
 * @since    1.0.0
 */
class IntervalTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests  Interval::fromJson
	 *
	 * @return  void
	 *
	 * @covers  chdemko\Interval\Interval::fromJson
	 * @covers  chdemko\Interval\Interval::__construct
	 * @covers  chdemko\Interval\Interval::__toString
	 *
	 * @since   1.0.0
	 */
	public function test_fromJson()
	{
		$interval = Interval::fromJson('{"inf":2,"infIncluded":true,"sup":3,"supIncluded":false}');

		$this->assertEquals(
			'[2,3[',
			(string) $interval
		);
	}

	/**
	 * Data provider for test_fromString
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	public function cases_fromString()
	{
		return [
			['[2,3[', false],
			[']2,3]', false],
			[']-INF,INF[', false],
			[']a,3[', true],
			['[-INF,INF[', true],
			[']-INF,INF]', true],
			['uncorrect', true],
		];
	}

	/**
	 * Tests  Interval::fromString
	 *
	 * @param   string   $string     Interval as a string
	 * @param   boolean  $exception  Exception flag
	 *
	 * @return  void
	 *
	 * @covers  chdemko\Interval\Interval::fromString
	 * @covers  chdemko\Interval\Interval::__construct
	 * @covers  chdemko\Interval\Interval::__toString
	 *
	 * @dataProvider  cases_fromString
	 *
	 * @since   1.0.0
	 */
	public function test_fromString($string, $exception)
	{
		if ($exception)
		{
			$this->setExpectedException('InvalidArgumentException');
		}

		$interval = Interval::fromString($string);

		$this->assertEquals(
			$string,
			(string) $interval
		);
	}

	/**
	 * Tests  Interval::universe
	 *
	 * @return  void
	 *
	 * @covers  chdemko\Interval\Interval::universe
	 * @covers  chdemko\Interval\Interval::__construct
	 * @covers  chdemko\Interval\Interval::__toString
	 *
	 * @since   1.0.0
	 */
	public function test_universe()
	{
		$interval = Interval::universe();

		$this->assertEquals(
			']-INF,INF[',
			(string) $interval
		);
	}

	/**
	 * Tests  Interval::closed
	 *
	 * @return  void
	 *
	 * @covers  chdemko\Interval\Interval::closed
	 * @covers  chdemko\Interval\Interval::__construct
	 * @covers  chdemko\Interval\Interval::__toString
	 *
	 * @since   1.0.0
	 */
	public function test_closed()
	{
		$interval = Interval::closed(2, 3);

		$this->assertEquals(
			'[2,3]',
			(string) $interval
		);
	}

	/**
	 * Tests  Interval::opened
	 *
	 * @return  void
	 *
	 * @covers  chdemko\Interval\Interval::opened
	 * @covers  chdemko\Interval\Interval::__construct
	 * @covers  chdemko\Interval\Interval::__toString
	 *
	 * @since   1.0.0
	 */
	public function test_opened()
	{
		$interval = Interval::opened(2, 3);

		$this->assertEquals(
			']2,3[',
			(string) $interval
		);
	}

	/**
	 * Tests  Interval::__get
	 *
	 * @return  void
	 *
	 * @covers  chdemko\Interval\Interval::__get
	 *
	 * @since   1.0.0
	 */
	public function test___get()
	{
		$interval = new Interval(2, true, 3, false);

		$this->assertEquals(
			2,
			$interval->inf
		);
		$this->assertEquals(
			true,
			$interval->infIncluded
		);
		$this->assertEquals(
			3,
			$interval->sup
		);
		$this->assertEquals(
			false,
			$interval->supIncluded
		);
		$this->setExpectedException('OutOfBoundsException');
		$unexisting = $interval->unexisting;
	}

	/**
	 * Tests  Interval::__set
	 *
	 * @return  void
	 *
	 * @covers  chdemko\Interval\Interval::__set
	 *
	 * @since   1.0.0
	 */
	public function test___set()
	{
		$interval = new Interval(2, true, 3, false);

		$interval->inf = 1;
		$this->assertEquals(
			1,
			$interval->inf
		);

		$interval->infIncluded = false;
		$this->assertEquals(
			false,
			$interval->infIncluded
		);

		$interval->sup = 4;
		$this->assertEquals(
			4,
			$interval->sup
		);

		$interval->supIncluded = true;
		$this->assertEquals(
			true,
			$interval->supIncluded
		);

		$interval->infIncluded = true;
		$interval->inf = - INF;
		$interval->sup = INF;
		$this->assertEquals(
			false,
			$interval->infIncluded
		);
		$this->assertEquals(
			false,
			$interval->supIncluded
		);

		$this->setExpectedException('OutOfBoundsException');
		$interval->unexisting = true;
	}

	/**
	 * Data provider for test_contains
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	public function cases_contains()
	{
		return [
			['[2,3[', 2, true],
			[']2,3[', 2, false],
			['[2,3[', 3, false],
			['[2,3]', 3, true],
		];
	}

	/**
	 * Tests  Interval::contains
	 *
	 * @param   string   $string     Interval as a string
	 * @param   float    $value      Value to be tested
	 * @param   boolean  $contained  Contained flag
	 *
	 * @return  void
	 *
	 * @covers  chdemko\Interval\Interval::contains
	 *
	 * @dataProvider  cases_contains
	 *
	 * @since   1.0.0
	 */
	public function test_contains($string, $value, $contained)
	{
		$interval = Interval::fromString($string);
		$this->assertEquals(
			$contained,
			$interval->contains($value)
		);
	}

	/**
	 * Tests  Interval::jsonSerialize
	 *
	 * @return  void
	 *
	 * @covers  chdemko\Interval\Interval::jsonSerialize
	 *
	 * @since   1.0.0
	 */
	public function test_jsonSerialize()
	{
		$interval = new Interval(2, true, 3, false);
		$this->assertEquals(
			'{"inf":2,"infIncluded":true,"sup":3,"supIncluded":false}',
			json_encode($interval)
		);
	}
}
