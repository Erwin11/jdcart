<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;

class ViewCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'views:clear';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear views folder';

	/**
	 * The file system instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->files = new \Illuminate\Filesystem\Filesystem;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		foreach ($this->files->files(storage_path().'/views') as $file){
			$this->files->delete($file);
		}
 
		$this->info('Views deleted from cache');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
