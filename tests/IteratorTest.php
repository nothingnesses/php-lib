<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

use Eris\Generator;
use PHPUnit\Framework\TestCase;
use Nothingnesses\Lib\Classes as C;

/**
 * @todo Add test for `nth_back`.
 */
class IteratorTest extends TestCase {
	use Eris\TestTrait;

	public function test_all(): void {
		$is_true = fn (bool $a): bool => $a === true;
		$this
			->forAll(
				Generator\seq(Generator\bool())
			)
			->then(function ($array) use ($is_true) {
				$this->assertEquals(
					array_reduce(
						$array,
						fn ($carry, $item) => $carry && $is_true($item),
						true
					),
					C\Iterator\ArrayIterator::new($array)
						->map(fn (C\Pair $pair): bool => $pair->second)
						->all($is_true)
				);
			});
	}

	public function test_any(): void {
		$this
			->forAll(
				Generator\seq(Generator\int()),
				Generator\int()
			)
			->then(function ($ints, $int) {
				$this->assertEquals(
					in_array($int, $ints),
					C\Iterator\ArrayIterator::new($ints)
						->map(fn ($item) => $item->second)
						->any(fn ($item) => $item === $int)
				);
			});
	}

	public function test_chain(): void {
		$this
			->forAll(
				Generator\seq(Generator\bool()),
				Generator\seq(Generator\bool())
			)->then(function ($first, $second) {
				$this->assertEquals(
					array_merge($first, $second),
					C\Iterator\ArrayIterator::new($first)
						->chain(C\Iterator\ArrayIterator::new($second))
						->map(fn ($item) => $item->second)
						->to_array()
				);
			});
	}

	public function test_filter(): void {
		$this
			->forAll(Generator\seq(Generator\int()))
			->then(function ($ints) {
				$filter = fn (int $int): bool => $int % 2 === 0;
				$this->assertEquals(
					array_values(array_filter($ints, $filter)),
					C\Iterator\ArrayIterator::new($ints)
						->map(fn ($item) => $item->second)
						->filter($filter)
						->to_array()
				);
			});
	}

	public function test_fold_left(): void {
		$this
			->forAll(Generator\seq(Generator\int()))
			->then(function ($ints) {
				$this->assertEquals(
					array_reduce($ints, fn ($carry, $item) => $carry + $item, 0),
					C\Iterator\ArrayIterator::new($ints)
						->map(fn ($args) => $args->second)
						->fold_left(function (int $carry): Closure {
							return function ($item) use ($carry) {
								return $carry + $item;
							};
						})(0)
				);
			});
	}

	public function test_map(): void {
		$this
			->forAll(Generator\seq(Generator\int()))
			->then(function ($ints) {
				$mapper = fn (int $int): int => $int * 2;
				$this->assertEquals(
					array_map($mapper, $ints),
					C\Iterator\ArrayIterator::new($ints)
						->map(fn ($item) => $item->second)
						->map($mapper)
						->to_array()
				);
			});
	}

	public function test_nth(): void {
		$this
			->forAll(
				Generator\seq(Generator\int()),
				Generator\choose(0, 200)
			)
			->withMaxSize(200)
			->then(function ($ints, $int) {
				switch (true) {
					case empty($ints) && gettype($ints) === "array":
						$this->assertEquals(
							C\Maybe::none(),
							C\Iterator\ArrayIterator::new($ints)
								->nth($int)
						);
						break;
					case count($ints) - 1 >= $int && gettype($ints) === "array":
						$this->assertEquals(
							C\Maybe::some($ints[$int]),
							C\Iterator\ArrayIterator::new($ints)
								->map(fn (C\Pair $pair) => $pair->second)
								->nth($int)
						);
						break;
					default:
						// If the int is out of range of the array, skip the test
						$this->assertTrue(true);
						break;
				}
			});
	}

	public function test_once(): void {
		$this
			->forAll(Generator\int())
			->then(function (int $int) {
				$this->assertEquals(
					[$int],
					C\Iterator\DoubleEnded\Once::new($int)
						->to_array()
				);
			});
	}

	public function test_reverse(): void {
		$this
			->forAll(Generator\seq(Generator\bool()))
			->then(function ($array) {
				$this->assertEquals(
					array_reverse($array),
					C\Iterator\ArrayIterator::new($array)
						->reverse()
						->map(fn ($item) => $item->second)
						->to_array()
				);
			});
	}

	public function test_skip_while(): void {
		$this
			->forAll(Generator\seq(Generator\bool()))
			->then(function ($array) {
				$predicate = fn (bool $bool): bool => $bool === true;
				$implementation = function (array $array, callable $predicate): array {
					$output = [];
					$tripwire = true;
					foreach ($array as $value) {
						if ($tripwire && !$predicate($value)) {
							$tripwire = false;
						}
						array_push($output, $value);
					}
					return $output;
				};
				$this->assertEquals(
					$implementation($array, $predicate),
					C\Iterator\ArrayIterator::new($array)
						->map(fn ($item) => $item->second)
						->skip_while($predicate)
						->to_array()
				);
			});
	}

	public function test_step_by(): void {
		$this
			->forAll(
				Generator\seq(Generator\int()),
				Generator\choose(1, 9)
			)
			->then(function ($ints, $step) {
				$implementation = function (array $array, int $step): array {
					$output = [];
					$count = count($array);
					for ($i = 0; $i < $count; $i += $step) {
						array_push($output, $array[$i]);
					}
					return $output;
				};
				$a = $implementation($ints, $step);
				$b = C\Iterator\ArrayIterator::new($ints)
					->map(fn ($item) => $item->second)
					->step_by($step)
					->to_array();
				$this->assertEquals($a, $b);
			});
	}

	public function test_take_while(): void {
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
					C\Iterator\ArrayIterator::new($array)
						->map(fn ($item) => $item->second)
						->take_while($predicate)
						->to_array()
				);
			});
	}

	public function test_to_array(): void {
		$this
			->forAll(Generator\seq(Generator\bool()))
			->then(function ($array) {
				$this->assertEquals(
					$array,
					C\Iterator\ArrayIterator::new($array)
						->map(fn ($item) => $item->second)
						->to_array()
				);
			});
	}
}
