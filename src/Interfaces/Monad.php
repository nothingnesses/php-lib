<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace

declare(strict_types=1);

namespace Nothingnesses\Lib\Interfaces;

/**
 * Monad interface.
 *
 * @template A
 * @template B
 */
interface Monad {
	/**
	 * Chains a monadic function over the wrapped value.
	 *
	 * @param callable(A): Functor<B> $fn - The monadic function to be applied.
	 * @return Functor<B> - A new monad with the function applied.
	 */
	public function bind(callable $fn): self;
}
