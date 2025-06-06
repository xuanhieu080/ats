<?php

namespace Packages\VCore\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Packages\Discount\Jobs\DiscountRollbackJob;
use Packages\Discount\Models\DiscountTemp;
use Packages\VCore\Actions\ValidateInputAction;
use Packages\VCore\VCorePipeline;

class VCoreCommand extends Command
{
    protected $signature = 'vcore:run {--name=} {--option=}';

    /**
     * C => Controller
     * M => Model
     * R => Request
     * S => Schema
     * --path=packages
     */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Modules';

    public function handle()
    {
        $payload = [
            'name' => $this->option('name'),
            'option' => $this->option('option'),
        ];

        $run = new VCorePipeline();
        $result = $run->handle($payload);

        if (isset($result['error'])) {
            $this->error($result['message']);
        }
    }
}
