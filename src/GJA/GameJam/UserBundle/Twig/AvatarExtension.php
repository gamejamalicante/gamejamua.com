<?php

namespace GJA\GameJam\UserBundle\Twig;

use GJA\GameJam\UserBundle\Entity\User;
use Ornicar\GravatarBundle\Templating\Helper\GravatarHelper;

class AvatarExtension extends \Twig_Extension
{
    /**
     * @var GravatarHelper
     */
    protected $gravatarHelper;

    public function __construct(GravatarHelper $helper)
    {
        $this->gravatarHelper = $helper;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('user_avatar', array($this, 'getUserAvatar')),
        );
    }

    public function getUserAvatar($user, $size = 20)
    {
        if ($user instanceof User) {
            $avatarUrl = $user->getAvatarUrl();
            $email = $user->getEmail();
        } else {
            $avatarUrl = $user['avatarUrl'];
            $email = $user['email'];
        }

        if (!is_null($avatarUrl)) {
            return $avatarUrl;
        } elseif ($gravatarUrl = $this->gravatarHelper->exists($email)) {
            return $this->gravatarHelper->getUrl($email, $size);
        }

        return '/bundles/gamejamcompo/images/noavatar.jpg';
    }

    public function getName()
    {
        return 'gamejam_user_avatar';
    }
}
