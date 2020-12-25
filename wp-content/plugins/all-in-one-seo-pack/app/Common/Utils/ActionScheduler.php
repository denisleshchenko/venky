<?php
namespace AIOSEO\Plugin\Common\Utils;

/**
 * This class makes sure the action scheduler tables always exist.
 *
 * @since 4.0.0
 */
class ActionScheduler extends \ActionScheduler_ListTable {
	/**
	 * Class Constructor.
	 *
	 * @since 4.0.0
	 *
	 * @param $store
	 * @param $logger
	 * @param $runner
	 */
	public function __construct( $store, $logger, $runner ) {
		global $wpdb;
		if (
				(
					is_a( $store, 'ActionScheduler_HybridStore' ) ||
					is_a( $store, 'ActionScheduler_DBStore' )
				) &&
				apply_filters( 'action_scheduler_enable_recreate_data_store', true ) &&
				method_exists( get_parent_class( $this ), 'recreate_tables' )
			) {
			$tableList = [
				'actionscheduler_actions',
				'actionscheduler_logs',
				'actionscheduler_groups',
				'actionscheduler_claims',
			];

			$foundTables = $wpdb->get_col( "SHOW TABLES LIKE '{$wpdb->prefix}actionscheduler%'" );
			foreach ( $tableList as $tableName ) {
				if ( ! in_array( $wpdb->prefix . $tableName, $foundTables, true ) ) {
					$this->recreate_tables();
					return;
				}
			}
		}
	}
}