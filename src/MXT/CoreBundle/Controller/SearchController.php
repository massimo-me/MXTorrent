<?php
/**
 * MXTorrent
 * © Chiarillo Massimo
 *
 * MXT\CoreBundle\Controller\SearchController
 *
 */
namespace MXT\CoreBundle\Controller;

use MXT\CoreBundle\Form\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template("MXTCoreBundle:Search:index.html.twig")
     *
     * @return array
     */
    public function homeAction(Request $request)
    {
        $form = $this->createForm(new SearchType(), null, [
            'filters' => $this->container->getParameter('kickass.search.fields')
        ]);

        $form->handleRequest($request);

        $formView = $form->createView();

        if ($form->isValid())
        {
            $kickAssClient = $this->container->get('mxt_core.torrent.client.kickAss');

            $torrents = $kickAssClient->request([
                $form->get('query')->getData(),
                $form->get('filter')->getData(),
                $form->get('page')->getData()
            ]);

            $this->saveResult($torrents);

            return compact('formView', 'torrents');
        }

        return compact('formView');
    }

    private function saveResult(array $torrents)
    {
        $torrentService = $this->container->get('mxt_core.torrent_utils');

        foreach($torrents as $torrent) {
            $torrentService->create($torrent);
        }
    }
}