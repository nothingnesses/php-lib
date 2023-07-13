<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;

/**
 * Maybe class representing an optional value that may or may not be present.
 *
 * @template A
 */
class Maybe {
	/**
	 * @var bool
	 */
	private $is_some;
	private $item;
	private function __construct(bool $is_some, $item)
	{
		$this->is_some = $is_some;
		$this->item = $item;
	}

	/**
	 * Chains a monadic function over the wrapped value.
	 *
	 * @param callable $fn - The monadic function to be applied.
	 * @return Maybe - A new Maybe value with the function applied.
	 */
	public function bind($fn): self {
		return $this->is_some ? $fn($this->item) : self::none();
	}

	/**
	 * Checks if the Maybe value is of the `some` variant.
	 *
	 * @return bool - `true` if the Maybe value is `some`, else `false`.
	 */
	public function is_some(): bool {
		return $this->is_some;
	}

	/**
	 * Extracts the value from the Maybe value or returns a default value.
	 *
	 * @template B
	 * @param B $on_none - Default value to return if `$maybe` is `none`.
	 * @return B - The value or the default value.
	 */
	public function from_maybe($on_none) {
		return $this->is_some ? $this->item : $on_none;
	}

	/**
	 * Applies a function to the item in the Maybe value conditionally.
	 *
	 * @template B
	 * @param B $on_none - Default value to return if `$maybe` is `none`.
	 * @return \Closure(callable(A): B): B - A closure that takes a function and returns the result of applying it conditionally.
	 */
	public function maybe($on_none): \Closure {
		/**
		 * Applies a function to the item in the Maybe value conditionally.
		 *
		 * @param callable(A): B $on_some - Function applied to item in self if it is `some`.
		 * @return B - The result of applying the function.
		 */
		return function (callable $on_some) use ($on_none) {
			return $this->is_some ? $on_some($this->item) : $on_none;
		};
	}

	/**
	 * Maps a function over the wrapped value.
	 *
	 * @param callable $fn - The function to be applied.
	 * @return Maybe - A new Maybe value with the function applied.
	 */
	public function map($fn): self {
		return $this->is_some ? self::some($fn($this->item)) : self::none();
	}

	/**
	 * Applies a function to the item in the Maybe value conditionally or lazily returns a default value.
	 *
	 * @template B
	 * @param callable(): B $on_none - Thunk called to return a default value if `$maybe` is `none`.
	 * @return \Closure(callable(A): callable(): B): B - A closure that takes a function and returns the result of applying it conditionally.
	 */
	public function maybe_lazy($on_none): \Closure {
		/**
		 * Applies a function to the item in the Maybe value conditionally or lazily returns a default value.
		 *
		 * @param callable(A): B $on_some - Function applied to item in self if it is `some`, or the result of calling `$on_none`.
		 * @return B - The result of applying the function or the default value.
		 */
		return function (callable $on_some) use ($on_none) {
			return $this->is_some ? $on_some($this->item) : $on_none();
		};
	}

	/**
	 * Creates a Maybe value of the `some` variant containing a value.
	 *
	 * @param mixed $item - The value to be wrapped.
	 * @return Maybe - A `some` variant containing the value.
	 */
	public static function some($item): self {
		return new self(
			true,
			$item
		);
	}

	/**
	 * Creates a Maybe value of the `none` variant.
	 *
	 * @return Maybe - A `none` variant.
	 */
	public static function none(): self {
		return new self(
			false,
			null
		);
	}
}
