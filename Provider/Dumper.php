<?php
/**
 * Class Dumper
 */
namespace Vdrahaniuk\DumpServer\Provider;


use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Server\Connection;
use Vdrahaniuk\DumpServer\Api\GeneralConfigurationInterface;

class Dumper
{
	/**
	 * The connection.
	 *
	 * @var \Symfony\Component\VarDumper\Server\Connection|null
	 */
	private $connection;
	/**
	 * @var GeneralConfigurationInterface
	 */
	private $generalConfiguration;
	/**
	 * Dumper constructor.
	 *
	 * @param  \Symfony\Component\VarDumper\Server\Connection|null  $connection
	 * @return void
	 */
	public function __construct(
		GeneralConfigurationInterface $generalConfiguration
	)
	{
		$this->generalConfiguration = $generalConfiguration;
	}
	/**
	 * Dump a value with elegance.
	 *
	 * @param  mixed  $value
	 * @return void
	 */
	public function dump($value)
	{
		if (class_exists(CliDumper::class)) {
			$data = (new VarCloner)->cloneVar($value);
			if ($this->connection === null || $this->connection->write($data) === false) {
				$dumper = in_array(PHP_SAPI, ['cli', 'phpdbg']) ? new CliDumper : $this->generalConfiguration->dumpOnFrontendAllowed() ? new HtmlDumper : null;
				if($dumper){
					$dumper->dump($data);
				}

			}
		} else {
			var_dump($value);
		}
	}

	public function setConnection(Connection $connection)
	{
		$this->connection = $connection;
		return $this;
	}
}