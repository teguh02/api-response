<?php

namespace teguh02\ApiResponse\Commands;

use Illuminate\Console\Command;

class ApiResponseCommand extends Command
{
    public $signature = 'api-response';

    public $description = 'Laravel API Response Command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
