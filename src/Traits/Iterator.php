<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Traits;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;

/**
 * @template A
 */
trait Iterator {
	/**
	 * Checks if all the items yielded by the iterator satisfy a predicate
	 * function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if all match a condition.
	 * @return bool `true` if all items match the condition, `false` otherwise.
	 */
	public function all(callable $fn): bool {
		$output = true;
		for (
			$current = $this->next();
			$current->is_some() && $output;
			$current = $this->next()
		) {
			$current->map(function ($item) use ($fn, &$output): void {
				$output = $fn($item);
			});
		}
		return $output;
	}

	/**
	 * Checks if any of the items yielded by the iterator satisfy a predicate
	 * function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if any match a condition.
	 * @return bool `true` if an item matches the condition, `false` otherwise.
	 */
	public function any(callable $fn): bool {
		return $this
			->find($fn)
			->is_some();
	}

	/**
	 * Returns an iterator that yields items from the current iterator, then from
	 * another iterator.
	 * 
	 * @param I\Iterator<A>|I\DoubleEndedIterator<A> $second The other iterator to concatenate with the current one.
	 * @return I\Iterator<A>|I\DoubleEndedIterator<A>
	 */
	public function chain(I\Iterator|I\DoubleEndedIterator $second): I\Iterator|I\DoubleEndedIterator {
		return match (true) {
			($this instanceof I\DoubleEndedIterator && $second instanceof I\DoubleEndedIterator) => C\Iterator\DoubleEnded\Chain::new($this)($second),
			default => C\Iterator\Chain::new($this)($second)
		};
	}

	/**
	 * Returns an iterator that yields items that satisfy a predicate function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if they match a condition.
	 * @return I\Iterator<A>|I\DoubleEndedIterator<A>
	 */
	public function filter(callable $fn): I\Iterator|I\DoubleEndedIterator {
		return match (true) {
			($this instanceof I\DoubleEndedIterator) => C\Iterator\DoubleEnded\Filter::new($fn)($this),
			default => C\Iterator\Filter::new($fn)($this)
		};
	}

	/**
	 * Returns the first item in the iterator that satisfies a predicate
	 * function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if they match a condition.
	 * @return C\Maybe<A> The `some` variant containing the first item that matches the condition if it exists, or the `none` variant.
	 */
	public function find(callable $fn): C\Maybe {
		for (
			$current = $this->next();
			$current->is_some();
			$current = $this->next()
		) {
			$result = $current->bind(fn ($item) => $fn($item)
				? C\Maybe::some($item)
				: C\Maybe::none());
			if ($result->is_some()) {
				return $result;
			}
		}
		return C\Maybe::none();
	}

	/**
	 * Left-associative fold operation.
	 * Applies a binary function to an initial accumulator value and the first
	 * item of the iterator, then uses the result as the new accumulator value.
	 * The process is then repeated for each of the remaining items of the
	 * iterator.
	 *
	 * @template Z
	 * @param callable(Z $carry): (callable(A $item): Z) $fn The function to apply to each item yielded.
	 * @return \Closure(Z $initial): Z A closure that accepts the initial value and returns the reduced value.
	 */
	public function fold_left(callable $fn): \Closure {
		/**
		 * @param Z $initial The initial value to use.
		 * @return Z
		 */
		return function ($initial) use ($fn) {
			$carry = $initial;
			$this->for_each(function ($item) use ($fn, &$carry) {
				$carry = $fn($carry)($item);
			});
			return $carry;
		};
	}

	/**
	 * Applies a function to each item yielded by the iterator.
	 *
	 * @param callable(A): void $fn The function to apply to each item yielded.
	 */
	public function for_each(callable $fn): void {
		for (
			$current = $this->next();
			$current->is_some();
		) {
			$current->map(function ($item) use ($fn): void {
				$fn($item);
			});
			$current = $this->next();
		}
	}

	/**
	 * Returns an iterator that yields items that are the result of applying a
	 * function to items yielded by the current iterator.
	 *
	 * @template B
	 * @param callable(A): B $fn The function to apply to each item yielded.
	 * @return I\Iterator<B>|I\DoubleEndedIterator<B>
	 */
	public function map(callable $fn): I\Iterator|I\DoubleEndedIterator {
		return match (true) {
			($this instanceof I\DoubleEndedIterator) => C\Iterator\DoubleEnded\Map::new($fn)($this),
			default => C\Iterator\Map::new($fn)($this)
		};
	}

	/**
	 * Returns the next item from the iterator.
	 *
	 * @return C\Maybe<A> The `some` variant containing the next item if it exists, or the `none` variant.
	 */
	abstract public function next(): C\Maybe;

	/**
	 * Returns the nth item wrapped in `some` if it exists, or `none` if not.
	 *
	 * @param int $index The integer to index into the iterator with.
	 * @return C\Maybe<A>
	 */
	public function nth(int $index): C\Maybe {
		return $this->skip($index)->next();
	}

	/**
	 * Returns an iterator that yields items after skipping the specified number
	 * of items.
	 *
	 * @param int $n The number of items to skip.
	 * @return I\Iterator<A>|I\DoubleEndedIterator<A>
	 */
	public function skip(int $n): I\Iterator|I\DoubleEndedIterator {
		return match (true) {
			($this instanceof I\DoubleEndedIterator) => C\Iterator\DoubleEnded\Skip::new($n)($this),
			default => C\Iterator\Skip::new($n)($this)
		};
	}

	/**
	 * Returns an iterator that yields items after skipping items that satisfy a
	 * predicate function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if they match a condition.
	 * @return I\Iterator<A>
	 */
	public function skip_while(callable $fn): I\Iterator {
		return C\Iterator\SkipWhile::new($fn)($this);
	}

	/**
	 * Returns an iterator that yields the first item, then every nth item.
	 *
	 * @param int $n Number of items to skip on every step.
	 * @return I\Iterator<A>|I\DoubleEndedIterator<A>
	 */
	public function step_by(int $n): I\Iterator|I\DoubleEndedIterator {
		return match (true) {
			($this instanceof I\DoubleEndedIterator) => C\Iterator\DoubleEnded\StepBy::new($n)($this),
			default => C\Iterator\StepBy::new($n)($this)
		};
	}

	/**
	 * Returns an iterator that yields items as long as they satisfy a predicate function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if they match a condition.
	 * @return I\Iterator<A>
	 */
	public function take_while(callable $fn): I\Iterator {
		return C\Iterator\TakeWhile::new($fn)($this);
	}

	/**
	 * Collects the items from the iterator into an array.
	 *
	 * @return array<A>
	 */
	public function to_array(): array {
		$array = [];
		$this->for_each(function ($item) use (&$array): void {
			array_push($array, $item);
		});
		return $array;
	}
}
