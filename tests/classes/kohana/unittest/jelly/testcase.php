<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Unittest_Jelly_TestCase extends Kohana_Unittest_Database_TestCase {

	/**
	* Creates tables.
	 *
	 * @return  void
	 * @uses    parent::setUpBeforeClass
	 * @uses    Kohana::find_file
	 * @uses    DB::query
	*/
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		// Load config
		$config = Kohana::$config->load('database')->{Kohana::$config->load('unittest')->db_connection};

		// Find file
		$file = Kohana::find_file('tests/test_data/jelly', 'test-schema-'.$config['type'], 'sql');

		// Get contents
		$file = file_get_contents($file);

		// Extract queries
		$queries = explode(';', $file);

		foreach ($queries as $query)
		{
			if (empty($query))
			{
				// Don't run empty queries
				continue;
			}

			// Execute query
			DB::query(NULL, $query)->execute();
		}
	}

	/**
	 * Creates the database connection.
	 *
     * @return  PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 * @uses    Kohana::$config->load
	 * @uses    Arr::get
	 * @uses    PDO
     */
    public function getConnection()
    {
        // Load config
		$config = Kohana::$config->load('database')->{Kohana::$config->load('unittest')->db_connection};

		// Set dsn
		$dsn = Arr::get($config, 'dsn', $config['type'].':host='.$config['connection']['hostname'].';dbname='.$config['connection']['database']);

		// Use MySQL connection
		$pdo = new PDO($dsn, $config['connection']['username'], $config['connection']['password']);

		// Create connection
		// IMPORTANT: database has to be set in config, even for PDO
		return $this->createDefaultDBConnection($pdo, $config['connection']['database']);
    }

    /**
	 * Inserts default data into database.
	 *
     * @return  PHPUnit_Extensions_Database_DataSet_IDataSet
	 * @uses    Kohana::find_file
     */
    public function getDataSet()
    {
		return $this->createXMLDataSet(Kohana::find_file('tests/test_data/jelly', 'test', 'xml'));
    }

} // End Kohana_Unittest_Jelly_TestCase
