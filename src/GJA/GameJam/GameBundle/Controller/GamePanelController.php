<?php

namespace GJA\GameJam\GameBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\GameBundle\Event\GameActivityCreationEvent;
use GJA\GameJam\GameBundle\Event\GameActivityInfoUpdateEvent;
use GJA\GameJam\GameBundle\Form\Type\GameType;
use GJA\GameJam\GameBundle\GameJamGameEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/panel/juegos")
 */
class GamePanelController extends AbstractController
{
    /**
     * @Route("/crear", name="gamejam_game_panel_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $game = new Game();
        $game->setIsNew(true);
        $game->setUser($this->getUser());

        $form = $this->createForm(new GameType(), $game);

        if($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $this->persistAndFlush($game, true);

                // game creation event
                $this->dispatchEvent(GameJamGameEvents::ACTIVITY_CREATION, new GameActivityCreationEvent($this->getUser(), $game));

                return $this->redirectToPath('gamejam_game', ['game' => $game->getNameSlug()]);
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/editar/{game}", name="gamejam_game_panel_edit")
     * @ParamConverter("game", options={"mapping":{"game":"nameSlug"}})
     * @Template()
     */
    public function editAction(Request $request, Game $game)
    {
        if(!$game->isUserAllowedToEdit($this->getUser()))
            throw new AccessDeniedException;

        $form = $this->createForm(new GameType(), $game);

        if($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $media = $game->getMedia();
                $image = $game->getImage();

                $this->persist($image);

                foreach($media as $mediaElement)
                {
                    $mediaElement->setGame($game);
                    $this->persist($mediaElement);
                }

                $this->persist($game);
                $this->flush();

                $this->addSuccessMessage("Cambios en el juego guardados");

                // game creation event
                $this->dispatchEvent(GameJamGameEvents::ACTIVITY_INFO_UPDATE, new GameActivityInfoUpdateEvent($this->getUser(), $game));

                return $this->redirectToPath('gamejam_game_panel_edit', ['game' => $game->getNameSlug()]);
            }
        }

        return ['form' => $form->createView(), 'game' => $game];
    }

    /**
     * @Route("/delete/{game}", name="gamejam_game_panel_delete")
     * @ParamConverter("game", options={"mapping":{"game":"nameSlug"}})
     */
    public function deleteAction(Game $game)
    {
        if(!$game->isUserAllowedToDelete($this->getUser()))
        {
            $this->addSuccessMessage("<strong>Error:</strong> no puedes eliminar este juego ya que pertenece a tu equipo. Por favor, contacta con el líder del equipo.");

            return $this->redirectToPath('gamejam_game_panel_edit', ['game' => $game->getNameSlug()]);
        }

        if($game->getCompo())
        {
            // game from compo, softdelete
            $this->deleteAndFlush($game);
        }
        else
        {
            // not from compo, hard delete
            $this->getEntityManager()->getFilters()->disable("softdeleteable");

            $this->deleteAndFlush($game);
        }

        $this->addSuccessMessage("Hemos eliminado el juego con éxito");

        return $this->redirectToPath('gamejam_game_panel_create');
    }
}