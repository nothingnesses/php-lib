<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;

/**
 * An optional value that may or may not be present.
 *
 * @template-covariant A
 */
class Maybe {
	/**
	 * @var bool
	 */
	private $is_some;
	/**
	 * @var A|null
	 */
	private $item;
	/**
	 * @param bool $is_some States if the instance is the `some` variant or not.
	 * @param mixed $item The wrapped value.
	 */
	private function __construct(bool $is_some, $item)
	{
		$this->is_some = $is_some;
		$this->item = $item;
	}

	/**
	 * Returns a `some` containing the result of applying a function to the
	 * currently contained value, if it exists. Otherwise, returns `none`.
	 *
	 * @template B
	 * @param callable(A): Maybe<B> $fn - The function to be applied.
	 * @return Maybe<B>
	 */
	public function bind($fn): self {
		return $this->is_some() && !is_null($this->item) ? $fn($this->item) : self::none();
	}

	/**
	 * Checks if the instance is of the `some` variant.
	 *
	 * @return bool `true` if the instance is `some`, else `false`.
	 */
	public function is_some(): bool {
		return $this->is_some;
	}

	/**
	 * Returns the value contained if the instance is of the `some` variant,
	 * or returns a default value if not.
	 *
	 * @template B
	 * @param B $on_none Default value to return if the instance is a `none`.
	 * @return B
	 */
	public function from_maybe($on_none) {
		return $this->is_some() && !is_null($this->item) ? $this->item : $on_none;
	}

	/**
	 * Returns a `some` containing the result of applying a function to the
	 * currently contained value, if it exists. Otherwise, returns `none`.
	 *
	 * @template B
	 * @param callable(A): B $fn - The function to be applied.
	 * @return Maybe<B>
	 */
	public function map($fn): self {
		return $this->is_some() && !is_null($this->item) ? self::some($fn($this->item)) : self::none();
	}

	/**
	 * Returns the result of applies a function to the wrapped value, if it
	 * exists. Otherwise, returns a default value.
	 *
	 * @template B
	 * @param B $on_none - Default value to return if self is `none`.
	 * @return \Closure(callable(A): B): B - Function to apply.
	 */
	public function maybe($on_none): \Closure {
		/**
		 * @param callable(A): B $on_some - Function applied to item in self if it is `some`.
		 * @return B
		 */
		return function (callable $on_some) use ($on_none) {
			return $this->is_some() && !is_null($this->item) ? $on_some($this->item) : $on_none;
		};
	}

	/**
	 * Returns the result of applies a function to the wrapped value, if it
	 * exists. Otherwise, returns a the result of calling a thunk.
	 *
	 * @template B
	 * @param callable(): B $on_none - Thunk called to return a default value if self is `none`.
	 * @return \Closure(callable(A): B): B - Function to apply.
	 */
	public function maybe_lazy($on_none): \Closure {
		/**
		 * @param callable(A): B $on_some - Function applied to item in self if it is `some`, or the result of calling `$on_none`.
		 * @return B
		 */
		return function (callable $on_some) use ($on_none) {
			return $this->is_some() && !is_null($this->item) ? $on_some($this->item) : $on_none();
		};
	}

	/**
	 * Makes an instanace of the `some` variant containing a value.
	 *
	 * @template B
	 * @param mixed $item - The value to be wrapped.
	 * @return Maybe<B>
	 */
	public static function some($item): self {
		return new self(
			true,
			$item
		);
	}

	// @note Ideally, this should return `Maybe<B>`, like the `some` constructor.
	/**
	 * Makes an instance of the `none` variant.
	 *
	 * @return Maybe<mixed>
	 */
	public static function none(): self {
		return new self(
			false,
			null
		);
	}
}
