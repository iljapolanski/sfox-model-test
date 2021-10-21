<?php

namespace App\Console\Commands;

use App\Service\SlotService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class SlotCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'slot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Bets on slot and shows paylines and total win";

    private SlotService $slotService;

    public function __construct(SlotService $slotService)
    {
        parent::__construct();

        $this->slotService = $slotService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $isExampleBoard = $this->input->getOption('example');
        if ($isExampleBoard !== 'true' && $isExampleBoard != 1) {
            $isExampleBoard = false;
        }
        $this->info("Slot started");
        $this->getOutput()->write($this->slotService->bet($isExampleBoard) . "\n");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            [
                'example',
                null,
                InputOption::VALUE_OPTIONAL,
                'Set true or 1 if to use the example board. otherwise and if omitted considered false.',
                false
            ],
        );
    }
}
