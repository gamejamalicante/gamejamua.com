<?php

namespace GJA\GameJam\ChallengeBundle\Listener;

use Noxlogic\RateLimitBundle\Events\GenerateKeyEvent;

class RateLimitGenerateKeyListener
{
    public function onGenerateKey(GenerateKeyEvent $event)
    {
        $request = $event->getRequest();

        $clientIp = $request->getClientIp();

        $event->setKey(md5($clientIp));
    }
}
