<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

use Eris\Generator;
use PHPUnit\Framework\TestCase;
use Nothingnesses\Lib\Classes\ArrayIterator;

class ArrayIteratorTest extends TestCase {
	use Eris\TestTrait;

	public function test_any() {
		$this
			->forAll(
				Generator\seq(Generator\int()),
				Generator\int()
			)
			->then(function ($ints, $int) {
				$this->assertEquals(
					in_array($int, $ints),
					ArrayIterator::new($ints)
						->map(fn ($item) => $item[1])
						->any(fn ($item) => $item === $int)
				);
			});
	}

	public function test_append() {
		$this
			->forAll(
				Generator\seq(Generator\bool()),
				Generator\seq(Generator\bool())
			)->then(function ($first, $second) {
				$this->assertEquals(
					array_merge($first, $second),
					ArrayIterator::new($first)
						->append(ArrayIterator::new($second))
						->map(fn ($item) => $item[1])
						->to_array()
				);
			});
	}

	public function test_filter() {
		$this
			->forAll(Generator\seq(Generator\int()))
			->then(function ($ints) {
				$filter = fn (int $int): bool => $int % 2 === 0;
				$this->assertEquals(
					array_values(array_filter($ints, $filter)),
					ArrayIterator::new($ints)
						->map(fn ($item) => $item[1])
						->filter($filter)
						->to_array()
				);
			});
	}

	public function test_fold_left() {
		$this
			->forAll(Generator\seq(Generator\int()))
			->then(function ($ints) {
				$this->assertEquals(
					array_reduce($ints, fn ($carry, $item) => $carry + $item, 0),
					ArrayIterator::new($ints)
						->map(fn ($args) => $args[1])
						->fold_left(function (int $carry): Closure {
							return function ($item) use ($carry) {
								return $carry + $item;
							};
						})(0)
				);
			});
	}

	public function test_map() {
		$this
			->forAll(Generator\seq(Generator\int()))
			->then(function ($ints) {
				$mapper = fn (int $int): int => $int * 2;
				$this->assertEquals(
					array_map($mapper, $ints),
					ArrayIterator::new($ints)
						->map(fn ($item) => $item[1])
						->map($mapper)
						->to_array()
				);
			});
	}

	public function test_reverse() {
		$this
			->forAll(Generator\seq(Generator\bool()))
			->then(function ($array) {
				$this->assertEquals(
					array_reverse($array),
					ArrayIterator::new($array)
						->reverse()
						->map(fn ($item) => $item[1])
						->to_array()
				);
			});
	}

	public function test_take_while() {
		$this
			->forAll(Generator\seq(Generator\bool()))
			->then(function ($array) {
				$predicate = fn (bool $bool): bool => $bool === true;
				$implementation = function (array $array) use ($predicate) {
					if (empty($array)) return [];
					$length = count($array);
					$index = 0;
					$output = [];
					while ($index < $length && $predicate($array[$index])) {
						array_push($output, $array[$index]);
						++$index;
					}
					return $output;
				};
				$this->assertEquals(
					$implementation($array),
					ArrayIterator::new($array)
						->map(fn ($item) => $item[1])
						->take_while($predicate)
						->to_array()
				);
			});
	}

	public function test_to_array() {
		$this
			->forAll(Generator\seq(Generator\bool()))
			->then(function ($array) {
				$this->assertEquals(
					$array,
					ArrayIterator::new($array)
						->map(fn ($item) => $item[1])
						->to_array()
				);
			});
	}
}
