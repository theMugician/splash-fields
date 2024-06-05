<?php
/**
 * Storage_Registry Class.
 *
 * @package splash-fields
 */

namespace Splash_Fields;

/**
 * Class Storage_Registry.
 */
class Storage_Registry {
    protected $storages = [];

	/**
	 * Get storage instance.
	 *
	 * @param string $class_name Storage class name.
	 * @return Storage Class
	 */
	public function get( $class_name ) {
		if ( empty( $this->storages[ $class_name ] ) ) {
			$this->storages[ $class_name ] = new $class_name();
		}

		return $this->storages[ $class_name ];
	}
}