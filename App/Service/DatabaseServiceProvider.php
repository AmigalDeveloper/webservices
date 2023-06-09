<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Service;

use Joomla\Database\DatabaseDriver;
use Joomla\Database\Monitor\DebugMonitor;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use Joomla\Database\DatabaseFactory;
use Joomla\Database\DatabaseInterface;


/**
 * Database service provider
 *
 * @since  1.0
 */
class DatabaseServiceProvider implements ServiceProviderInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function register(Container $container)
	{
		$container->set('Joomla\\Database\\DatabaseDriver',
			function () use ($container)
			{
				$config = $container->get('config');

				$options = array(
					'driver' => $config->get('database.driver'),
					'host' => $config->get('database.host'),
					'user' => $config->get('database.user'),
					'password' => $config->get('database.password'),
					'database' => $config->get('database.name'),
					'prefix' => $config->get('database.prefix')
				);
				$options['monitor'] = new DebugMonitor;
                
             $databaseFactory = new DatabaseFactory;
			 $db = $databaseFactory->getDriver($options['driver'],$options);
			 //$db->setMonitor($config->get('debug.database', false));

				return $db;
			}, true, true
		);

		// Alias the database
		$container->alias('db', 'Joomla\\Database\\DatabaseDriver');
	}
}