<?php

namespace GJA\GameJam\CompoBundle\Service;

use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\UserBundle\Entity\User;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendTeamInvitationMail(User $from, Team $to, $message)
    {
    }
}
