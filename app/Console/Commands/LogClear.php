<?php
	
	namespace App\Console\Commands;
	
	use Illuminate\Console\Command;
	
	class LogClear extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'logs:clear';
		
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Command description';
		
		/**
		 * Create a new command instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
			parent::__construct();
		}
		
		/**
		 * function clear logs from storage folder
		 */
		public function handle()
		{
			$files = glob(storage_path('logs/laravel*.log'));
			
			foreach ($files as $file) {
				if (file_exists($file)) {
					unlink($file);
				}
			}
			$this->comment('Logs have been cleared!');
		}
	}
