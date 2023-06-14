<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Interfaces;

use Nothingnesses\Lib\Classes as C;

/**
 * @template A
 *
 * Represents an iterator that can iterate over a collection of elements.
 */
interface Iterator extends Functor {
	/**
	 * Checks if any item in the iterator matches a given condition.
	 *
	 * @param callable(A): bool $predicate The function applied to the item being iterated over to test if it matches the condition.
	 * @return bool `true` if any item matches the condition, `false` otherwise.
	 */
	public function any($predicate): bool;

	/**
	 * Filters the items in the iterator based on a given condition.
	 *
	 * @param callable(A): bool $predicate The function applied to the items being iterated over to filter those that match the condition.
	 * @return FilterIterator<A> An iterator over the items matching the condition.
	 */
	public function filter($predicate): FilterIterator;

	/**
	 * Finds the first item in the iterator that matches a given condition.
	 *
	 * @param callable(A): bool $predicate The function applied to the item being iterated over to test if it matches the condition.
	 * @return C\Maybe<A> The `some` variant containing the first item that matches the condition, if it exists, or the `none` variant.
	 */
	public function find($predicate): C\Maybe;


	/**
	 * Applies a function to each item in the iterator, reducing the items to a single value from the left.
	 *
	 * @template B
	 * @param callable(B $carry): callable(A $item): B $fn The function to apply to each item being iterated over.
	 * @return \Closure(B $initial): B A closure that accepts the initial value and returns the reduced value.
	 */
	public function foldl($fn): \Closure;
	/**
	 * Applies a function to each item in the iterator.
	 *
	 * @param callable(A): void $fn The function to apply to each item being iterated over.
	 * @return void
	 */
	public function for_each($fn);

	/**
	 * Applies a function to each item in the iterator and returns the output.
	 *
	 * @param callable(A): A $fn The function to apply to each item being iterated over.
	 * @return MapIterator<A> An iterator over the results of applying the function over the items.
	 */
	public function map($fn): \Nothingnesses\Lib\Interfaces\Functor;

	/**
	 * Retrieves the next item from the iterator.
	 *
	 * @return C\Maybe<A> The `some` variant containing the next item if it exists, or the `none` variant.
	 */
	public function next(): C\Maybe;

	/**
	 * Collects the items from the iterator into an array.
	 *
	 * @return array<A> An array containing the items collected from the iterator.
	 */
	public function to_array(): array;
}
