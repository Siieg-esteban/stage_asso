<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

use App\Form\MakeprotoType;
use App\Form\MakejeuType;
use App\Form\MakeblogType;
use App\Form\NewMessageType;

use App\Entity\Blog;
use App\Entity\Jeu;
use App\Entity\Proto;
use App\Entity\Com;
use App\Entity\User;
use App\Entity\Imagejeuproto;
use App\Entity\Listecontributeur;
use App\Entity\Listecompetence;
use App\Entity\Competence;
use App\Entity\Messagerie;

class IndexController extends AbstractController
{
    /**
     * @Route("/listeblog", name="listeblog")
     */
    public function listeblog(Request $request): Response
    {
        $em=$this->getDoctrine()->getRepository(Blog::class);
        $Allblog=$em->findAll();
        $countblog=count($Allblog);

        $bloglist=array();
        for ($i=0;$i<$countblog;$i++) { 
            $bloglist[]=$Allblog[$i];
        }

        $blog = new Blog();
        $form = $this->createForm(MakeblogType::class, $blog);
        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testdata = $form->getData();

            $em=$this->getDoctrine()->getRepository(User::class);
            $test=$this->getUser()->getId();
            $userid=$em->findOneBy(array('id' => $test));

            $datetime = new \DateTime('@'.strtotime('now'));

            $blog->setTitre($testdata->getTitre());
            $blog->setContenue($testdata->getContenue());
            $blog->setType($testdata->getType());
            $blog->setJeu($testdata->getJeu());

            $blog->setDatetime($datetime);
            $blog->setAuteur($userid);

            $em=$this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('index/liste_blog.html.twig', [
            'form' => $form->createView(),
            'listeblog' => $bloglist,
        ]);
    }

    /**
     * @Route("/listejeu", name="listejeu")
     */
    public function listejeu(Request $request): Response
    {
        $em=$this->getDoctrine()->getRepository(Jeu::class);
        $Alljeu=$em->findAll();
        $countjeu=count($Alljeu);

        $jeulist=array();
        for ($i=0;$i<$countjeu;$i++) { 
            $jeulist[]=$Alljeu[$i];
        }

        $jeu = new Jeu();
        $form = $this->createForm(MakejeuType::class, $jeu);
        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testdata = $form->getData();

            $em=$this->getDoctrine()->getRepository(User::class);
            $test=$this->getUser()->getId();
            $userid=$em->findOneBy(array('id' => $test));

            $datetime = new \DateTime('@'.strtotime('now'));

            $jeu->setTitre($testdata->getTitre());
            $jeu->setContenue($testdata->getContenue());
            $jeu->setLien($testdata->getLien());
            $jeu->setEtat($testdata->getEtat());

            $jeu->setUpvote('0');
            $jeu->setDatetime($datetime);
            $jeu->setAuteur($userid);

            $em=$this->getDoctrine()->getManager();
            $em->persist($jeu);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('index/liste_jeu.html.twig', [
            'form' => $form->createView(),
            'listejeu' => $jeulist,
        ]);
    }

    /**
     * @Route("/listeproto", name="listeproto")
     */
    public function listeproto(Request $request): Response
    {
        $em=$this->getDoctrine()->getRepository(Proto::class);
        $Allproto=$em->findAll();
        $countproto=count($Allproto);

        $protolist=array();
        for ($i=0;$i<$countproto;$i++) { 
            $protolist[]=$Allproto[$i];
        }

        $proto = new Proto();
        $contrib = new Listecontributeur();
        $form = $this->createForm(MakeprotoType::class, $proto);
        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testdata = $form->getData();

            $em=$this->getDoctrine()->getRepository(User::class);
            $test=$this->getUser()->getId();
            $userid=$em->findOneBy(array('id' => $test));

            $datetime = new \DateTime('@'.strtotime('now'));

            $proto->setTitre($testdata->getTitre());
            $proto->setContenue($testdata->getContenue());
            
            $proto->setDatetime($datetime);
            $proto->setAuteur($userid);

            $em=$this->getDoctrine()->getManager();
            $em->persist($proto);
            $em->flush();
            
            $contrib->setType('proto');
            $contrib->setProto($proto);
            $contrib->setUser($this->getUser());

            $em=$this->getDoctrine()->getManager();
            $em->persist($contrib);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('index/liste_proto.html.twig', [
            'form' => $form->createView(),
            'listeproto' => $protolist,
        ]);
    }

