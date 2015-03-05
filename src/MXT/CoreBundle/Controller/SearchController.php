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
     * @Route("/kickass/search", name="kickass_search")
     * @Template("MXTCoreBundle:Search:index.html.twig")
     *
     * @return array
     */
    public function kickAssAction(Request $request)
    {
        $form = $this->createForm(new SearchType(), null, [
            'filters' => $this->container->getParameter('kickass.search.fields'),
            'page' => 1
        ]);

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $kickAssClient = $this->container->get('mxt_core.torrent.client.kickAss');

            $torrentList = $kickAssClient->request([
                $form->get('query')->getData(),
                $form->get('filter')->getData(),
                $form->get('page')->getData()
            ]);

            return [
                'form'     => $form->createView(),
                'torrents' => $torrentList
            ];
        }

        return [
            'form'      => $form->createView()
        ];
    }
}