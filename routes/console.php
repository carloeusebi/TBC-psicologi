<?php

declare(strict_types=1);
use App\Console\Commands\FetchPlansCommand;

Schedule::command(FetchPlansCommand::class)->twiceDaily();
