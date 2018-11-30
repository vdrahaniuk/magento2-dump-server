<?php
namespace Vdrahaniuk\DumpServer\Config;

use Vdrahaniuk\DumpServer\Api\GeneralConfigurationInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class General implements GeneralConfigurationInterface
{

	public $scopeConfig;

	public function __construct(
		ScopeConfigInterface $scopeConfig
	) {
		$this->scopeConfig = $scopeConfig;
	}


	public function dumpOnFrontendAllowed(): bool
	{
		return $this->scopeConfig->isSetFlag(GeneralConfigurationInterface::XML_PATH_DUMP_ON_FRONTEND_ENABLED);
	}

	public function getHost(): string
	{
		return $this->scopeConfig->getValue(GeneralConfigurationInterface::XML_PATH_DUMP_SERVER_HOST) ?? 'tcp://127.0.0.1:9912';
	}
}