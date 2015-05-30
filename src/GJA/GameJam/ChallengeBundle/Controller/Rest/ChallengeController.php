<?php

namespace GJA\GameJam\ChallengeBundle\Controller\Rest;

use TrivialSense\FrameworkCommon\Controller\AbstractController;
use GJA\GameJam\ChallengeBundle\Entity\Challenge;
use GJA\GameJam\ChallengeBundle\Event\ChallengeCompletedEvent;
use GJA\GameJam\ChallengeBundle\Exception\ChallengeCompletedException;
use GJA\GameJam\ChallengeBundle\GameJamChallengeEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Noxlogic\RateLimitBundle\Annotation\RateLimit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/challenge/{challenge}")
 * @ParamConverter("challenge", options={"mapping":{"challenge":"token"}})
 */
class ChallengeController extends AbstractController
{
    /**
     * @Route("/complete", defaults={"_format":"json"}, name="gamejam_api_challenge_complete")
     * @Rest\View
     * @RateLimit(limit=10, period=3600)
     */
    public function completeAction(Challenge $challenge)
    {
        if ($challenge->isCompleted()) {
            throw new ChallengeCompletedException('Challenge is already completed');
        }

        $challenge->complete();

        $this->persistAndFlush($challenge);

        $result = array('result' => true, 'completions' => $challenge->getCompletions());

        if (!$challenge->getTemp() && !$challenge->isCompleted()) {
            $event = $this->dispatchEvent(
                GameJamChallengeEvents::CHALLENGE_COMPLETED,
                new ChallengeCompletedEvent($challenge, $challenge->getGame())
            );

            $result['extra'] = $event->getExtra();
        }

        return $result;
    }
}
