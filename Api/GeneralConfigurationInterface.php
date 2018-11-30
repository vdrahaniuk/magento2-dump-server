<?php
namespace Vdrahaniuk\DumpServer\Api;

interface GeneralConfigurationInterface
{
	const XML_PATH_DUMP_ON_FRONTEND_ENABLED = 'dump_server/general/frontend';
	const XML_PATH_DUMP_SERVER_HOST = 'dump_server/general/host';
	
	/**
	 * Check if dump on frontend is enabled
	 * @return bool
	 */
	public function dumpOnFrontendAllowed(): bool;

	/**
	 * Get dump-server address
	 * @return string
	 */
	public function getHost(): string;
}