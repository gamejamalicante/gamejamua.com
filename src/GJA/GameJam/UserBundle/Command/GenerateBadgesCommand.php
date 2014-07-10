<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\UserBundle\Command;

use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\UserBundle\Entity\User;
use Gregwar\Image\Image;
use Ornicar\GravatarBundle\Templating\Helper\GravatarHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateBadgesCommand extends ContainerAwareCommand
{
    protected $cacheDir;

    protected function configure()
    {
        $this->setName('gamejam:user:generate-badges')
            ->addOption('username', null, InputOption::VALUE_REQUIRED, 'Pick an username', 'all')
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Limit when all');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cacheDir = $this->getContainer()->getParameter('kernel.cache_dir');
        $username = $input->getOption('username');
        $limit = $input->getOption('limit') ?: PHP_INT_MAX;

        if($username === 'all') {
            $users = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository("GameJamUserBundle:User")->findAll();
        } else {
            $users = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository("GameJamUserBundle:User")->findBy(['username' => $username]);
        }

        foreach ($users as $key => $user) {
            $output->writeln('Generating badge for user: <info>' .$user. '</info>');
            $badgeFilename = $this->generateBadge($user);

            if($badgeFilename !== false)
                $output->writeln('Generated badge: <info>' .$badgeFilename. '</info>\\n');

            if($key >= $limit) {
                break;
            }
        }
    }

    protected function generateBadge(User $user)
    {
        /** @var Compo $compo */
        $compo = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('GameJamCompoBundle:Compo')->findOneBy(['open' => true]);

        if(!$user->hasAppliedTo($compo)) {
            return false;
        }

        $badgeNormalFile = __DIR__ . '/../Resources/badges/badge_normal_vacio.png';
        $badgeOrgFile = __DIR__ . '/../Resources/badges/badge_org_vacio.png';

        $fontSci = __DIR__ . '/../Resources/badges/SciFly-Sans.ttf';

        if($user->hasRole('ROLE_STAFF')) {
            $badge = $badgeOrgFile;
        } else {
            $badge = $badgeNormalFile;
        }

        $image = new Image($badge);
        $image->useFallback(false);

        $this->drawAvatar($image, $user);
        $this->drawUsername($image, $user, $fontSci);
        $this->drawTwitter($image, $user, $fontSci);
        $this->drawLevel($image, $user, $fontSci);

        if($team = $user->getTeamForCompo($compo))
            $this->drawTeam($image, $team->getName(), $fontSci);

        $filename = __DIR__ . '/../Resources/badges/generated/' .$user->getUsername(). '.png';

        $image->save($filename);

        return $filename;
    }

    protected function drawAvatar(Image $image, User $user)
    {
        $avatarUrl = $user->getAvatarUrl();

        if (!is_null($avatarUrl) && $this->remoteImageExists($avatarUrl)) {
            $avatar = $avatarUrl;
        } else {
            // check gravatar
            /** @var GravatarHelper $gravatar */
            $gravatar = $this->getContainer()->get('templating.helper.gravatar');

            if ($gravatar->exists($user->getEmail())) {
                $avatar = $gravatar->getUrl($user->getEmail(), '120');
            } else {
                $avatar = __DIR__ . '/../../CompoBundle/Resources/public/images/noavatar.jpg';
            }
        }

        $avatarImage = new Image($avatar, 120, 120);
        $avatarImage->cropResize(120, 120);

        $image->merge($avatarImage, 20, 33, 120, 120);
    }

    protected function drawUsername(Image $image, User $user, $fontFile)
    {
        $image->write($fontFile, $user->getUsername(), 155, 55, 23, 0);
    }

    protected function drawTwitter(Image $image, User $user, $fontFile)
    {
        if($twitter = $user->getTwitter()) {
            $image->write($fontFile, '@' . $twitter, 155, 80, 15, 0, 0x6363B0);
        }
    }

    private function drawLevel(Image $image, User $user, $fontSci)
    {
        $image->write($fontSci, $user->getLevel() ?: 0, 40, 172, 14);
    }

    private function drawTeam(Image $image, $team, $fontSci)
    {
        $image->write($fontSci, $team, 20, 192, 14);
    }

    private function remoteImageExists($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(curl_exec($ch)!==FALSE)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
} 