    /**
     * @Route("/pageblog{id}", name="pageblog")
     */
    public function pageblog($id): Response
    {
        $em=$this->getDoctrine()->getRepository(Blog::class);
        $blog=$em->findOneby(array('id'=>$id));

        $em2=$this->getDoctrine()->getRepository(Com::class);
        $AllBlogCom=$em2->findBy(array('type'=>'blog'));
        $countCom=count($AllBlogCom);

        $em3=$this->getDoctrine()->getRepository(Imagejeuproto::class);
        $AllBlogImage=$em3->findBy(array('type'=>'blog'));
        $countImg=count($AllBlogImage);

        $comment=array();
        for ($i=0;$i<$countCom;$i++) {
            if ($AllBlogCom[$i]->getBlog()->getId()==$id) {
                $comment[]=$AllBlogCom[$i];
            } 
        }

        $images=array();
        for ($i=0;$i<$countImg;$i++) {
            if ($AllBlogImage[$i]->getBlog()->getId()==$id) {
                $images[]=$AllBlogImage[$i];
            } 
        }

        return $this->render('index/page_blog.html.twig', [
            'blog' => $blog,
            'comments' => $comment,
            'images' => $images,
        ]);
    }

    /**
     * @Route("/pagejeu{id}", name="pagejeu")
     */
    public function pagejeu($id): Response
    {
        $em=$this->getDoctrine()->getRepository(Jeu::class);
        $jeu=$em->findOneby(array('id'=>$id));

        $em2=$this->getDoctrine()->getRepository(Com::class);
        $AllJeuCom=$em2->findBy(array('type'=>'jeu'));
        $countCom=count($AllJeuCom);

        $em3=$this->getDoctrine()->getRepository(Imagejeuproto::class);
        $AllBlogImage=$em3->findBy(array('type'=>'blog'));
        $countImg=count($AllBlogImage);

        $em4=$this->getDoctrine()->getRepository(Blog::class);
        $bloglink=$em4->findBy(array('jeu'=>$jeu));

        $em5=$this->getDoctrine()->getRepository(Listecontributeur::class);
        $contribAll=$em5->findBy(array('type'=>'jeu'));
        $countContrib=count($contribAll);

        $comment=array();
        for ($i=0;$i<$countCom;$i++) {
            if ($AllJeuCom[$i]->getJeu()->getId()==$id) {
                $comment[]=$AllJeuCom[$i];
            } 
        }

        $images=array();
        for ($i=0;$i<$countImg;$i++) {
            if ($AllBlogImage[$i]->getBlog()->getId()==$id) {
                $images[]=$AllBlogImage[$i];
            } 
        }

        $contribList=array();
        for ($i=0;$i<$countContrib;$i++) {
            if ($contribAll[$i]->getJeu()==$jeu) {
                $contribList[]=$contribAll[$i];
            } 
        }

        return $this->render('index/page_jeu.html.twig', [
            'jeu' => $jeu,
            'comments' => $comment,
            'images' => $images,
            'listeblog' => $bloglink,
            'listecontrib' => $contribList,
        ]);
    }

    /**
     * @Route("/pageproto{id}", name="pageproto")
     */
    public function pageproto($id): Response
    {
        $em=$this->getDoctrine()->getRepository(Proto::class);
        $proto=$em->findOneby(array('id'=>$id));

        $em2=$this->getDoctrine()->getRepository(Com::class);
        $AllProtoCom=$em2->findBy(array('type'=>'proto'));
        $countCom=count($AllProtoCom);

        $em3=$this->getDoctrine()->getRepository(Imagejeuproto::class);
        $AllBlogImage=$em3->findBy(array('type'=>'blog'));
        $countImg=count($AllBlogImage);

        $em4=$this->getDoctrine()->getRepository(Listecontributeur::class);
        $contribAll=$em4->findBy(array('type'=>'proto'));
        $countContrib=count($contribAll);

        $comment=array();
        for ($i=0;$i<$countCom;$i++) {
            if ($AllProtoCom[$i]->getProto()->getId()==$id) {
                $comment[]=$AllProtoCom[$i];
            } 
        }

        $images=array();
        for ($i=0;$i<$countImg;$i++) {
            if ($AllBlogImage[$i]->getBlog()->getId()==$id) {
                $images[]=$AllBlogImage[$i];
            } 
        }

        $contribList=array();
        for ($i=0;$i<$countContrib;$i++) {
            if ($contribAll[$i]->getProto()==$proto) {
                $contribList[]=$contribAll[$i];
            } 
        }

        return $this->render('index/page_proto.html.twig', [
            'proto' => $proto,
            'comments' => $comment,
            'images' => $images,
            'listecontrib' => $contribList,
        ]);
    }

