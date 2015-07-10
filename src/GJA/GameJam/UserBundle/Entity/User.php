<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Entity\User as BaseUser;
use GJA\GameJam\CompoBundle\Entity\Achievement;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\CompoApplication;
use GJA\GameJam\CompoBundle\Entity\Contributor;
use GJA\GameJam\CompoBundle\Entity\Notification;
use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\CompoBundle\Entity\WaitingList;
use GJA\GameJam\GameBundle\Entity\Game;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="GJA\GameJam\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="gamejam_users",
 *      uniqueConstraints={@UniqueConstraint(name="autologin_idx", columns={"autologinToken"})}
 * )
 * @UniqueEntity(fields={"username"}, message="Este nombre de usuario ya existe")
 * @UniqueEntity(fields={"email"}, message="El email ya está en uso")
 */
class User extends BaseUser implements EncoderAwareInterface
{
    const SEX_MALE = 0;
    const SEX_FEMALE = 1;

    /**
     * @Assert\Regex(pattern="/^[a-z0-9_-]{3,20}$/i", message="El nombre de usuario no es válido")
     */
    protected $username;

    /**
     * @Assert\Regex(pattern="/^^(?=.*\d).{5,100}$$/", message="La contraseña es muy insegura")
     */
    protected $plainPassword;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $registeredAt;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\GameBundle\Entity\Game", mappedBy="user")
     *
     * @var Game[]
     */
    protected $games;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Team", inversedBy="users", fetch="EAGER")
     * @ORM\JoinTable(name="gamejam_users_teams")
     */
    protected $teams;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\TeamInvitation", mappedBy="target", fetch="LAZY")
     */
    protected $teamInvitationsReceived;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\TeamInvitation", mappedBy="sender", fetch="LAZY")
     */
    protected $teamInvitationsSent;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\CompoApplication", mappedBy="user")
     */
    protected $applications;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Activity", mappedBy="user")
     * @ORM\OrderBy({"date"="DESC"})
     */
    protected $activity;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Notification", mappedBy="users")
     */
    protected $notifications;

    /**
     * @ORM\OneToMany(targetEntity="AchievementGranted", mappedBy="user")
     */
    protected $achievements;

    /**
     * @ORM\Column(type="integer")
     */
    protected $coins = 100;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $nickname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $avatarUrl;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $birthDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $sex;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $siteUrl;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $presentation;

    /**
     * @ORM\OneToOne(targetEntity="GJA\GameJam\CompoBundle\Entity\Contributor", mappedBy="user")
     */
    protected $contributor;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $publicProfile = true;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $publicEmail = false;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Notification", mappedBy="usersRead")
     */
    protected $readNotifications;

    /**
     * @Assert\NotBlank
     * @Assert\True
     */
    protected $termsAccepted = true;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $allowCommunications = true;

    /**
     * @ORM\Column(type="json_array")
     */
    protected $oauthTokens = array();

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $twitter;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $legacyPassword = false;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\WaitingList", mappedBy="user")
     */
    protected $waitingLists;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastIp;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $autologinToken;

    public function __construct()
    {
        parent::__construct();
        $this->autologinToken = sha1(time().uniqid());
    }

    /**
     * @param mixed $achievements
     */
    public function setAchievements($achievements)
    {
        $this->achievements = $achievements;
    }

    /**
     * @return AchievementGranted[]
     */
    public function getAchievements()
    {
        return $this->achievements;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $avatarUrl
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;
    }

    /**
     * @return mixed
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $coins
     */
    public function setCoins($coins)
    {
        $this->coins = $coins;
    }

    /**
     * @return mixed
     */
    public function getCoins()
    {
        return $this->coins;
    }

    /**
     * @param mixed $games
     */
    public function setGames($games)
    {
        $this->games = $games;
    }

    public function getGames()
    {
        return $this->games;
    }

