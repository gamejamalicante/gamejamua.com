<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\UserBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\UserBundle\Entity\User;
use GJA\GameJam\UserBundle\Form\Type\ShoutType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{user}", name="gamejam_user_profile")
     * @ParamConverter("user", options={"mapping":{"user":"username"}})
     * @Template
     */
    public function profileAction(User $user)
    {
        return ['user' => $user];
    }
} 