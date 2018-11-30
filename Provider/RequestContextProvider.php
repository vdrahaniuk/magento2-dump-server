<?php
/**
 * Class Dumper
 */
namespace Vdrahaniuk\DumpServer\Provider;

use Magento\Framework\App\Request\Http;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\ContextProvider\ContextProviderInterface;
class RequestContextProvider implements ContextProviderInterface
{
	/**
	 * The current request.
	 *
	 * @var Http|null
	 */
	private $currentRequest;
	/**
	 * The variable cloner.
	 *
	 * @var \Symfony\Component\VarDumper\Cloner\VarCloner
	 */
	private $cloner;
	/**
	 * RequestContextProvider constructor.
	 *
	 * @param  Http|null  $currentRequest
	 * @return void
	 */
	public function __construct(Http $currentRequest = null)
	{
		$this->currentRequest = $currentRequest;
		$this->cloner = new VarCloner;
		$this->cloner->setMaxItems(0);
	}
	/**
	 * Get the context.
	 *
	 * @return array|null
	 */
	public function getContext(): ?array
	{
		if ($this->currentRequest === null) {
			return null;
		}
		$controller = null;
		if ($route = $this->currentRequest) {
			$controller = $route->getControllerName();
		}

		return [
			'uri' => $this->currentRequest->getUri()->getPath(),
			'method' => $this->currentRequest->getActionName(),
			'controller' =>  $this->cloner->cloneVar($controller),
			'identifier' => spl_object_hash($this->currentRequest),
		];
	}
}
