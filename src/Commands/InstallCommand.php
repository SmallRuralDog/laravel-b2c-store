<?php


namespace SmallRuralDog\Store\Commands;


use Illuminate\Console\Command;
use SmallRuralDog\Store\Models\StoreTablesSeeder;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the laravel-store package';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->call('migrate');

        $this->info('开始更新成功');


        $this->call('db:seed', ['--class' => StoreTablesSeeder::class]);

        $this->info('数据更新成功');
    }
}
