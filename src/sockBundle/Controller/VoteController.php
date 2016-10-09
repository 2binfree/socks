<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 09/10/16
 * Time: 09:32
 */

namespace sockBundle\Controller;

use sockBundle\Repository\SockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class VoteController extends Controller
{
    const MAX_PER_PAGE = 9;

    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $socks = $em->getRepository('sockBundle:Sock')->getByPage($page, self::MAX_PER_PAGE);

        $total = count($socks);
        $maxPage = (int)($total / SockRepository::MAX_RESULT);
        return $this->render('vote/index.html.twig', array(
            'maxPage'       => $maxPage,
            'socks'         => $socks,
            'page'          => $page,
            'voteUrl'       => $this->generateUrl("vote_vote")
        ));
    }

    public function voteAction(Request $request){
        $images = explode(",", $request->request->get('images'));
        $name = $request->request->get('name');
        return new Response(dump($images) . " " . $name);

    }
}