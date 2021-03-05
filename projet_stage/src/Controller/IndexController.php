<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blog;
use App\Entity\Jeu;
use App\Entity\Proto;

class IndexController extends AbstractController
{
    /**
     * @Route("/listeblog", name="listeblog")
     */
    public function listeblog(): Response
    {
        $em=$this->getDoctrine()->getRepository(Blog::class);
        $Allblog=$em->findAll();
        $countblog=count($Allblog);

        $blog=array();
        for ($i=0;$i<$countblog;$i++) { 
            $blog[]=$Allblog[$i];
        }

        return $this->render('index/liste_blog.html.twig', [
            'listeblog' => $blog,
        ]);
    }

    /**
     * @Route("/listejeu", name="listejeu")
     */
    public function listejeu(): Response
    {
        $em=$this->getDoctrine()->getRepository(Jeu::class);
        $Alljeu=$em->findAll();
        $countjeu=count($Alljeu);

        $jeu=array();
        for ($i=0;$i<$countjeu;$i++) { 
            $jeu[]=$Alljeu[$i];
        }

        return $this->render('index/liste_jeu.html.twig', [
            'listejeu' => $jeu,
        ]);
    }

    /**
     * @Route("/listeproto", name="listeproto")
     */
    public function listeproto(): Response
    {
        $em=$this->getDoctrine()->getRepository(Proto::class);
        $Allproto=$em->findAll();
        $countproto=count($Allproto);

        $proto=array();
        for ($i=0;$i<$countproto;$i++) { 
            $proto[]=$Allproto[$i];
        }

        return $this->render('index/liste_proto.html.twig', [
            'listeproto' => $proto,
        ]);
    }

    /**
     * @Route("/pageblog{id}", name="pageblog")
     */
    public function pageblog($id): Response
    {
        $em=$this->getDoctrine()->getRepository(Blog::class);
        $blog=$em->findOneby(array('id'=>$id));

        return $this->render('index/page_blog.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/pagejeu{id}", name="pagejeu")
     */
    public function pagejeu($id): Response
    {
        $em=$this->getDoctrine()->getRepository(Jeu::class);
        $jeu=$em->findOneby(array('id'=>$id));

        return $this->render('index/page_jeu.html.twig', [
            'jeu' => $jeu,
        ]);
    }

    /**
     * @Route("/pageproto{id}", name="pageproto")
     */
    public function pageproto($id): Response
    {
        $em=$this->getDoctrine()->getRepository(Proto::class);
        $proto=$em->findOneby(array('id'=>$id));

        return $this->render('index/page_proto.html.twig', [
            'proto' => $proto,
        ]);
    }
}