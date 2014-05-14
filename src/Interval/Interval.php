<?php

/**
 * chdemko\Interval\Interval class
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
 * Interval on the real line
 *
 * @package  Interval
 * 
 * @since    1.0.0
 *
 * @todo     Declare properties
 */
class Interval implements \JsonSerializable
{
	/**
	 * @var     number  inf mark
	 *
	 * @since   1.0.0
	 */
	private $inf;

	/**
	 * @var     boolean  included inf mark flag
	 *
	 * @since   1.0.0
	 */
	private $infIncluded;

	/**
	 * @var     number  sup mark
	 *
	 * @since   1.0.0
	 */
	private $sup;

	/**
	 * @var     boolean  included sup mark flag
	 *
	 * @since   1.0.0
	 */
	private $supIncluded;

	/**
	 * Create a new interval
	 *
	 * @param   float    $inf          The inf mark
	 * @param   boolean  $infIncluded  The included inf mark flag
	 * @param   float    $sup          The sup mark
	 * @param   boolean  $supIncluded  The included sup mark flag
	 *
	 * @since   1.0.0
	 */
	public function __construct($inf, $infIncluded, $sup, $supIncluded)
	{
		$this->inf = (float) $inf;
		$this->sup = (float) $sup;
		$this->infIncluded = is_infinite($this->inf) ? false : (bool) $infIncluded;
		$this->supIncluded = is_infinite($this->sup) ? false : (bool) $supIncluded;
	}

	/**
	 * Magic get method
	 *
	 * @param   string  $property  The property
	 *
	 * @throws  \OutOfBoundsException  If the property does not exist
	 *
	 * @return  mixed  The value associated to the property
	 *
	 * @since   1.0.0
	 */
	public function __get($property)
	{
		switch ($property)
		{
			case 'inf':
				return $this->inf;
			break;
			case 'infIncluded':
				return $this->infIncluded;
			break;
			case 'sup':
				return $this->sup;
			break;
			case 'supIncluded':
				return $this->supIncluded;
			break;
			default:
				throw new \OutOfBoundsException('Undefined property');
			break;
		}
	}

	/**
	 * Magic set method
	 *
	 * @param   string  $property  The property
	 * @param   mixed   $value     The new value
	 *
	 * @throws  \OutOfBoundsException  If the property does not exist
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function __set($property, $value)
	{
		switch ($property)
		{
			case 'inf':
				$value = (float) $value;

				if (is_infinite($value))
				{
					$this->infIncluded = false;
				}

				$this->inf = $value;
			break;
			case 'infIncluded':
				$this->infIncluded = (bool) $value;
			break;
			case 'sup':
				$value = (float) $value;

				if (is_infinite($value))
				{
					$this->supIncluded = false;
				}

				$this->sup = $value;
			break;
			case 'supIncluded':
				$this->supIncluded = (bool) $value;
			break;
			default:
				throw new \OutOfBoundsException('Undefined property');
			break;
		}
	}

	/**
	 * Create a new Interval from json
	 *
	 * @param   string  $json  A json encoded value
	 *
	 * @return  Interval  A new Interval
	 *
	 * @since   1.0.0
	 */
	public static function fromJson($json)
	{
		$values = json_decode($json);

		return new Interval(
			isset($values->inf) ? $values->inf : - INF,
			isset($values->infIncluded) ? $values->infIncluded : true,
			isset($values->sup) ? $values->sup : INF,
			isset($values->supIncluded) ? $values->supIncluded : true
		);
	}

	/**
	 * Create a new Interval from string
	 *
	 * @param   string  $string  An interval as a string
	 *
	 * @return  Interval  A new Interval
	 *
	 * @since   1.0.0
	 */
	public static function fromString($string)
	{
		if (preg_match('/(\[|\])(.*),(.*)(\[|\])/', $string, $matches))
		{
			if ($matches[2] == '-INF')
			{
				if ($matches[1] == ']')
				{
					$inf = - INF;
				}
				else
				{
					throw new \InvalidArgumentException('Argument must be a correct interval string');
				}
			}
			else
			{
				$inf = filter_var($matches[2], FILTER_VALIDATE_FLOAT);
			}

			if ($matches[3] == 'INF')
			{
				if ($matches[4] == '[')
				{
					$sup = INF;
				}
				else
				{
					throw new \InvalidArgumentException('Argument must be a correct interval string');
				}
			}
			else
			{
				$sup = filter_var($matches[3], FILTER_VALIDATE_FLOAT);
			}

			if ($inf && $sup)
			{
				return new Interval($inf, $matches[1] == '[', $sup, $matches[4] == ']');
			}
			else
			{
				throw new \InvalidArgumentException('Argument must be a correct interval string');
			}
		}
		else
		{
			throw new \InvalidArgumentException('Argument must be a correct interval string');
		}
	}

	/**
	 * Create a new Interval containing all the real line
	 *
	 * @return  Interval  A new Interval
	 *
	 * @since   1.0.0
	 */
	public static function universe()
	{
		return new Interval(-INF, false, INF, false);
	}

	/**
	 * Create a new closed Interval
	 *
	 * @param   float  $inf  The inf mark
	 * @param   float  $sup  The sup mark
	 *
	 * @return  Interval  A new Interval
	 *
	 * @since   1.0.0
	 */
	public static function closed($inf, $sup)
	{
		return new Interval($inf, true, $sup, true);
	}

	/**
	 * Create a new opened Interval
	 *
	 * @param   float  $inf  The inf mark
	 * @param   float  $sup  The sup mark
	 *
	 * @return  Interval  A new Interval
	 *
	 * @since   1.0.0
	 */
	public static function opened($inf, $sup)
	{
		return new Interval($inf, false, $sup, false);
	}

	/**
	 * Test if a value is contained in the interval
	 *
	 * @param   float  $value  Value to be tested
	 *
	 * @return  boolean  Membership of value
	 *
	 * @since   1.0.0
	 */
	public function contains($value)
	{
		return
			($this->infIncluded ? ($value >= $this->inf) : ($value > $this->inf)) &&
			($this->supIncluded ? ($value <= $this->sup) : ($value < $this->sup));
	}

	/**
	 * Serialize the object
	 *
	 * @return  array  Array of values
	 *
	 * @since   1.0.0
	 */
	public function jsonSerialize()
	{
		$array = [];

		if (!is_infinite($this->inf))
		{
			$array['inf'] = $this->inf;
			$array['infIncluded'] = $this->infIncluded;
		}

		if (!is_infinite($this->sup))
		{
			$array['sup'] = $this->sup;
			$array['supIncluded'] = $this->supIncluded;
		}

		return $array;
	}

	/**
	 * Convert the object to a string
	 *
	 * @return  string  String representation of this object
	 *
	 * @since   1.0.0
	 */
	public function __toString()
	{
		return
			($this->infIncluded ? '[' : ']') . $this->inf . ',' .
			$this->sup . ($this->supIncluded ? ']' : '[');
	}
}
