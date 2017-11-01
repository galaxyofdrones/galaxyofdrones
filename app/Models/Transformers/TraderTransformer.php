<?php

namespace Koodilab\Models\Transformers;

class TraderTransformer extends Transformer
{
    /**
     * The mission transformer instance.
     *
     * @var MissionTransformer
     */
    protected $missionTransformer;

    /**
     * Constructor.
     *
     * @param MissionTransformer $missionTransformer
     */
    public function __construct(MissionTransformer $missionTransformer)
    {
        $this->missionTransformer = $missionTransformer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Koodilab\Models\Grid $item
     */
    public function transform($item)
    {
        return [
            'missions' => $this->missionTransformer->transformCollection(
                $item->planet->findNotExpiredMissions()
            ),
        ];
    }
}
