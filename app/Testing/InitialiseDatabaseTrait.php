<?php

namespace App\Testing;

trait InitialiseDatabaseTrait
{
	protected static $backupExtension = '.dusk.bak';
	
	/**
	 * Creates an empty database for testing, but backups the current dev one first.
	 */
	public function backupDatabase()
	{
		if (!$this->app) {
			$this->refreshApplication();
		}
		
		$db = $this->app->make('db')->connection();
		if (!file_exists($db->getDatabaseName())) {
			touch($db->getDatabaseName());
		}
	}
}