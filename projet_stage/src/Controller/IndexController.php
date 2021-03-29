<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Form\MakeprotoType;
use App\Form\MakejeuType;
use App\Form\MakeblogType;
use App\Form\MakecomType;
use App\Form\UpdatecomType;
use App\Form\NewMessageType;
use App\Form\UpdateMessageType;

use App\Entity\Blog;
use App\Entity\Jeu;
use App\Entity\Proto;
use App\Entity\Com;
use App\Entity\User;
use App\Entity\Imagejeuproto;
use App\Entity\Imagecommunication;
use App\Entity\Fichiercommunication;
use App\Entity\Listecontributeur;
use App\Entity\Listecompetence;
use App\Entity\Competence;
use App\Entity\Messagerie;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

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

            $newImages=$form->get("image")->getData();

            $em=$this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            $countnewImages=count($newImages);
            
            for ($i=0; $i < $countnewImages; $i++) { 
                $image=$newImages[$i];

                $encoded_data = base64_encode(file_get_contents($image)); 

                $imagetest = new Imagejeuproto();

                $imagetest->setType("blog");
                $imagetest->setBlog($blog);
                $imagetest->setImage($encoded_data);

                $em=$this->getDoctrine()->getManager();
                $em->persist($imagetest);
                $em->flush();
            }

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
        $contrib = new Listecontributeur();
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

            $newImages=$form->get("image")->getData();

            $em=$this->getDoctrine()->getManager();
            $em->persist($jeu);
            $em->flush();

            $contrib->setType('jeu');
            $contrib->setJeu($jeu);
            $contrib->setUser($this->getUser());

            $em=$this->getDoctrine()->getManager();
            $em->persist($contrib);
            $em->flush();

            $countnewImages=count($newImages);

            for ($i=0; $i < $countnewImages; $i++) { 
                $image=$newImages[$i];

                $encoded_data = base64_encode(file_get_contents($image)); 

                $imagetest = new Imagejeuproto();

                $imagetest->setType("jeu");
                $imagetest->setJeu($jeu);
                $imagetest->setImage($encoded_data);

                $em=$this->getDoctrine()->getManager();
                $em->persist($imagetest);
                $em->flush();
            }

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

            $newImages=$form->get("image")->getData();

            $em=$this->getDoctrine()->getManager();
            $em->persist($contrib);
            $em->flush();

            $countnewImages=count($newImages);

            for ($i=0; $i < $countnewImages; $i++) { 
                $image=$newImages[$i];

                $encoded_data = base64_encode(file_get_contents($image)); 

                $imagetest = new Imagejeuproto();

                $imagetest->setType("proto");
                $imagetest->setProto($proto);
                $imagetest->setImage($encoded_data);

                $em=$this->getDoctrine()->getManager();
                $em->persist($imagetest);
                $em->flush();
            }

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
        $AllBlogImage=$em3->findBy(array('type'=>'jeu'));
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
            if ($AllBlogImage[$i]->getJeu()->getId()==$id) {
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
    public function pageproto($id,Request $request,SluggerInterface $slugger): Response
    {
        $em=$this->getDoctrine()->getRepository(Proto::class);
        $proto=$em->findOneby(array('id'=>$id));

        $em2=$this->getDoctrine()->getRepository(Com::class);
        $AllProtoCom=$em2->findBy(array('type'=>'proto'));
        $countCom=count($AllProtoCom);

        $em3=$this->getDoctrine()->getRepository(Imagejeuproto::class);
        $AllBlogImage=$em3->findBy(array('type'=>'proto'));
        $countImg=count($AllBlogImage);

        $em4=$this->getDoctrine()->getRepository(Listecontributeur::class);
        $contribAll=$em4->findBy(array('type'=>'proto'));
        $countContrib=count($contribAll);

        $em5=$this->getDoctrine()->getRepository(Imagecommunication::class);
        $allimagescom=$em5->findBy(array('type'=>'com'));;
        $imagecomtest=array();

        $em6=$this->getDoctrine()->getRepository(Fichiercommunication::class);
        $allimagescom=$em6->findBy(array('type'=>'com'));;
        $fichiercomtest=array();

        $comment=array();
        for ($i=0;$i<$countCom;$i++) {
            if ($AllProtoCom[$i]->getProto()->getId()==$id) {
                $comment[]=$AllProtoCom[$i];
                $test=$em5->findBy(array('com'=>$AllProtoCom[$i]));
                $test3=$em6->findBy(array('com'=>$AllProtoCom[$i]));
                if ($test) {
                    foreach ($test as $test2) {
                        $imagecomtest[]=$test2;
                    }
                }
                if ($test3) {
                    foreach ($test3 as $test4) {
                        $fichiercomtest[]=$test4;
                    }
                }

            } 
        }

        $images=array();
        for ($i=0;$i<$countImg;$i++) {
            if ($AllBlogImage[$i]->getProto()->getId()==$id) {
                $images[]=$AllBlogImage[$i];
            } 
        }

        $contribList=array();
        for ($i=0;$i<$countContrib;$i++) {
            if ($contribAll[$i]->getProto()==$proto) {
                $contribList[]=$contribAll[$i];
            } 
        }

        $Comment = new Com();

        $form = $this->createForm(MakecomType::class, $Comment);
        $form->handlerequest($request);
        $form2 = $this->createForm(UpdatecomType::class, $Comment);
        $form2->handlerequest($request);

        // form create com page proto
        if ($form->isSubmitted() && $form->isValid()) {
            $testdata = $form->getData();

            $userid=$this->getUser();
            $datetime = new \DateTime('@'.strtotime('now'));

            $Comment->setProto($proto);
            $Comment->setType('proto');
            $Comment->setDatetime($datetime);
            $Comment->setEnvoyer($userid);
            $Comment->setContenue($testdata->getContenue());

            $newImages=$form->get("image")->getData();

            $em=$this->getDoctrine()->getManager();
            $em->persist($Comment);
            $em->flush();

            $countnewImages=count($newImages);

            for ($i=0; $i < $countnewImages; $i++) { 
                $image=$newImages[$i];

                $encoded_data = base64_encode(file_get_contents($image)); 

                $imagetest = new Imagecommunication();

                $imagetest->setType("com");
                $imagetest->setCom($Comment);
                $imagetest->setImage($encoded_data);

                $em=$this->getDoctrine()->getManager();
                $em->persist($imagetest);
                $em->flush();
            }

            $uploadFile = $form->get('upload')->getData();

            $countnewUpload=count($uploadFile);

            if ($uploadFile) {
                for ($i=0; $i < $countnewUpload; $i++) { 
                
                    $originalFilename = pathinfo($uploadFile[$i]->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFile[$i]->guessExtension();

                    // Move the file to the directory where uploads are stored
                    try {
                        $uploadFile[$i]->move(
                            $this->getParameter('upload_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'uploadFilename' property to store the PDF file name
                    // instead of its contents
                    $uploadDB = new Fichiercommunication();
                    $uploadDB->setLien($newFilename);
                    $uploadDB->setType('com');
                    $uploadDB->setCom($Comment);

                    $em=$this->getDoctrine()->getManager();
                    $em->persist($uploadDB);
                    $em->flush();
                }
            }
            return $this->redirect($request->getUri());
        }

        // form update com page proto
        if ($form2->isSubmitted() && $form2->isValid()) {
            $testdata = $form2->getData();
            $id = $form2->get("comid")->getData();

            $em=$this->getDoctrine()->getRepository(Com::class);
            $Comment=$em->findOneby(array('id'=>$id));

            $Comment->setContenue($form2->get("contenue2")->getData());
            
            $newImages=$form2->get("image2")->getData();

            $em=$this->getDoctrine()->getManager();
            $em->persist($Comment);
            $em->flush();

            $countnewImages=count($newImages);

            for ($i=0; $i < $countnewImages; $i++) { 
                $image=$newImages[$i];

                $encoded_data = base64_encode(file_get_contents($image)); 

                $imagetest = new Imagecommunication();

                $imagetest->setType("com");
                $imagetest->setCom($Comment);
                $imagetest->setImage($encoded_data);

                $em=$this->getDoctrine()->getManager();
                $em->persist($imagetest);
                $em->flush();
            }

            $uploadFile = $form2->get('upload2')->getData();

            $countnewUpload=count($uploadFile);

            if ($uploadFile) {
                for ($i=0; $i < $countnewUpload; $i++) { 
                
                    $originalFilename = pathinfo($uploadFile[$i]->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFile[$i]->guessExtension();

                    // Move the file to the directory where uploads are stored
                    try {
                        $uploadFile[$i]->move(
                            $this->getParameter('upload_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'uploadFilename' property to store the PDF file name
                    // instead of its contents
                    $uploadDB = new Fichiercommunication();
                    $uploadDB->setLien($newFilename);
                    $uploadDB->setType('com');
                    $uploadDB->setCom($Comment);

                    $em=$this->getDoctrine()->getManager();
                    $em->persist($uploadDB);
                    $em->flush();
                }
            }
            return $this->redirect($request->getUri());
        }

        return $this->render('index/page_proto.html.twig', [
            'form' => $form->createView(),
            'upform' => $form2->createView(),
            'proto' => $proto,
            'comments' => $comment,
            'images' => $images,
            'imagesCom' => $imagecomtest,
            'fichierCom' => $fichiercomtest,
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

            if ($request->query->get("imagesComment")){
                $newImages=$request->query->get("imagesComment");
                $countnewImages=count($newImages);

                for ($i=0; $i < $countnewImages; $i++) {
                    $image=$newImages[$i];
    
                    $encoded_data = base64_encode(file_get_contents($image)); 
    
                    $imagetest = new Imagecommunication();
    
                    $imagetest->setType("com");
                    $imagetest->setCom($Comment);
                    $imagetest->setImage($encoded_data);
    
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($imagetest);
                    $em->flush();
                }
            }  
        }

        if ($request->query->get("type")=="blog") {
            return $this->redirectToRoute('pageblog', ['id' => $pageId]);
        }elseif ($request->query->get("type")=="jeu") {
            return $this->redirectToRoute('pagejeu', ['id' => $pageId]);
        }else{
            return $this->redirectToRoute('pageproto', ['id' => $pageId]);
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
    public function pagemessagerie(Request $request, $id,SluggerInterface $slugger): Response
    {
        $em=$this->getDoctrine()->getRepository(User::class);
        $theUser=$em->findOneby(array('id'=>$id));

        $em2=$this->getDoctrine()->getRepository(Messagerie::class);
        $allMessage=$em2->findAll();
        $countAllMessage=count($allMessage);

        $em3=$this->getDoctrine()->getRepository(Imagecommunication::class);
        $allMessageImg=$em3->findby(array('type'=>'messagerie'));

        $em4=$this->getDoctrine()->getRepository(Fichiercommunication::class);
        $allfichiercom=$em4->findBy(array('type'=>'messagerie'));;

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
        $form2 = $this->createForm(UpdateMessageType::class, $message);
        $form2->handlerequest($request);

        // form create message page messagerie
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

            $newImages=$form->get("image")->getData();            

            $em=$this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $countnewImages=count($newImages);
            
            for ($i=0; $i < $countnewImages; $i++) { 
                $image=$newImages[$i];

                $encoded_data = base64_encode(file_get_contents($image)); 

                $imagetest = new Imagecommunication();

                $imagetest->setType("messagerie");
                $imagetest->setMessagerie($message);
                $imagetest->setImage($encoded_data);

                $em=$this->getDoctrine()->getManager();
                $em->persist($imagetest);
                $em->flush();
            }

            $uploadFile = $form->get('upload')->getData();

            $countnewUpload=count($uploadFile);

            if ($uploadFile) {
                for ($i=0; $i < $countnewUpload; $i++) { 
                
                    $originalFilename = pathinfo($uploadFile[$i]->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFile[$i]->guessExtension();

                    // Move the file to the directory where uploads are stored
                    try {
                        $uploadFile[$i]->move(
                            $this->getParameter('upload_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'uploadFilename' property to store the PDF file name
                    // instead of its contents
                    $uploadDB = new Fichiercommunication();
                    $uploadDB->setLien($newFilename);
                    $uploadDB->setType('messagerie');
                    $uploadDB->setMessagerie($message);

                    $em=$this->getDoctrine()->getManager();
                    $em->persist($uploadDB);
                    $em->flush();
                }
            }
            return $this->redirect($request->getUri());
        }

        // form update message page messagerie
        if ($form2->isSubmitted() && $form2->isValid()) {
            $testdata = $form2->getData();
            $id = $form2->get("comid")->getData();

            $em=$this->getDoctrine()->getRepository(Messagerie::class);
            $message=$em->findOneby(array('id'=>$id));

            $message->setContenue($form2->get("contenue2")->getData());
            
            $newImages=$form2->get("image2")->getData();

            $em=$this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $countnewImages=count($newImages);

            for ($i=0; $i < $countnewImages; $i++) { 
                $image=$newImages[$i];

                $encoded_data = base64_encode(file_get_contents($image)); 

                $imagetest = new Imagecommunication();

                $imagetest->setType("messagerie");
                $imagetest->setMessagerie($message);
                $imagetest->setImage($encoded_data);

                $em=$this->getDoctrine()->getManager();
                $em->persist($imagetest);
                $em->flush();
            }

            $uploadFile = $form2->get('upload2')->getData();

            $countnewUpload=count($uploadFile);

            if ($uploadFile) {
                for ($i=0; $i < $countnewUpload; $i++) { 
                
                    $originalFilename = pathinfo($uploadFile[$i]->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFile[$i]->guessExtension();

                    // Move the file to the directory where uploads are stored
                    try {
                        $uploadFile[$i]->move(
                            $this->getParameter('upload_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'uploadFilename' property to store the PDF file name
                    // instead of its contents
                    $uploadDB = new Fichiercommunication();
                    $uploadDB->setLien($newFilename);
                    $uploadDB->setType('messagerie');
                    $uploadDB->setMessagerie($message);

                    $em=$this->getDoctrine()->getManager();
                    $em->persist($uploadDB);
                    $em->flush();
                }
            }
            return $this->redirect($request->getUri());
        }

        return $this->render('index/page_messagerie.html.twig', [
            'imagesmessagerie' => $allMessageImg,
            'fichiersmessagerie' => $allfichiercom,
            'form' => $form->createView(),
            'upform' => $form2->createView(),
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

    /**
     * @Route("/deletepage/{type}/{id}", name="deletepage")
     */
    public function deletepage(Request $request,$id,$type): Response
    {
        if ($type=='blog') {
            $em=$this->getDoctrine()->getRepository(Blog::class);
            $page=$em->findOneby(array('id'=>$id));
        }elseif ($type=='jeu') {
            $em=$this->getDoctrine()->getRepository(Jeu::class);
            $page=$em->findOneby(array('id'=>$id));
        }elseif ($type=='proto'){
            $em=$this->getDoctrine()->getRepository(Proto::class);
            $page=$em->findOneby(array('id'=>$id));
        }else {
            return $this->redirectToRoute('listeblog');  
        }
        
        if ($this->getUser()){
            if ($this->getUser()==$page->getAuteur() or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                
                $em=$this->getDoctrine()->getManager();
                $em->remove($page);
                $em->flush();
            }
        }
        return $this->redirectToRoute('liste'.$type);      
    }

    /**
     * @Route("/updatepage_{type}_{id}", name="updatepage")
     */
    public function updatepage(Request $request,$id,$type): Response
    {
        if ($type=='blog') {
            $em=$this->getDoctrine()->getRepository(Blog::class);
            $page=$em->findOneby(array('id'=>$id));
            $form = $this->createForm(MakeblogType::class, $page);
        } elseif ($type=='jeu') {
            $em=$this->getDoctrine()->getRepository(Jeu::class);
            $page=$em->findOneby(array('id'=>$id));
            $form = $this->createForm(MakejeuType::class, $page);
        } elseif ($type=='proto') {
            $em=$this->getDoctrine()->getRepository(Proto::class);
            $page=$em->findOneby(array('id'=>$id));
            $form = $this->createForm(MakeprotoType::class, $page);
        } else {
            return $this->redirectToRoute('listeblog');  
        }

        $form->handleRequest($request);

        if ($this->getUser()){
            if ($this->getUser()==$page->getAuteur() or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                
                $em2=$this->getDoctrine()->getRepository(Imagejeuproto::class);
                $AllBlogImage=$em2->findBy(array('type'=>$type));
                $countImg=count($AllBlogImage);

                $images=array();
                for ($i=0;$i<$countImg;$i++) {
                    if ($type=='blog') {
                        if ($AllBlogImage[$i]->getBlog()->getId()==$id) {
                            $images[]=$AllBlogImage[$i];
                        }
                    } elseif ($type=='jeu') {
                        if ($AllBlogImage[$i]->getJeu()->getId()==$id) {
                            $images[]=$AllBlogImage[$i];
                        }
                    } elseif ($type=='proto') {
                        if ($AllBlogImage[$i]->getProto()->getId()==$id) {
                            $images[]=$AllBlogImage[$i];
                        }
                    } 
                }

                if($form->isSubmitted() and $form->isValid()){

                    $testdata = $form->getData();

                    $page->setTitre($testdata->getTitre());
                    $page->setContenue($testdata->getContenue());

                    if ($type=='blog') {
                        $page->setType($testdata->getType());
                        $page->setJeu($testdata->getJeu());
                    } elseif ($type=='jeu') {
                        $page->setLien($testdata->getLien());
                        $page->setEtat($testdata->getEtat());
                    }

                    $newImages=$form->get("image")->getData();

                    $em=$this->getDoctrine()->getManager();
                    $em->persist($page);
                    $em->flush();

                    $countnewImages=count($newImages);

                    for ($i=0; $i < $countnewImages; $i++) { 
                        $image=$newImages[$i];

                        $encoded_data = base64_encode(file_get_contents($image)); 

                        $imagetest = new Imagejeuproto();

                        $imagetest->setType($type);
                        if ($type=='blog') {
                            $imagetest->setBlog($page);
                        } elseif ($type=='jeu') {
                            $imagetest->setJeu($page);
                        } elseif ($type=='proto') {
                            $imagetest->setProto($page);
                        }
                        
                        $imagetest->setImage($encoded_data);

                        $em=$this->getDoctrine()->getManager();
                        $em->persist($imagetest);
                        $em->flush();
                    }
                    
                    return $this->redirectToRoute('liste'.$type); 
                }
                
                return $this->render('index/page_update.html.twig', [
                    'images' => $images,
                    'type' => $type,
                    'page' => $page,
                    'form' => $form->createView(),
                ]);
            }     
        } return $this->redirectToRoute('listeblog');   
    }  

    /**
     * @Route("/deleteimage_{type}_{pageid}_{id}", name="deleteimage")
     */
    public function deleteimage(Request $request,$id,$pageid,$type): Response
    {
        $em=$this->getDoctrine()->getRepository(Imagejeuproto::class);
        $image=$em->findOneby(array('id'=>$id));

        if ($type=='blog') {
            $em2=$this->getDoctrine()->getRepository(Blog::class);
            $page=$em2->findOneby(array('id'=>$pageid));
        }elseif ($type=='jeu') {
            $em2=$this->getDoctrine()->getRepository(Jeu::class);
            $page=$em2->findOneby(array('id'=>$pageid));
        }elseif ($type=='proto') {
            $em2=$this->getDoctrine()->getRepository(Proto::class);
            $page=$em2->findOneby(array('id'=>$pageid));
        }else {
            return $this->redirectToRoute('listeblog'); 
        }
        
        if ($this->getUser()){
            if ($this->getUser()==$page->getAuteur() or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                
                $em=$this->getDoctrine()->getManager();
                $em->remove($image);
                $em->flush();

                return $this->redirectToRoute('updatepage',['type' => $type,'id' => $pageid]);
            }
        }
        return $this->redirectToRoute('listeblog');       
    }

    /**
     * @Route("/deletecom_{type}_{id}", name="deletecom")
     */
    public function deletecom(Request $request,$id,$type): Response
    {
        if ($type=='com') {
            $em=$this->getDoctrine()->getRepository(Com::class);
            $com=$em->findOneby(array('id'=>$id));
        } elseif ($type=='mes') {
            $em=$this->getDoctrine()->getRepository(Messagerie::class);
            $com=$em->findOneby(array('id'=>$id));
        }
        
        
        if ($this->getUser()) {
            if ($this->getUser()==$com->getEnvoyer() or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {

                if ($type=='com') {
                    $typepage=$com->getType();

                    if ($typepage=='blog') {
                        $page=$com->getBlog();
                    } elseif ($typepage=='jeu') {
                        $page=$com->getJeu();
                    } elseif ($typepage=='proto') {
                        $page=$com->getProto();
                    } else {
                        return $this->redirectToRoute('listeblog'); 
                    }
                } elseif ($type=='mes') {
                    $typepage='messagerie';
                    $page=$com->getReceveur();
                }

                $em=$this->getDoctrine()->getManager();
                $em->remove($com);
                $em->flush();

                return $this->redirectToRoute('page'.$typepage,['id' => $page->getId()]);
            }
        }
        return $this->redirectToRoute('liste'.$com->getType());       
    }

    /**
     * @Route("/updatecom_{type}_{id}", name="updatecom")
     */
    public function updatecom(Request $request,$id,$type): Response
    {
        if ($type=='com') {
            $em=$this->getDoctrine()->getRepository(Com::class);
            $com=$em->findOneby(array('id'=>$id));
        } elseif ($type=='mes') {
            $em=$this->getDoctrine()->getRepository(Messagerie::class);
            $com=$em->findOneby(array('id'=>$id));
        } else{
            return $this->redirectToRoute('listeblog');   
        }

        if ($this->getUser()){
            if ($this->getUser()==$com->getEnvoyer() or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
               
                if ($type=='com') {
                    $typepage=$com->getType();

                    if ($typepage=='blog') {
                        $page=$com->getBlog();
                    } elseif ($typepage=='jeu') {
                        $page=$com->getJeu();
                    } elseif ($typepage=='proto') {
                        $page=$com->getProto();
                    } else {
                        return $this->redirectToRoute('listeblog'); 
                    }
                } elseif ($type='mes') {
                    $typepage='messagerie';
                    $page=$com->getReceveur();
                }

                $com->setContenue($request->request->get("textComment"));

                $files = $request->files->all();
                if ($request->getMethod() == "POST") {
    
                    foreach ($files as $file) {
                         if ($file instanceof UploadedFile) {
                            $encoded_data= base64_encode(file_get_contents($file));

                            $imagetest = new Imagecommunication();
        
                            if ($type=='com') {
                                $imagetest->setType("com");
                                $imagetest->setCom($com);
                            }elseif ($type='mes') {
                                $imagetest->setType("messagerie");
                                $imagetest->setMessagerie($com);
                            }
                            
                            $imagetest->setImage($encoded_data);
            
                            $em=$this->getDoctrine()->getManager();
                            $em->persist($imagetest);
                            $em->flush();
                        }
                    }
                }

                $em=$this->getDoctrine()->getManager();
                $em->persist($com);
                $em->flush();
                
                return $this->redirectToRoute('page'.$typepage,['id' => $page->getId()]);
            }
        } return $this->redirectToRoute('liste'.$com->getType());   
    }  

    /**
     * @Route("/deleteimagecom_{id}", name="deleteimage")
     */
    public function deleteimagecom(Request $request,$id): Response
    {
        $em=$this->getDoctrine()->getRepository(Imagecommunication::class);
        $image=$em->findOneby(array('id'=>$id));
        $type=$image->getType();
        if ($type=='com') {
            $com=$image->getCom();
            $proto=$com->getProto();
            $page='proto';
        }elseif ($type=='messagerie') {
            $com=$image->getMessagerie();
            $proto=$com->getReceveur();
            $page='messagerie';
        }
        
        if ($this->getUser()){
            if ($this->getUser()==$com->getEnvoyer() or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                
                $em=$this->getDoctrine()->getManager();
                $em->remove($image);
                $em->flush();

                return $this->redirectToRoute('page'.$page,['id' => $proto->getId()]);
            }
        }
        return $this->redirectToRoute('listeblog');       
    }

    /**
     * @Route("/deletefichiercom_{filename}", name="deletefichiercom")
     */
    public function deletefichiercom(Request $request,$filename): Response
    {
        $em=$this->getDoctrine()->getRepository(Fichiercommunication::class);
        $file=$em->findOneby(array('lien'=>$filename));
        $type=$file->getType();
        if ($type=='com') {
            $com=$file->getCom();
            $proto=$com->getProto();
            $page='proto';
        }elseif ($type=='messagerie') {
            $com=$file->getMessagerie();
            $proto=$com->getReceveur();
            $page='messagerie';
        }
        
        if ($this->getUser()){
            if ($this->getUser()==$com->getEnvoyer() or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                unlink('upload/'.$filename);
                
                $em=$this->getDoctrine()->getManager();
                $em->remove($file);
                $em->flush();

                return $this->redirectToRoute('page'.$page,['id' => $proto->getId()]);
            }
        }
        return $this->redirectToRoute('listeblog');       
    }
}