    /**
     * @Route("/commentaire", name="commentaire")
     */
    public function comment(Request $request): Response
    {
        if ($request->query->get("textComment")){
            $em=$this->getDoctrine()->getRepository(User::class);
            $test=$this->getUser()->getId();
            $userid=$em->findOneBy(array('id' => $test));

            $Comment = new Com();
            $datetime = new \DateTime('@'.strtotime('now'));

            if ($request->query->get("type")=="blog") {
                $emBlog=$this->getDoctrine()->getRepository(Blog::class);
                $pageId=$request->query->get("pageid");
                $realpage=$emBlog->findOneBy(array('id' => $pageId));
                $Comment->setBlog($realpage);  

            }elseif ($request->query->get("type")=="jeu") {
                $emJeu=$this->getDoctrine()->getRepository(Jeu::class);
                $pageId=$request->query->get("pageid");
                $realpage=$emJeu->findOneBy(array('id' => $pageId));
                $Comment->setJeu($realpage);

            }else {
                $emProto=$this->getDoctrine()->getRepository(Proto::class);
                $pageId=$request->query->get("pageid");
                $realpage=$emProto->findOneBy(array('id' => $pageId));
                $Comment->setProto($realpage);
            }
            
            $Comment->setContenue($request->query->get("textComment"));
            $Comment->setType($request->query->get("type"));
    
            $Comment->setDatetime($datetime);
            $Comment->setEnvoyer($userid);

            $em=$this->getDoctrine()->getManager();
            $em->persist($Comment);
            $em->flush();
        }
        if ($request->query->get("type")=="blog") {
            return $this->redirectToRoute('pageblog', ['id' => $pageId]);
        }elseif ($request->query->get("type")=="jeu") {
            return $this->redirectToRoute('pagejeu', ['id' => $pageId]);
        }else{
            return $this->redirectToRoute('pageproto', ['id' => $pageId]);
            // return $this->redirectToRoute('person_in_need_view', ['id' => $yourEntity->getId()]);
        }
    }

    /**
     * @Route("/pageuser{id}", name="pageuser")
     */
    public function pageuser(Request $request, $id): Response
    {
        $em=$this->getDoctrine()->getRepository(User::class);
        $theUser=$em->findOneby(array('id'=>$id));

        $em2=$this->getDoctrine()->getRepository(Listecontributeur::class);
        $contribUser=$em2->findBy(array('user'=>$theUser));
        $countContrib=count($contribUser);

        $em3=$this->getDoctrine()->getRepository(Blog::class);
        $blogUser=$em3->findBy(array('auteur'=>$theUser));

        $em4=$this->getDoctrine()->getRepository(Messagerie::class);
        $allMessage=$em4->findAll();
        $countAllMessage=count($allMessage);

        $em5=$this->getDoctrine()->getRepository(Listecompetence::class);
        $competence=$em5->findBy(array('user'=>$theUser));

        $em6=$this->getDoctrine()->getRepository(Competence::class);
        $typeCompetence=$em6->findAll();

        $contribList=array();
        for ($i=0;$i<$countContrib;$i++) {
            if ($contribUser[$i]->getType()=="jeu") {
                $contribList[]=$contribUser[$i];
            } 
        }

        $messageList=array();
        $personneList=array();
        for ($i=0;$i<$countAllMessage;$i++) {
            if ($allMessage[$i]->getEnvoyer()==$theUser or $allMessage[$i]->getReceveur()==$theUser) {
                $messageList[]=$allMessage[$i];
                if ($allMessage[$i]->getEnvoyer()==$theUser) {
                    if (in_array($allMessage[$i]->getReceveur(), $personneList)){}else{
                        $personneList[]=$allMessage[$i]->getReceveur();
                    }
                } elseif ($allMessage[$i]->getReceveur()==$theUser) {
                    if (in_array($allMessage[$i]->getEnvoyer(), $personneList)){}else{
                        $personneList[]=$allMessage[$i]->getEnvoyer();
                    }
                }
            } 
        }

        return $this->render('index/page_user.html.twig', [
            'user' => $theUser,
            'competences' => $competence,
            'typeCompetences' => $typeCompetence,
            'contribs' => $contribList,
            'blogs' => $blogUser,
            'personnes' => $personneList,
            'messages' => $messageList,
        ]);
    }

