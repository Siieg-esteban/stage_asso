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

use App\Service\unziper;

use App\Form\MakeprotoType;
use App\Form\MakejeuType;
use App\Form\MakeblogType;
use App\Form\MakecomType;
use App\Form\UpdatecomType;
use App\Form\NewMessageType;
use App\Form\UpdateMessageType;
use App\Form\MakerequeteType;
use App\Form\UpdateUserType;

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
use App\Entity\RequeteContributeur;
use App\Entity\Imagefichierrequete;

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

        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                $demandeContrib=$emtest->findAll();
                $countDemandeContrib=count($demandeContrib);
            }
            else {
                $countDemandeContrib=0;      
            }
        }
        else {
            $countDemandeContrib=0;      
        }

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

            $mainImage=$form->get("mainimage")->getData();
            $base64=base64_encode(file_get_contents($mainImage)); 
            $blog->setImage($base64);
            
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
            'countDemandeContrib' => $countDemandeContrib,
            'pagetype' => 'blog',
            'form' => $form->createView(),
            'listeblog' => $bloglist,
            'countblog' => $countblog,
        ]);
    }

    /**
     * @Route("/listejeu", name="listejeu")
     */
    public function listejeu(Request $request,SluggerInterface $slugger,unziper $unziper): Response
    {
        $em=$this->getDoctrine()->getRepository(Jeu::class);
        $Alljeu=$em->findAll();
        $countjeu=count($Alljeu);
        $randNumber=random_int(1, $countjeu);
        $randomGame=$Alljeu[$randNumber-1];
        
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                $demandeContrib=$emtest->findAll();
                $countDemandeContrib=count($demandeContrib);
            }
            else {
                $countDemandeContrib=0;      
            }
        }
        else {
            $countDemandeContrib=0;      
        }

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
            $jeu->setEtat($testdata->getEtat());
            $jeu->setNomdossier($testdata->getNomdossier());
            $jeu->setLongueur($testdata->getLongueur());
            $jeu->setLargeur($testdata->getLargeur());

            $mainImage=$form->get("mainimage")->getData();
            $base64=base64_encode(file_get_contents($mainImage)); 
            $jeu->setImage($base64);

            $jeu->setUpvote('0');
            $jeu->setDatetime($datetime);
            $jeu->setAuteur($userid);

            if ($form->get("fileWeb")->getData() and $form->get("fileDl")->getData()) {
                $jeu->setType('all');
            } elseif ($form->get("fileWeb")->getData()) {
                $jeu->setType('web');
            } elseif ($form->get("fileDl")->getData()) {
                $jeu->setType('dl');
            } else {
                $jeu->setType('null');
            }

            if ($form->get("fileWeb")->getData()) {
                $uploadFileWeb = $form->get('fileWeb')->getData();
                $originalFilename = pathinfo($uploadFileWeb->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFileWeb->guessExtension();
                $newReponame = $safeFilename.'-'.uniqid();

                mkdir('jeuWeb/'.$newReponame, 0777, true);
                chmod('jeuWeb/'.$newReponame, 0777);

                // Move the file to the directory where uploads are stored
                try {
                    $uploadFileWeb->move(
                        'jeuWeb',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $jeu->setLienWeb($newReponame);
                $unziper->unzip($newFilename,$newReponame);
            }            

            if ($form->get("fileDl")->getData()) {
                $uploadFileDl = $form->get('fileDl')->getData();
                $originalFilename = pathinfo($uploadFileDl->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFileDl->guessExtension();

                // Move the file to the directory where uploads are stored
                try {
                    $uploadFileDl->move(
                        $this->getParameter('jeuDl_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $jeu->setLienDl($newFilename);
            }

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
            'countDemandeContrib' => $countDemandeContrib,
            'pagetype' => 'jeu',
            'form' => $form->createView(),
            'listejeu' => $jeulist,
            'randomGame' => $randomGame,
            'countjeu' => $countjeu,
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

        $em2=$this->getDoctrine()->getRepository(Com::class);
        $AllProtoCom=$em2->findBy(array('type'=>'proto'));

        $em3=$this->getDoctrine()->getRepository(Listecontributeur::class);
        $contribAll=$em3->findBy(array('type'=>'proto'));

        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                $demandeContrib=$emtest->findAll();
                $countDemandeContrib=count($demandeContrib);
            }
            else {
                $countDemandeContrib=0;      
            }
        }
        else {
            $countDemandeContrib=0;      
        }

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

            $mainImage=$form->get("mainimage")->getData();
            $base64=base64_encode(file_get_contents($mainImage)); 
            $proto->setImage($base64);

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
            'countDemandeContrib' => $countDemandeContrib,
            'pagetype' => 'proto',
            'form' => $form->createView(),
            'listeproto' => $protolist,
            'protoCom' => $AllProtoCom,
            'contribAll' => $contribAll,
            'countproto' => $countproto,
        ]);
    }

    /**
     * @Route("/pageblog{id}", name="pageblog")
     */
    public function pageblog($id): Response
    {
        $em=$this->getDoctrine()->getRepository(Blog::class);
        $blog=$em->findOneby(array('id'=>$id));
        
        $AllBlogs=$em->findAll();
        $AllOtherBlog=array();
        foreach ($AllBlogs as $oneblog) {
            if ($oneblog != $blog) {
                $AllOtherBlog[]=$oneblog;
            }
        }
        $CountOtherBlog=count($AllOtherBlog);

        $random1=mt_rand(0,$CountOtherBlog-1);
        $random2=mt_rand(0,$CountOtherBlog-1);
        while ($random2==$random1) {
            $random2=mt_rand(0,$CountOtherBlog-1);
        }
        $AutreBlog1=$AllOtherBlog[$random1];
        $AutreBlog2=$AllOtherBlog[$random2];

        $em2=$this->getDoctrine()->getRepository(Com::class);
        $AllBlogCom=$em2->findBy(array('type'=>'blog'));
        $countCom=count($AllBlogCom);

        $em3=$this->getDoctrine()->getRepository(Imagejeuproto::class);
        $AllBlogImage=$em3->findBy(array('type'=>'blog'));
        $countImg=count($AllBlogImage);

        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                $demandeContrib=$emtest->findAll();
                $countDemandeContrib=count($demandeContrib);
            }
            else {
                $countDemandeContrib=0;      
            }
        }
        else {
            $countDemandeContrib=0;      
        }

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
            'countDemandeContrib' => $countDemandeContrib,
            'pagetype' => 'blog',
            'blog' => $blog,
            'comments' => $comment,
            'images' => $images,
            'AutreBlog1' => $AutreBlog1,
            'AutreBlog2' => $AutreBlog2,
        ]);
    }

    /**
     * @Route("/pagejeu{id}", name="pagejeu")
     */
    public function pagejeu($id): Response
    {
        $em=$this->getDoctrine()->getRepository(Jeu::class);
        $jeu=$em->findOneby(array('id'=>$id));

        $AllJeux=$em->findAll();
        $AllOtherJeu=array();
        foreach ($AllJeux as $oneJeu) {
            if ($oneJeu != $jeu) {
                $AllOtherJeu[]=$oneJeu;
            }
        }
        $CountOtherJeu=count($AllOtherJeu);

        $random1=mt_rand(0,$CountOtherJeu-1);
        $random2=mt_rand(0,$CountOtherJeu-1);
        while ($random2==$random1) {
            $random2=mt_rand(0,$CountOtherJeu-1);
        }
        $AutreJeu1=$AllOtherJeu[$random1];
        $AutreJeu2=$AllOtherJeu[$random2];

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

        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                $demandeContrib=$emtest->findAll();
                $countDemandeContrib=count($demandeContrib);
            }
            else {
                $countDemandeContrib=0;      
            }
        }
        else {
            $countDemandeContrib=0;      
        }

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
            'countDemandeContrib' => $countDemandeContrib,
            'pagetype' => 'jeu',
            'jeu' => $jeu,
            'comments' => $comment,
            'images' => $images,
            'listeblog' => $bloglink,
            'listecontrib' => $contribList,
            'AutreJeu1' => $AutreJeu1,
            'AutreJeu2' => $AutreJeu2,
        ]);
    }

    /**
     * @Route("/pageproto{id}", name="pageproto")
     */
    public function pageproto($id,Request $request,SluggerInterface $slugger): Response
    {
        $em=$this->getDoctrine()->getRepository(Proto::class);
        $proto=$em->findOneby(array('id'=>$id));

        $AllProtos=$em->findAll();
        $AllOtherProto=array();
        foreach ($AllProtos as $oneProto) {
            if ($oneProto != $proto) {
                $AllOtherProto[]=$oneProto;
            }
        }
        $CountOtherProto=count($AllOtherProto);

        $random1=mt_rand(0,$CountOtherProto-1);
        $random2=mt_rand(0,$CountOtherProto-1);
        while ($random2==$random1) {
            $random2=mt_rand(0,$CountOtherProto-1);
        }
        $AutreProto1=$AllOtherProto[$random1];
        $AutreProto2=$AllOtherProto[$random2];

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

        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                $demandeContrib=$emtest->findAll();
                $countDemandeContrib=count($demandeContrib);
            }
            else {
                $countDemandeContrib=0;      
            }
        }
        else {
            $countDemandeContrib=0;      
        }

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
            'countDemandeContrib' => $countDemandeContrib,
            'pagetype' => 'proto',
            'form' => $form->createView(),
            'upform' => $form2->createView(),
            'proto' => $proto,
            'comments' => $comment,
            'images' => $images,
            'imagesCom' => $imagecomtest,
            'fichierCom' => $fichiercomtest,
            'listecontrib' => $contribList,
            'AutreProto1' => $AutreProto1,
            'AutreProto2' => $AutreProto2,
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

        $em7=$this->getDoctrine()->getRepository(RequeteContributeur::class);
        $demandeContrib=$em7->findAll();

        $em8=$this->getDoctrine()->getRepository(Imagefichierrequete::class);
        $AllFichierDemande=$em8->findAll();

        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                $demandeContrib=$emtest->findAll();
                $countDemandeContrib=count($demandeContrib);
            }
            else {
                $countDemandeContrib=0;      
            }
        }
        else {
            $countDemandeContrib=0;      
        }

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

        $form = $this->createForm(UpdateUserType::class, $theUser);
        $form->handlerequest($request);

        if($form->isSubmitted() and $form->isValid()){
            if ($this->getUser()){
                if ($this->getUser()==$theUser or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                    $testdata = $form->getData();

                    $theUser->setName($testdata->getName());
                    $theUser->setDescription($testdata->getDescription());
                    if ($form->get("avatar")->getData()) {
                        $mainImage=$form->get("avatar")->getData();
                        $base64=base64_encode(file_get_contents($mainImage)); 
                        $theUser->setAvatar($base64);
                    }
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($theUser);
                    $em->flush();

                    return $this->redirectToRoute('pageuser',['id' => $id]);   
                }
            }
        }

        return $this->render('index/page_user.html.twig', [
            'countDemandeContrib' => $countDemandeContrib,
            'form' => $form->createView(),
            'pagetype' => 'user',
            'user' => $theUser,
            'competences' => $competence,
            'typeCompetences' => $typeCompetence,
            'contribs' => $contribList,
            'blogs' => $blogUser,
            'personnes' => $personneList,
            'messages' => $messageList,
            'demandeContrib' => $demandeContrib,
            'FichierDemande' => $AllFichierDemande,
            
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

        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                $demandeContrib=$emtest->findAll();
                $countDemandeContrib=count($demandeContrib);
            }
            else {
                $countDemandeContrib=0;      
            }
        }
        else {
            $countDemandeContrib=0;      
        }

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
            'countDemandeContrib' => $countDemandeContrib,
            'pagetype' => 'user',
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
     * @Route("/updatecompetence_{id}", name="updatecompetence")
     */
    public function updatecompetence(Request $request,$id): Response
    {
        $em=$this->getDoctrine()->getRepository(Listecompetence::class);
        $competence=$em->findOneBy(array('id'=>$id));
        $idUser=$competence->getUser()->getId();

        if ($this->getUser()){
            if ($this->getUser()==$competence->getUser() or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $em=$this->getDoctrine()->getManager();
                $em->remove($competence);
                $em->flush();
            }
        }
        
        return $this->redirectToRoute('pageuser',['id' => $idUser]);      
    }

    /**
     * @Route("/deletepage/{type}/{id}", name="deletepage")
     */
    public function deletepage(Request $request,$id,$type): Response
    {
        $test=$type;
        if ($test=='blog') {
            $em=$this->getDoctrine()->getRepository(Blog::class);
            $page=$em->findOneby(array('id'=>$id));
        }elseif ($test=='jeu') {
            $em=$this->getDoctrine()->getRepository(Jeu::class);
            $page=$em->findOneby(array('id'=>$id));
        }elseif ($test=='proto'){
            $em=$this->getDoctrine()->getRepository(Proto::class);
            $page=$em->findOneby(array('id'=>$id));
            
        }else {
            return $this->redirectToRoute('listeblog');  
        }
        
        if ($this->getUser()){
            if ($this->getUser()==$page->getAuteur() or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                
                if ($test=='jeu') {
                    if ($page->getType()=="all") {
                        // delete file dl
                        $dirdl=$this->getParameter('jeuDl_directory').'/'.$page->getLienDl();
                        unlink($dirdl);

                        // delete file web
                        $dirweb=$this->getParameter('jeuWeb_directory').'/'.$page->getLienWeb().'/'.$page->getNomdossier();
                        $dirweb2=$this->getParameter('jeuWeb_directory').'/'.$page->getLienWeb();
                        $dirinside=scandir($dirweb);
                        $countdirinside=count($dirinside);
                        for ($i=2; $i < $countdirinside; $i++) { 
                            unlink($dirweb.'/'.$dirinside[$i]);
                        }
                        rmdir($dirweb);
                        rmdir($dirweb2);
                    } elseif ($page->getType()=="web") {
                        // delete file web
                        $dirweb=$this->getParameter('jeuWeb_directory').'/'.$page->getLienWeb().'/'.$page->getNomdossier();
                        $dirweb2=$this->getParameter('jeuWeb_directory').'/'.$page->getLienWeb();
                        $dirinside=scandir($dirweb);
                        $countdirinside=count($dirinside);
                        for ($i=2; $i < $countdirinside; $i++) { 
                            unlink($dirweb.'/'.$dirinside[$i]);
                        }
                        rmdir($dirweb);
                        rmdir($dirweb2);
                    } elseif ($page->getType()=="dl") {
                        // delete file dl
                        $dir=$this->getParameter('jeuDl_directory').'/'.$page->getLienDl();
                        unlink($dir);
                    }
                }

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
    public function updatepage(Request $request,SluggerInterface $slugger,unziper $unziper,$id,$type): Response
    {
        if ($type=='blog') {
            $em=$this->getDoctrine()->getRepository(Blog::class);
            $page=$em->findOneby(array('id'=>$id));
            $form = $this->createForm(MakeblogType::class, $page);
        } elseif ($type=='jeu') {
            $em=$this->getDoctrine()->getRepository(Jeu::class);
            $page=$em->findOneby(array('id'=>$id));
            $form = $this->createForm(MakejeuType::class, $page);
            $oldReponame = $page->getNomdossier();
        } elseif ($type=='proto') {
            $em=$this->getDoctrine()->getRepository(Proto::class);
            $page=$em->findOneby(array('id'=>$id));
            $form = $this->createForm(MakeprotoType::class, $page);
        } else {
            return $this->redirectToRoute('listeblog');  
        }

        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                $demandeContrib=$emtest->findAll();
                $countDemandeContrib=count($demandeContrib);
            }
            else {
                $countDemandeContrib=0;      
            }
        }
        else {
            $countDemandeContrib=0;      
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

                    if ($form->get("mainimage")) {
                        $mainImage=$form->get("mainimage")->getData();
                        $base64=base64_encode(file_get_contents($mainImage)); 
                        $page->setImage($base64);
                    }

                    if ($type=='blog') {
                        $page->setType($testdata->getType());
                        $page->setJeu($testdata->getJeu());
                    } elseif ($type=='jeu') {
                        $page->setEtat($testdata->getEtat());
                        $page->setLongueur($testdata->getLongueur());
                        $page->setLargeur($testdata->getLargeur());
                        $page->setNomdossier($testdata->getNomdossier());
                
                        if ($form->get("fileWeb")->getData()) {
                            $uploadFileWeb = $form->get('fileWeb')->getData();
                            if ($page->getLienWeb()) {
                                // $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFileWeb->guessExtension();
                                $newFilename = $page->getLienWeb().'rar';
                                $newReponame = $page->getLienWeb();
                                                            
                                $dir=$this->getParameter('jeuWeb_directory').'/'.$newReponame.'/'.$oldReponame;
                                $dirinside=scandir($dir);
                                $countdirinside=count($dirinside);
                                for ($i=2; $i < $countdirinside; $i++) { 
                                    unlink($dir.'/'.$dirinside[$i]);
                                }
                                rmdir($dir);
                            }else {
                                $originalFilename = pathinfo($uploadFileWeb->getClientOriginalName(), PATHINFO_FILENAME);
                                // this is needed to safely include the file name as part of the URL
                                $safeFilename = $slugger->slug($originalFilename);
                                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFileWeb->guessExtension();
                                $newReponame = $safeFilename.'-'.uniqid();

                                mkdir('jeuWeb/'.$newReponame, 0777, true);
                                chmod('jeuWeb/'.$newReponame, 0777);
                                $page->setLienWeb($newReponame);
                            }

                            // Move the file to the directory where uploads are stored
                            try {
                                $uploadFileWeb->move(
                                    'jeuWeb',
                                    $newFilename
                                );
                            } catch (FileException $e) {
                                // ... handle exception if something happens during file upload
                            }
            
                            $unziper->unzip($newFilename,$newReponame);
                        }

                        if ($form->get("fileDl")->getData()) {
                            if ($page->getLienDl()) {
                                $oldFilename = $page->getLienDl();
                                $dir=$this->getParameter('jeuDl_directory');
                                unlink($dir.'/'.$oldFilename);

                                $uploadFileDl = $form->get('fileDl')->getData();
                
                                // Move the file to the directory where uploads are stored
                                try {
                                    $uploadFileDl->move(
                                        $this->getParameter('jeuDl_directory'),
                                        $oldFilename
                                    );
                                } catch (FileException $e) {
                                    // ... handle exception if something happens during file upload
                                }
                            } else {
                                $uploadFileDl = $form->get('fileDl')->getData();
                                $originalFilename = pathinfo($uploadFileDl->getClientOriginalName(), PATHINFO_FILENAME);
                                // this is needed to safely include the file name as part of the URL
                                $safeFilename = $slugger->slug($originalFilename);
                                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFileDl->guessExtension();
                                $page->setLienDl($newFilename);
                                // Move the file to the directory where uploads are stored
                                try {
                                    $uploadFileDl->move(
                                        $this->getParameter('jeuDl_directory'),
                                        $newFilename
                                    );
                                } catch (FileException $e) {
                                    // ... handle exception if something happens during file upload
                                }
                            }
                        }
                        $testWeb=false;
                        $testDl=false;
                        if ($form->get("fileWeb")->getData() or $page->getLienWeb()) {
                            $testWeb=true;
                            $page->setType('web');
                        } 
                        if ($form->get("fileDl")->getData() or $page->getLienDl()) {
                            $testDl=true;
                            $page->setType('dl');
                        }
                        if ($testWeb == true and $testDl == true) {
                            $page->setType('all');
                        }
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
                    return $this->redirectToRoute('page'.$type,['id' => $id]); 
                }
                
                return $this->render('index/page_update.html.twig', [
                    'countDemandeContrib' => $countDemandeContrib,
                    'pagetype' => $type,
                    'type' => $type,
                    'images' => $images,
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
            $em2=$this->getDoctrine()->getRepository(Fichiercommunication::class);
            $fichierCommunication=$em2->findby(array('com'=>$com));
        } elseif ($type=='mes') {
            $em=$this->getDoctrine()->getRepository(Messagerie::class);
            $com=$em->findOneby(array('id'=>$id));
            $em2=$this->getDoctrine()->getRepository(Fichiercommunication::class);
            $fichierCommunication=$em2->findby(array('messagerie'=>$com));
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

                foreach ($fichierCommunication as $file) {
                    unlink('upload/'.$file->getLien());
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

                $com->setContenue($request->query->get("textComment"));

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
     * @Route("/deleteimagecom_{id}", name="deleteimagecom")
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

    /**
     * @Route("/pagerequetecontrib", name="pagerequetecontrib")
     */
    public function pagerequetecontrib(Request $request,SluggerInterface $slugger): Response
    {
        $requete = new RequeteContributeur();
        $form = $this->createForm(MakerequeteType::class, $requete);
        $form->handlerequest($request);

        if ($this->getUser()) {
            if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                $demandeContrib=$emtest->findAll();
                $countDemandeContrib=count($demandeContrib);
            }
            else {
                $countDemandeContrib=0;      
            }
        }
        else {
            $countDemandeContrib=0;      
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $testdata = $form->getData();

            $userid=$this->getUser();
            $datetime = new \DateTime('@'.strtotime('now'));

            $requete->setDatetime($datetime);
            $requete->setUser($userid);
            $requete->setDemande($testdata->getDemande());

            $newImages=$form->get("image")->getData();

            $em=$this->getDoctrine()->getManager();
            $em->persist($requete);
            $em->flush();

            $countnewImages=count($newImages);

            for ($i=0; $i < $countnewImages; $i++) { 
                $image=$newImages[$i];

                $encoded_data = base64_encode(file_get_contents($image)); 

                $imagetest = new Imagefichierrequete();

                $imagetest->setType("image");
                $imagetest->setImage($encoded_data);
                $imagetest->setRequete($requete);

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
                            $this->getParameter('demande_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'uploadFilename' property to store the PDF file name
                    // instead of its contents
                    $uploadDB = new Imagefichierrequete();
                    $uploadDB->setLien($newFilename);
                    $uploadDB->setType('fichier');
                    $uploadDB->setRequete($requete);
                    
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($uploadDB);
                    $em->flush();
                }
            }
            return $this->redirect($request->getUri());
        }

        return $this->render('index/page_requete_contrib.html.twig', [
            'countDemandeContrib' => $countDemandeContrib,
            'pagetype' => 'requete_contributeur',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reponce_demande_{rep}_{id}", name="reponce_demande")
     */
    public function reponce_demande(Request $request,$id,$rep): Response
    {
        $em=$this->getDoctrine()->getRepository(RequeteContributeur::class);
        $demande=$em->findOneby(array('id'=>$id));
        $idpage= $demande->getUser()->getId();
        $em2=$this->getDoctrine()->getRepository(Imagefichierrequete::class);
        $fichierRequete=$em2->findby(array('requete'=>$demande));
        
        if ($this->getUser()){
            if ( $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                
                $message = new Messagerie();
                $datetime = new \DateTime('@'.strtotime('now'));
                
                $message->setDatetime($datetime);
                $message->setEnvoyer($this->getUser());
                $message->setReceveur($demande->getUser());

                // si accepter : mp auto d acceptation + change role
                if ($rep=='1') {
                    $message->setContenue('Votre demande pour devenir contributeur a été accepter !');
                    $role = ('ROLE_CONTRIBUTOR');
                    $demande->getUser()->setRoles($role);
                // si refu : mp auto de refu 
                }elseif ($rep=='2') {
                    $message->setContenue("Votre demande pour devenir contributeur n'a pas pu être acceptée. Un admin va vous envoyer un message pour vous expliquer les raisons de ce refus");
                    $role = array('ROLE_USER');
                    $demande->getUser()->setRoles($role);
                }

                foreach ($fichierRequete as $file) {
                    if ($file->getType()=="fichier") {
                        unlink('demande/'.$file->getLien());
                    }
                }
                
                $em=$this->getDoctrine()->getManager();
                $em->persist($message);
                $em->remove($demande);
                $em->flush();

                // redirect to messagerie de la personne pour lui dire
                return $this->redirectToRoute('pagemessagerie',['id' => $idpage]);
            }
        } return $this->redirectToRoute('listeblog');       
    }

    /**
     * @Route("/listecontributeur_{type}", name="listecontributeur")
     */
    public function listecontributeur(Request $request,$type): Response
    {
        if ($this->getUser()){
            if ($this->getUser()->getRoles()[0]=="ROLE_CONTRIBUTOR" or $this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                
                $em=$this->getDoctrine()->getRepository(User::class);
                $AllUser=$em->findAll();

                $em2=$this->getDoctrine()->getRepository(Listecompetence::class);
                $AllListecompetence=$em2->findAll();

                $em3=$this->getDoctrine()->getRepository(Competence::class);
                $CompetenceUnique=$em3->findAll();

                if ($this->getUser()) {
                    if ($this->getUser()->getRoles()[0]=="ROLE_ADMIN" ) {
                        $emtest=$this->getDoctrine()->getRepository(RequeteContributeur::class);
                        $demandeContrib=$emtest->findAll();
                        $countDemandeContrib=count($demandeContrib);
                    }
                    else {
                        $countDemandeContrib=0;      
                    }
                }
                else {
                    $countDemandeContrib=0;      
                }

                $Allcontributeur=array();

                foreach ($AllUser as $user) {
                    if ($user->getRoles()[0]=="ROLE_ADMIN" or $user->getRoles()[0]=="ROLE_CONTRIBUTOR") {
                        if ($type=="all") {
                            $Allcontributeur[]=$user;
                        }else{
                            foreach ($AllListecompetence as $competence) {
                                if ($competence->getCompetence()->getNom()==$type and $competence->getUser()==$user) {
                                    $Allcontributeur[]=$user;
                                }                    
                            }
                        }
                    }
                }

                return $this->render('index/liste_contributeur.html.twig', [
                    'countDemandeContrib' => $countDemandeContrib,
                    'pagetype' => 'liste_contributeur',
                    'type' => $type,
                    'listecontributeur' => $Allcontributeur,
                    'listecompetence' => $AllListecompetence,
                    'competenceUnique' => $CompetenceUnique,
                ]);
            }
        } return $this->redirectToRoute('listeblog');   
    }
}

