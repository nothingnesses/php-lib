<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Interfaces;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;

/**
 * Functions for a type that can iterate over a set of items.
 * 
 * @template A
 */
interface Iterator {
	/**
	 * Checks if all the items yielded by the iterator satisfy a predicate
	 * function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if all match a condition.
	 * @return bool `true` if all items match the condition, `false` otherwise.
	 */
	public function all($fn): bool;

	/**
	 * Checks if any of the items yielded by the iterator satisfy a predicate
	 * function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if any match a condition.
	 * @return bool `true` if an item matches the condition, `false` otherwise.
	 */
	public function any($fn): bool;

	/**
	 * Returns an iterator that yields items from the current iterator, then from
	 * another iterator.
	 * 
	 * @param I\Iterator<A> $second The other iterator to concatenate with the current one.
	 * @return I\Iterator<A>
	 */
	public function chain($second);

	/**
	 * Returns an iterator that yields items that satisfy a predicate function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if they match a condition.
	 * @return I\Iterator<A>
	 */
	public function filter($fn);

	/**
	 * Returns the first item in the iterator that satisfies a predicate
	 * function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if they match a condition.
	 * @return C\Maybe<A> The `some` variant containing the first item that matches the condition if it exists, or the `none` variant.
	 */
	public function find($fn): C\Maybe;

	/**
	 * Left-associative fold operation.
	 * Applies a binary function to an initial accumulator value and the first
	 * item of the iterator, then uses the result as the new accumulator value.
	 * The process is then repeated for each of the remaining items of the
	 * iterator.
	 *
	 * @template B
	 * @param callable(B $carry): (callable(A $item): B) $fn The function to apply to each item yielded.
	 * @return \Closure(B $initial): B A closure that accepts the initial value and returns the reduced value.
	 */
	public function fold_left($fn): \Closure;

	/**
	 * Applies a function to each item yielded by the iterator.
	 *
	 * @param callable(A): void $fn The function to apply to each item yielded.
	 * @return void
	 */
	public function for_each($fn);

	/**
	 * Returns an iterator that yields items that are the result of applying a
	 * function to items yielded by the current iterator.
	 *
	 * @template B
	 * @param callable(A): B $fn The function to apply to each item yielded.
	 * @return \Nothingnesses\Lib\Interfaces\Iterator<\Nothingnesses\Lib\Interfaces\B>
	 */
	public function map($fn);

	/**
	 * Returns the next item from the iterator.
	 *
	 * @return C\Maybe<A> The `some` variant containing the next item if it exists, or the `none` variant.
	 */
	public function next(): C\Maybe;

	/**
	 * Returns an iterator that yields items as long as they satisfy a predicate function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if they match a condition.
	 * @return I\Iterator<A>
	 */
	public function take_while($fn);

	/**
	 * Collects the items from the iterator into an array.
	 *
	 * @return array<A>
	 */
	public function to_array(): array;
}