    /**
     * @Route("/pagemessagerie{id}", name="pagemessagerie")
     */
    public function pagemessagerie(Request $request, $id): Response
    {
        $em=$this->getDoctrine()->getRepository(User::class);
        $theUser=$em->findOneby(array('id'=>$id));

        $em2=$this->getDoctrine()->getRepository(Messagerie::class);
        $allMessage=$em2->findAll();
        $countAllMessage=count($allMessage);

        $messageList=array();
        for ($i=0;$i<$countAllMessage;$i++) {
            if ($allMessage[$i]->getEnvoyer()==$theUser or $allMessage[$i]->getReceveur()==$theUser) {
                if ($allMessage[$i]->getEnvoyer()==$this->getUser() or $allMessage[$i]->getReceveur()==$this->getUser()) {
                    $messageList[]=$allMessage[$i];
                } 
            } 
        }

        $message = new Messagerie();
        $form = $this->createForm(NewMessageType::class, $message);
        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testdata = $form->getData();

            $em=$this->getDoctrine()->getRepository(User::class);
            $test=$this->getUser()->getId();
            $userid=$em->findOneBy(array('id' => $test));

            $datetime = new \DateTime('@'.strtotime('now'));

            $message->setContenue($testdata->getContenue());

            $message->setDatetime($datetime);
            $message->setEnvoyer($userid);
            $message->setReceveur($theUser);

            $em=$this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('index/page_messagerie.html.twig', [
            'form' => $form->createView(),
            'user' => $theUser,
            'messages' => $messageList,
        ]);
    }

    /**
     * @Route("/newcontrib{id}", name="newcontrib")
     */
    public function newcontrib(Request $request, $id): Response
    {
        $em=$this->getDoctrine()->getRepository(Proto::class);
        $proto=$em->findOneBy(array('id'=>$id));

        $em2=$this->getDoctrine()->getRepository(Listecontributeur::class);
        $listcontrib=$em2->findBy(array('proto'=>$proto));
        $countlistcontrib=count($listcontrib);

        $test=$this->getUser();

        $contrib = new Listecontributeur();
        $dejacontrib=false;
        for ($i=0;$i<$countlistcontrib;$i++) {
            if ($listcontrib[$i]->getUser()==$test) {
                $dejacontrib=true;
            } 
        }
        if ($dejacontrib==false) {
            $contrib->setType('proto');
            $contrib->setProto($proto);
            $contrib->setUser($this->getUser());

            $em=$this->getDoctrine()->getManager();
            $em->persist($contrib);
            $em->flush();  
        }
        
        return $this->redirectToRoute('pageproto',['id' => $id]);        
    }

    /**
     * @Route("/competence", name="competence")
     */
    public function competence(Request $request): Response
    {
        if ($request->query->get("newCompetence")){
            $em=$this->getDoctrine()->getRepository(User::class);
            $test=$this->getUser()->getId();
            $userid=$em->findOneBy(array('id' => $test));

            $competence = new Listecompetence();
            $newCompId=$request->query->get("newCompetence");
            $em2=$this->getDoctrine()->getRepository(Competence::class);
            $CompaAjouter=$em2->findOneBy(array('id' => $newCompId));

            $competence->setCompetence($CompaAjouter);  
            $competence->setUser($userid);

            $em=$this->getDoctrine()->getManager();
            $em->persist($competence);
            $em->flush();
        }
        return $this->redirectToRoute('pageuser',['id' => $test]);      
    }
}

