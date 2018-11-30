<?php
namespace Vdrahaniuk\DumpServer\Observer;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Event\ObserverInterface;

use Vdrahaniuk\DumpServer\Provider\Dumper;
use Vdrahaniuk\DumpServer\Provider\RequestContextProvider;

use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Server\Connection;
use Symfony\Component\VarDumper\Server\DumpServer;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Vdrahaniuk\DumpServer\Api\GeneralConfigurationInterface;

class FrontendActionBefore implements ObserverInterface
{

	/**
	 * @var Http
	 */
	private $request;
	public $directoryList;
	public $dumper;
	public $generalConfiguration;
	/**
	 * FrontendActionBefore constructor.
	 * @param Http $request

	 */
	public function __construct(
		Http $request,
		DirectoryList $directoryList,
		Dumper $dumper,
		GeneralConfigurationInterface $generalConfiguration
	) {

		$this->request = $request;
		$this->directoryList = $directoryList;
		$this->dumper = $dumper;
		$this->generalConfiguration = $generalConfiguration;
	}

	public function execute(\Magento\Framework\Event\Observer $observer)
	{

		$connection = new Connection($this->generalConfiguration->getHost(), [
			'request' => new RequestContextProvider($this->request),
			'source' => new SourceContextProvider('utf-8', $this->directoryList->getRoot()),
		]);

		VarDumper::setHandler(function ($var) use ($connection) {
			$this->dumper->setConnection($connection)->dump($var);
		});

		return $this;
	}

	

}