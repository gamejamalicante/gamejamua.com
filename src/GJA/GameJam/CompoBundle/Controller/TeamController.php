<?php

namespace GJA\GameJam\CompoBundle\Controller;

use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/compos/{compo}/{team}")
 * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
 * @ParamConverter("team", options={"mapping":{"team":"nameSlug"}})
 */
class TeamController
{
    /**
     * @Route("/enviar-invitacion/{user}", name="gamejam_compo_team_send_invitation")
     */
    public function sendInvitation(Compo $compo, Team $team, User $user)
    {
    }

    /**
     * @Route("/aceptar-invitacion", name="gamejam_compo_team_accept_invitation")
     */
    public function acceptInvitation(Team $team)
    {
    }

    public function sendRequest(Team $team)
    {

    }
} 