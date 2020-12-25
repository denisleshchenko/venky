<?php
namespace AIOSEO\Plugin\Common\Sitemap;

/**
 * Handles our sitemap rewrite rules.
 *
 * @since 4.0.0
 */
class Rewrite {

	/**
	 * Returns our sitemap rewrite rules.
	 *
	 * @since 4.0.0
	 *
	 * @return array $rules The compiled array of rewrite rules, keyed by their regex pattern.
	 */
	private static function get() {
		$filename = aioseo()->sitemap->helpers->filename( 'general' );

		$rules = [];
		if ( ! empty( $filename ) && 'sitemap' !== $filename && aioseo()->options->sitemap->general->enable ) {
			// Check if user has a custom filename from the V3 migration.
			$rules += [
				"$filename.xml"           => 'index.php?aiosp_sitemap_path=root',
				"(.+)-$filename.xml"      => 'index.php?aiosp_sitemap_path=$matches[1]',
				"(.+)-$filename(\d+).xml" => 'index.php?aiosp_sitemap_path=$matches[1]&aiosp_sitemap_page=$matches[2]'
			];
		}

		foreach ( aioseo()->sitemap->addons as $addon => $classes ) {
			if ( ! empty( $classes['rewrite'] ) ) {
				$rules += $classes['rewrite']->get();
			}
		}

		if ( aioseo()->options->sitemap->general->enable ) {
			$rules += [
				'sitemap.xml' => 'index.php?aiosp_sitemap_path=root',
			];
		}

		$rules += [
			'(.+)-sitemap.xml'      => 'index.php?aiosp_sitemap_path=$matches[1]',
			'(.+)-sitemap(\d+).xml' => 'index.php?aiosp_sitemap_path=$matches[1]&aiosp_sitemap_page=$matches[2]'
		];

		if ( aioseo()->options->sitemap->rss->enable ) {
			$rules += [
				'sitemap.rss'        => 'index.php?aiosp_sitemap_path=rss',
				'sitemap.latest.rss' => 'index.php?aiosp_sitemap_path=rss'
			];
		}
		return $rules;
	}

	/**
	 * Adds our sitemap rewrite rules.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public static function add() {
		// @TODO: [V4+] Refactor all of this to add rewrite rules via a filter instead. Then we can get rid of delete() as well.

		// We need to clear our rules first in case a sitemap was disabled.
		self::delete();

		$existingRules = (array) get_option( 'rewrite_rules', [] );
		if ( empty( $existingRules ) ) {
			// We need to flush the rules after the Search Appearance options in Yoast SEO are updated; otherwise the regular rewrite rules are missing.
			return flush_rewrite_rules( false );
		}

		$newRules = array_merge( self::get(), $existingRules );
		update_option( 'rewrite_rules', $newRules );
	}

	/**
	 * Deletes our sitemap rewrite rules.
	 *
	 * @since 4.0.0
	 *
	 * @param  boolean $deactivation Whether the plugin is being deactivated.
	 * @return void
	 */
	public static function delete( $deactivation = false ) {
		$rules = get_option( 'rewrite_rules' );

		if ( empty( $rules ) ) {
			return;
		}

		$generalEnabled = aioseo()->options->sitemap->general->enable;
		foreach ( $rules as $k => $v ) {
			if ( preg_match( '#.*aiosp_.*#', $v ) ) {
				if ( 'index.php?aiosp_sitemap_path=root' === $v ) {
					if ( ! $generalEnabled ) {
						unset( $rules[ $k ] );
					}
				}

				if ( $deactivation ) {
					unset( $rules[ $k ] );
				}
			}
		}

		update_option( 'rewrite_rules', $rules );
	}
}