    public function getGameForCompo(Compo $compo)
    {
        foreach ($this->getGames() as $game) {
            if ($game->getCompo() === $compo) {
                return $game;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getAllGames()
    {
        $games = new ArrayCollection();

        foreach ($this->getTeams() as $team) {
            if ($game = $team->getGame()) {
                $games->add($game);
            }
        }

        foreach ($this->getGames() as $game) {
            if (!$games->contains($game)) {
                $games->add($game);
            }
        }

        return $games;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname ?: $this->username;
    }

    /**
     * @param mixed $notifications
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param mixed $presentation
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
    }

    /**
     * @return mixed
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @param mixed $publicEmail
     */
    public function setPublicEmail($publicEmail)
    {
        $this->publicEmail = $publicEmail;
    }

    /**
     * @return mixed
     */
    public function getPublicEmail()
    {
        return $this->publicEmail;
    }

    /**
     * @param mixed $publicProfile
     */
    public function setPublicProfile($publicProfile)
    {
        $this->publicProfile = $publicProfile;
    }

    /**
     * @return mixed
     */
    public function getPublicProfile()
    {
        return $this->publicProfile;
    }

    /**
     * @param mixed $readNotifications
     */
    public function setReadNotifications($readNotifications)
    {
        $this->readNotifications = $readNotifications;
    }

    /**
     * @return mixed
     */
    public function getReadNotifications()
    {
        return $this->readNotifications;
    }

    /**
     * @param mixed $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param mixed $siteUrl
     */
    public function setSiteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl;
    }

    /**
     * @return mixed
     */
    public function getSiteUrl()
    {
        return $this->siteUrl;
    }

    /**
     * @param mixed $teams
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;
    }

    /**
     * @return Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param mixed $registeredAt
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;
    }

    /**
     * @return mixed
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

    public function __toString()
    {
        return $this->getNickname();
    }

    public function getShouts()
    {
        return $this->getActivity()->filter(function (Activity $activity) {
            return $activity->getType() == Activity::TYPE_SHOUT;
        });
    }

    /**
     * @param mixed $termsAccepted
     */
    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = $termsAccepted;
    }

    /**
     * @return mixed
     */
    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    /**
     * @param CompoApplication[] $applications
     */
    public function setApplications($applications)
    {
        $this->applications = $applications;
    }

    /**
     * @return CompoApplication[]
     */
    public function getApplications()
    {
        return $this->applications;
    }

    public function hasAppliedTo(Compo $compo)
    {
        foreach ($this->getApplications() as $application) {
            if ($compo === $application->getCompo()) {
                if ($application->isCompleted()) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    public function getApplicationTo(Compo $compo)
    {
        foreach ($this->getApplications() as $application) {
            if ($compo === $application->getCompo()) {
                if ($application->isCompleted()) {
                    return $application;
                }
            }
        }

        return;
    }

    public function getOpenApplicationTo(Compo $compo)
    {
        foreach ($this->getApplications() as $application) {
            if ($compo === $application->getCompo()) {
                if (!$application->isCompleted()) {
                    return $application;
                }
            }
        }

        return;
    }

    public function hasAchievement(Achievement $achievement)
    {
        foreach ($this->getAchievements() as $achievementGranted) {
            if ($achievementGranted->getAchievement() === $achievement) {
                return true;
            }
        }

        return false;
    }

    public function isMember()
    {
        if ($this->hasRole('ROLE_MEMBER')) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $allowCommunications
     */
    public function setAllowCommunications($allowCommunications)
    {
        $this->allowCommunications = $allowCommunications;
    }

    /**
     * @return mixed
     */
    public function getAllowCommunications()
    {
        return $this->allowCommunications;
    }

    public static function getAvailableSexes()
    {
        return [
            self::SEX_MALE => 'Hombre',
            self::SEX_FEMALE => 'Mujer',
        ];
    }

    /**
     * @param mixed $oauthTokens
     */
    public function setOauthTokens($oauthTokens)
    {
        $this->oauthTokens = $oauthTokens;
    }

    /**
     * @return mixed
     */
    public function getOauthTokens()
    {
        return $this->oauthTokens;
    }

    public function setOAuthAccountUserId($service, $username)
    {
        $this->oauthTokens[$service]['username'] = $username;
    }

    public function setOauthAccountAccessToken($service, $token)
    {
        $this->oauthTokens[$service]['token'] = $token;
    }

    public function hasOauthServiceConnected($service)
    {
        return isset($this->oauthTokens[$service]);
    }

    public function getOauthTwitterUsername()
    {
        if (isset($this->oauthTokens['twitter']['username'])) {
            return $this->oauthTokens['twitter']['username'];
        }

        return;
    }

    /**
     * @param mixed $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $legacyPassword
     */
    public function setLegacyPassword($legacyPassword)
    {
        $this->legacyPassword = $legacyPassword;
    }

    /**
     * @return mixed
     */
    public function getLegacyPassword()
    {
        return $this->legacyPassword;
    }

    public function getEncoderName()
    {
        if ($this->legacyPassword) {
            return 'legacy_encoder';
        }

        return;
    }

    public function getAge()
    {
        $now = new \DateTime('now');

        return $now->diff($this->getBirthDate())->format('%Y');
    }

    public function hasReadNotification(Notification $notification)
    {
        return $this->readNotifications->contains($notification);
    }

    /**
     * @param Compo $compo
     *
     * @return Team
     */
    public function getTeamForCompo(Compo $compo)
    {
        foreach ($this->getTeams() as $team) {
            if ($team->getCompo() === $compo) {
                return $team;
            }
        }

        return;
    }

    public function addToTeam(Team $team)
    {
        $this->teams[] = $team;
    }

    public function getLevel()
    {
        $level = 0;

        foreach ($this->getApplications() as $application) {
            if ($application->isCompleted()) {
                $level++;
            }
        }

        return $level;
    }

    public function substractCoins($coins)
    {
        $this->coins -= (int) $coins;
    }

    /**
     * @param mixed $teamInvitationsReceived
     */
    public function setTeamInvitationsReceived($teamInvitationsReceived)
    {
        $this->teamInvitationsReceived = $teamInvitationsReceived;
    }

    /**
     * @return mixed
     */
    public function getTeamInvitationsReceived()
    {
        return $this->teamInvitationsReceived;
    }

    /**
     * @param mixed $teamInvitationsSent
     */
    public function setTeamInvitationsSent($teamInvitationsSent)
    {
        $this->teamInvitationsSent = $teamInvitationsSent;
    }

    /**
     * @return mixed
     */
    public function getTeamInvitationsSent()
    {
        return $this->teamInvitationsSent;
    }

    public function removeFromTeam(Team $team)
    {
        $this->teams->removeElement($team);
    }

    public function getWaitingListFor(Compo $compo)
    {
        foreach ($this->getWaitingLists() as $waitingList) {
            if ($waitingList->getCompo() === $compo) {
                return $waitingList;
            }
        }
    }

    /**
     * @param mixed $waitingLists
     */
    public function setWaitingLists($waitingLists)
    {
        $this->waitingLists = $waitingLists;
    }

    /**
     * @return WaitingList[]
     */
    public function getWaitingLists()
    {
        return $this->waitingLists;
    }

    /**
     * @param mixed $lastIp
     */
    public function setLastIp($lastIp)
    {
        $this->lastIp = $lastIp;
    }

    /**
     * @return mixed
     */
    public function getLastIp()
    {
        return $this->lastIp;
    }

    /**
     * @param mixed $autologinToken
     */
    public function setAutologinToken($autologinToken)
    {
        $this->autologinToken = $autologinToken;
    }

    /**
     * @return mixed
     */
    public function getAutologinToken()
    {
        return $this->autologinToken;
    }

    public function getJudgedCompos()
    {
        if (is_null($contributor = $this->getContributor())) {
            return;
        }

        $compos = array();

        foreach ($contributor->getComposJudged() as $compo) {
            if (!$compo->hasJuryVotingEnded()) {
                $compos[] = $compo;
            }
        }

        return $compos;
    }

    /**
     * @param mixed $contributor
     */
    public function setContributor($contributor)
    {
        $this->contributor = $contributor;
    }

    /**
     * @return Contributor
     */
    public function getContributor()
    {
        return $this->contributor;
    }

    public function isAdmin()
    {
        return $this->hasRole('ROLE_ADMIN');
    }
}
