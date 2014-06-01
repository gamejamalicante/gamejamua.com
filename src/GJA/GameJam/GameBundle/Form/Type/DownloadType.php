<?php

namespace GJA\GameJam\GameBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class DownloadType extends AbstractType
{
    /**
     * @{inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_game_download';
    }
} 