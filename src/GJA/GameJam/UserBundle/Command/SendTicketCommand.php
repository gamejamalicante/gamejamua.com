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

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SendTicketCommand extends ContainerAwareCommand
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function configure()
    {
        $this->setName('gamejam:user:send-tickets')
            ->addOption('user', 'u', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'List of specific users', array());
    }

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container); // TODO: Change the autogenerated stub

        $this->mailer = $container->get('mailer');
        $this->twig = $container->get('twig');
        $this->entityManager = $container->get('doctrine.orm.entity_manager');

        $container->enterScope('request');
        $container->set('request', new Request(), 'request');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $compo = $this->entityManager->getRepository('GameJamCompoBundle:Compo')->findOneBy([], ['id' => 'DESC']);

        $applications = $compo->getClosedApplications();

        $specificUsers = $input->getOption('user');

        foreach ($applications as $application) {
            if ($application->isTicketSent()) {
                continue;
            }

            $user = $application->getUser();

            if (!empty($specificUsers) && !in_array($user->getUsername(), $specificUsers)) {
                continue;
            }

            $output->writeln("User: <info>" .$application->getUser()->getUsername(). "</info>");

            $ticketUrl =
                'http://'.
                $this->getContainer()->getParameter('domain') .
                $this->getContainer()->get('router')->generate(
                'gamejam_user_profile_ticket_pdf',
                array(
                    'user' => $user->getUsername(),
                    'token' => $user->getAutologinToken()
                ),
                UrlGeneratorInterface::ABSOLUTE_PATH
            );

            $output->writeln('PDF file: <info>' .$ticketUrl. '</info>');

            $ticketFile = file_get_contents($ticketUrl);
            $tmpPdf = '/tmp/ticket-' . $user->getUsername(). '.pdf';

            file_put_contents($tmpPdf, $ticketFile);

            $mail = \Swift_Message::newInstance()
                ->setSubject('[GameJam Alicante] ¡Tu entrada para la VI GameJam!')
                ->setBody(
                    $this->twig->render(
                        'GameJamUserBundle:Mail:ticket.html.twig',
                        array('ticket_url' => $ticketUrl)
                    )
                )
                ->setFrom($this->getContainer()->getParameter('main_email'))
                ->setTo($user->getEmail())
                ->setReplyTo($this->getContainer()->getParameter('main_email'))
                ->setContentType('text/html')
            ->attach(\Swift_Attachment::fromPath($tmpPdf));

            $this->mailer->send($mail);
        }
    }
}
