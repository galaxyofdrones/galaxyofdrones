<?php

namespace App\Console\Commands;

use App\Console\Behaviors\PrependTimestamp;
use App\Contracts\Starmap\Generator;
use App\Contracts\Starmap\Renderer;
use Illuminate\Console\Command;

class StarmapGenerate extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $signature = 'starmap:generate {--no-render}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Generate the starmap';

    /**
     * The generator instance.
     *
     * @var Generator
     */
    protected $generator;

    /**
     * The renderer instance.
     *
     * @var Renderer
     */
    protected $renderer;

    /**
     * Constructor.
     */
    public function __construct(Generator $generator, Renderer $renderer)
    {
        parent::__construct();

        $this->generator = $generator;
        $this->renderer = $renderer;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info(
            $this->prependTimestamp('Generating starmap...')
        );

        $this->generator->generate();

        if ($this->option('no-render')) {
            $this->info(
                $this->prependTimestamp('Generation complete!')
            );
        } else {
            $this->info(
                $this->prependTimestamp('Generation complete! Rendering starmap...')
            );

            $this->call('starmap:render');
        }
    }
}
