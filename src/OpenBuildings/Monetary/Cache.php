<?php

namespace OpenBuildings\Monetary;

/**
* Simple cache using Desarrolla2\Cache.
* @author Haralan Dobrev <hdobrev@despark.com>
* @copyright (c) 2013 OpenBuildings Inc.
* @license http://spdx.org/licenses/BSD-3-Clause
*/
class Cache implements Cacheable {

	/**
	* Cache will be valid for 1 day
	*/
	const CACHE_LIFETIME = 86400;

	const CACHE_DIR = 'monetary';

	/**
	* @var \Stash\Pool
	*/
	protected $_cache;

	/**
	* Get an instance of the cache helper
	* @return \Stash\Pool
	*/
	public function cache_driver()
	{
			if ( ! $this->_cache)
			{
				if (defined ( APP_CACHE )) {
					$cache_dir = APP_CACHE . DIRECTORY_SEPARATOR . static::CACHE_DIR;
				}
				else {
					$cache_dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . static::CACHE_DIR;
				}

				// Uses a install specific default path if none is passed.
				$this->driver = new \Stash\Driver\FileSystem();

				$this->driver->setOptions([
					'path'	=> $cache_dir
				]);

				$this->_cache = new \Stash\Pool($this->driver);
			}

			return $this->_cache;
		}

		private function getItem($cache_key)
		{
			return $this->cache_driver()->getItem($cache_key);
		}

		public function read_cache($cache_key)
		{
			$item = $this->getItem($cache_key);

			return $item->get(\Stash\Invalidation::OLD);
		}

		public function write_cache($cache_key, $data)
		{
			$item = $this->getItem($cache_key);

			$item->set($data, CACHE_LIFETIME);

			return $this;
		}
	}
