<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace

declare(strict_types=1);

namespace Nothingnesses\Lib\Interfaces;

/**
 * Functor interface.
 *
 * @template A
 * @template B
 */
interface Functor {
	/**
	 * Maps a function over the wrapped value.
	 *
	 * @param callable(A): B $fn - The function to be applied.
	 * @return Functor<B> - A new functor with the function applied.
	 */
	public function map($fn): self;
}

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
	public function bind($fn): self;
}