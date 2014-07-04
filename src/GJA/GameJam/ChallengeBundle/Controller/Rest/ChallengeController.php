<?php

namespace GJA\GameJam\ChallengeBundle\Controller\Rest;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\ChallengeBundle\Entity\Challenge;
use GJA\GameJam\ChallengeBundle\Exception\ChallengeCompletedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/challenge/{challenge}", name="gamejam_api_challenge_complete")
 * @ParamConverter("challenge", options={"mapping":{"challenge":"token"}})
 */
class ChallengeController extends AbstractController
{
    /**
     * @Route("/complete", defaults={"_format":"json"})
     * @Rest\View
     * @RateLimit(limit=10, period=3600)
     */
    public function completeAction(Challenge $challenge)
    {
        if($challenge->isCompleted())
        {
            throw new ChallengeCompletedException('Challenge is already completed');
        }
    }
}