<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Contracts\Starmap\Generator;
use Koodilab\Contracts\Starmap\Renderer;

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
     *
     * @param Generator $generator
     * @param Renderer  $renderer
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
