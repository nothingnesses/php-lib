<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\WordPress\Classes\Iterator;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;
use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\WordPress\Classes as WC;
use WP_Post;

/**
 * An iterator over a post's ancestors.
 * 
 * @implements I\Iterator<WP_Post>
 */
class Ancestors implements I\Iterator {
	/**
	 * @use T\Iterator<WP_Post>
	 */
	use T\Iterator;

	/**
	 * @param C\Maybe<WP_Post> $post Post to get the ancestors of.
	 * @param array<WP_Post> $posts The array to search posts in.
	 */
	private function __construct(private C\Maybe $post, private array $posts) {
	}

	/**
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return \Closure(WP_Post): WC\Iterator\Ancestors
	 */
	public static function new(array $posts): \Closure {
		/**
		 * @param WP_Post $post Post to get the ancestors of.
		 * @return WC\Iterator\Ancestors
		 */
		return fn (WP_Post $post): self => new self(
			posts: $posts,
			post: C\Maybe::some($post),
		);
	}

	public function next(): C\Maybe {
		$a = &$this;
		return $this->post->bind(
			fn (WP_Post $post) => WC\Functions::get_post_parent($a->posts)($post)
				->maybe_lazy(function () use (&$a) {
					$none = C\Maybe::none();
					$a->post = $none;
					return $none;
				})(function ($parent) use (&$a) {
				$maybe_parent = C\Maybe::some($parent);
				$a->post = $maybe_parent;
				return $maybe_parent;
			})
		);
	}
}
