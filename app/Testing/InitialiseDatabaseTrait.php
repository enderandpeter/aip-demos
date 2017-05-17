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
		
		file_put_contents($db->getDatabaseName(), '');
		
	}
}