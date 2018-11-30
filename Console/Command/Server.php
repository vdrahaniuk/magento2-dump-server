<?php

namespace Vdrahaniuk\DumpServer\Console\Command;

use InvalidArgumentException;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Server\DumpServer;
use Symfony\Component\VarDumper\Command\Descriptor\CliDescriptor;
use Symfony\Component\VarDumper\Command\Descriptor\HtmlDescriptor;
use Vdrahaniuk\DumpServer\Api\GeneralConfigurationInterface;

class Server extends Command
{
	const FORMAT = 'format';

	/**
	 * @var Magento\Framework\App\State
	 */
	private $state;
	/**
	 * @var DumpServer
	 */
	private $dumpServer;
	private $generalConfiguration;
	
	public function __construct(
		State $state,
		GeneralConfigurationInterface $generalConfiguration
	) {
		$this->state = $state;
		$this->generalConfiguration = $generalConfiguration;
		$this->dumpServer = new DumpServer($this->generalConfiguration->getHost());
		parent::__construct();

	}


	protected function configure()
	{
		$this->setName('dump-server:start')
			->setDescription('Start magento2 dump server')
			->addOption(self::FORMAT, '-f', InputOption::VALUE_OPTIONAL, 'format', 'cli');

	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		$this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

		switch ($format = $input->getOption(self::FORMAT)) {
			case 'cli':
				$descriptor = new CliDescriptor(new CliDumper);
				break;
			case 'html':
				$descriptor = new HtmlDescriptor(new HtmlDumper);
				break;
			default:
				throw new InvalidArgumentException(sprintf('Unsupported format "%s".', $format));
		}


		$io = new SymfonyStyle($input, $output);
		$errorIo = $io->getErrorStyle();
		$errorIo->title('Magento 2 Var Dump Server');
		$this->dumpServer->start();
		$errorIo->success(sprintf('Server listening on %s', $this->dumpServer->getHost()));
		$errorIo->comment('Quit the server with CONTROL-C.');
		$this->dumpServer->listen(function (Data $data, array $context, int $clientId) use ($descriptor, $io) {
			$descriptor->describe($io, $data, $context, $clientId);
		});

	}
}