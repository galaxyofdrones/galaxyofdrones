<?php

namespace App\Console\Commands;

use App\Console\Behaviors\PrependTimestamp;
use App\Contracts\Starmap\Renderer;
use Illuminate\Console\Command;

class StarmapRender extends Command
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
