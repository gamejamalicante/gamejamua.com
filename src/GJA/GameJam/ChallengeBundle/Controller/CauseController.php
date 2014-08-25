<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\ChallengeBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\ChallengeBundle\Entity\Cause;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/retos-solidarios/{cause}")
 * @ParamConverter("cause", options={"mapping":{"cause":"nameSlug"}})
 */
class CauseController extends AbstractController
{
    /**
     * @Route("/", name="gamejam_challenge_cause")
     * @Template
     */
    public function indexAction(Cause $cause)
    {
        return ['cause' => $cause];
    }
}