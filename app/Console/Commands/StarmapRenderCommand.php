<?php

namespace Koodilab\Console\Commands;

use Illuminate\Console\Command;
use Koodilab\Console\Behaviors\PrependTimestamp;
use Koodilab\Contracts\Starmap\Renderer;

class StarmapRenderCommand extends Command
{
    use PrependTimestamp;

    /**
     * {@inheritdoc}
     */
    protected $signature = 'starmap:render';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Render the starmap';

    /**
     * The renderer instance.
     *
     * @var Renderer
     */
    protected $renderer;

    /**
     * Constructor.
     *
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        parent::__construct();

        $this->renderer = $renderer;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->renderer->render();

        $this->info(
            $this->prependTimestamp('Rendering complete! Have fun!')
        );
    }
}
