<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

/**
 * An optional value that may or may not be present.
 *
 * @template-covariant A
 * @template-covariant B
 */
class Either {
	/**
	 * @var bool
	 */
	private $is_right;
	/**
	 * @var A|B
	 */
	private $item;
	/**
	 * @param bool $is_right States if the instance is the `right` variant or not.
	 * @param mixed $item The wrapped value.
	 */
	private function __construct(bool $is_right, $item)
	{
		$this->is_right = $is_right;
		$this->item = $item;
	}

	/**
	 * Returns the result of applying the first function to the wrapped item if
	 * this is a `left` instance or the result of applying the second function
	 * if it's a `right` instance.
	 *
	 * @template C
	 * @param callable(A): C $on_left - Function applied to the wrapped item if this is a `left` instance.
	 * @return \Closure(callable(B): C): C
	 */
	public function either($on_left): \Closure {
		/**
		 * @param callable(B): C $on_right - Function applied to the wrapped item if this is a `right` instance.
		 * @return B
		 */
		return function (callable $on_right) use ($on_left) {
			return $this->is_right() ? $on_right($this->item) : $on_left($this->item);
		};
	}

	/**
	 * Checks if the instance is of the `right` variant.
	 *
	 * @return bool `true` if the instance is `right`, else `false`.
	 */
	public function is_right(): bool {
		return $this->is_right;
	}

	/**
	 * Makes an instance of the `left` variant containing a value.
	 *
	 * @template-covariant A
	 * @template-covariant B
	 * @param mixed $item - The value to be wrapped.
	 * @return Either<A,B>
	 */
	public static function left($item): self {
		return new self(
			false,
			$item
		);
	}

	/**
	 * Makes an instance of the `right` variant containing a value.
	 *
	 * @template-covariant A
	 * @template-covariant B
	 * @param mixed $item - The value to be wrapped.
	 * @return Either<A,B>
	 */
	public static function right($item): self {
		return new self(
			true,
			$item
		);
	}
}
