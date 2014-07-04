<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \AntiMattr\GoogleBundle\GoogleBundle(),
            new \FOS\UserBundle\FOSUserBundle(),
            new \Presta\SitemapBundle\PrestaSitemapBundle(),
            new \Knp\Bundle\TimeBundle\KnpTimeBundle(),
            new \AMNL\RouterUnslashBundle\AMNLRouterUnslashBundle(),
            new \Ivory\GoogleMapBundle\IvoryGoogleMapBundle(),
            new \MZ\MailChimpBundle\MZMailChimpBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new \Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
            new \GJA\GameJam\CompoBundle\GameJamCompoBundle(),
            new \GJA\GameJam\GameBundle\GameJamGameBundle(),
            new \GJA\GameJam\UserBundle\GameJamUserBundle(),
            new \Ornicar\GravatarBundle\OrnicarGravatarBundle(),
            new \JMS\Payment\CoreBundle\JMSPaymentCoreBundle(),
            new \JMS\Payment\PaypalBundle\JMSPaymentPaypalBundle(),
            new \HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new \Endroid\Bundle\TwitterBundle\EndroidTwitterBundle(),
            new \Elao\ErrorNotifierBundle\ElaoErrorNotifierBundle(),
            new \Liip\MonitorBundle\LiipMonitorBundle(),
            new \AFM\Bundle\SiteStatusCheckerBundle\SiteStatusCheckerBundle(),
            new \Thrace\MediaBundle\ThraceMediaBundle(),
            new \Liip\ImagineBundle\LiipImagineBundle(),
            new \Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Liip\UrlAutoConverterBundle\LiipUrlAutoConverterBundle(),
        );

        if (in_array($this->getEnvironment(), array('rest_dev', 'rest_prod')))
        {
            $bundles[] = new \GJA\GameJam\ChallengeBundle\GameJamChallengeBundle();
            $bundles[] = new \FOS\RestBundle\FOSRestBundle();
            $bundles[] = new Noxlogic\RateLimitBundle\NoxlogicRateLimitBundle();
            $bundles[] = new \JMS\SerializerBundle\JMSSerializerBundle();
            $bundles[] = new \Snc\RedisBundle\SncRedisBundle();
        }

        if (in_array($this->getEnvironment(), array('dev', 'test', 'migration')))
        {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Webfactory\Bundle\ExceptionsBundle\WebfactoryExceptionsBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}