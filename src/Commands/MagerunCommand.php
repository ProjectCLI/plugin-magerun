<?php

namespace ProjectCLI\Magerun\Commands;

use Chriha\ProjectCLI\Commands\Command;
use Chriha\ProjectCLI\Contracts\Plugin;
use Chriha\ProjectCLI\Helpers;
use Chriha\ProjectCLI\Services\Docker;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MagerunCommand extends Command
{

    /** @var string */
    protected static $defaultName = 'magerun';

    /** @var string */
    protected $description = 'Alias for Magerun commands';


    /**
     * Configure the command by adding a description, arguments and options
     *
     * @return void
     */
    public function configure() : void
    {
        $this->addDynamicArguments()->addDynamicOptions();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(Docker $docker) : void
    {
        $docker->exec('web', $this->getParameters(['php', 'vendor/bin/n98-magerun2']))
            ->setTty(true)
            ->run(
                function ($type, $buffer)
                {
                    $this->output->write($buffer);
                }
            );
    }

    /**
     * Make command only available if inside the project
     */
    public static function isActive() : bool
    {
        return PROJECT_IS_INSIDE
            && file_exists(Helpers::projectPath('src/vendor/bin/n98-magerun2'));
    }

}
