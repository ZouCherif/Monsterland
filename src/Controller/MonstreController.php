<?php
    namespace App\Controller;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use App\Form\Type\MonstreType;
    use App\Form\Type\RoyaumeType;
    use App\Entity\Monstre;
    use App\Entity\Royaume;

    class MonstreController extends AbstractController{
        public function afficher_monstres() {
            $monstres = $this->getDoctrine()->getRepository(Monstre::class)->findAll();
            if(!$monstres)
                return new Response("Not Found");
            return $this->render('monstres/afficher_liste_monstres.html.twig', array('monstres' => $monstres));
        }
        public function ajouter_monstre(Request $request) {
            $monstre = new Monstre;
            $form = $this->createFormBuilder($monstre)
                ->add('nom', TextType::class)
                ->add('type', TextType::class)
                ->add('puissance', IntegerType::class)
                ->add('taille', IntegerType::class)
                ->add('royaume', EntityType::class, ['class' => Royaume::class, 'choice_label' => 'nom'])
                ->add('envoyer', SubmitType::class)
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($monstre);
                $entityManager->flush();
                return $this->redirectToRoute('liste_monstres');
            }
            return $this->render('monstres/ajouter_monstre.html.twig', array('monFormulaire' => $form->createView()));
        }
        public function afficher_royaumes() {
            $royaumes = $this->getDoctrine()->getRepository(Royaume::class)->findAll();
            if(!$royaumes)
                return new Response("Not Found");
            return $this->render('royaumes/afficher_liste_royaumes.html.twig', array('royaumes' => $royaumes));
        }
        public function ajouter_royaume(Request $request){
            $royaume = new Royaume;
            $form = $this->createFormBuilder($royaume)
            ->add('nom', TextType::class)
            ->add('envoyer', SubmitType::class)
            ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($royaume);
            $entityManager->flush();
            return $this->redirectToRoute('liste_royaumes');
            }
            return $this->render('royaumes/ajouter_royaume.html.twig', array('monFormulaire' => $form->createView()));
        }
        public function modifier($id) {
            $monstre = $this->getDoctrine()->getRepository(Monstre::class)->find($id);
            if(!$monstre)
                throw $this->createNotFoundException('monstre[id='.$id.'] inexistante');
            $form = $this->createForm(MonstreType::class, $monstre,
                ['action' => $this->generateUrl('monstre_modifier_suite', array('id' => $monstre->getId()))]);
            $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
            return $this->render('monstres/modifier_monstre.html.twig', array('monFormulaire' => $form->createView()));
        }
        public function modifierSuite(Request $request, $id) {
            $monstre = $this->getDoctrine()->getRepository(Monstre::class)->find($id);
            if(!$monstre)
                throw $this->createNotFoundException('monstre[id='.$id.'] inexistante');
            $form = $this->createForm(MonstreType::class, $monstre,
                ['action' => $this->generateUrl('monstre_modifier_suite', array('id' => $monstre->getId()))]);
            $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($monstre);
                $entityManager->flush();
                $url = $this->generateUrl('liste_monstres', array('id' => $monstre->getId()));
                return $this->redirect($url);
            }
            return $this->render('monstres/modifier_monstre.html.twig',
            array('monFormulaire' => $form->createView()));
        }           
        public function afficher_monstre($id) {
            $monstre = $this->getDoctrine()->getRepository(Monstre::class)->find($id);
            if(!$monstre)
                return new Response("Not Found");
            $royaume = $this->getDoctrine()->getRepository(Royaume::class)->find($monstre->getRoyaume());
            return $this->render('monstres/afficher_monstre.html.twig', array('monstre' => $monstre, 'royaume' => $royaume->getNom()));
        }
        public function supprimer_monstre($id){
            $monstre = $this->getDoctrine()->getRepository(Monstre::class)->find($id);
            if(!$monstre)
                return new Response("Not Found");
            $em = $this->getDoctrine()->getManager();
            $em->remove($monstre);
            $em->flush();
            return $this->redirectToRoute('liste_monstres');
        }
        public function modifier_royaume($id){
            $royaume = $this->getDoctrine()->getRepository(Royaume::class)->find($id);
            if(!$royaume)
                throw $this->createNotFoundException('royaume[id='.$id.'] inexistante');
            $form = $this->createForm(RoyaumeType::class, $royaume,
                ['action' => $this->generateUrl('royaume_modifier_suite', array('id' => $royaume->getId()))]);
            $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
            return $this->render('royaumes/modifier_royaume.html.twig', array('monFormulaire' => $form->createView()));
        }
        public function modifierRoyaumeSuite(Request $request, $id) {
            $royaume = $this->getDoctrine()->getRepository(Royaume::class)->find($id);
            if(!$royaume)
                throw $this->createNotFoundException('royaume[id='.$id.'] inexistante');
            $form = $this->createForm(RoyaumeType::class, $royaume,
                ['action' => $this->generateUrl('royaume_modifier_suite', array('id' => $royaume->getId()))]);
            $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($royaume);
                $entityManager->flush();
                $url = $this->generateUrl('liste_royaumes', array('id' => $royaume->getId()));
                return $this->redirect($url);
            }
            return $this->render('royaumes/modifier_royaume.html.twig',
            array('monFormulaire' => $form->createView()));
        }  
        public function afficher_royaume($id) {
            $royaume = $this->getDoctrine()->getRepository(Royaume::class)->find($id);
            if(!$royaume)
                return new Response("Not Found");
            return $this->render('royaumes/afficher_royaume.html.twig', array('royaume' => $royaume));
        }
        public function supprimer_royaume($id){
            $royaume = $this->getDoctrine()->getRepository(Royaume::class)->find($id);
            if(!$royaume)
                return new Response("Not Found");
            $em = $this->getDoctrine()->getManager();
            $em->remove($Royaume);
            $em->flush();
            return $this->redirectToRoute('liste_royaumes');
        }
        public function montres_royaume($id){
            $royaume = $this->getDoctrine()->getRepository(Royaume::class)->find($id);
            $repo = $this->getDoctrine()->getManager()->getRepository(Monstre::class);
            $monstres = $repo->findByRoyaume($royaume);
            return $this->render('monstres/afficher_liste_monstres.html.twig', array('monstres' => $monstres));
        }
        public function monstres_type($type){
            $monstres = $this->getDoctrine()->getRepository(Monstre::class)->findByType($type);
            if(!$monstres)
                return new Response("not found");
            return $this->render('monstres/afficher_liste_monstres.html.twig', array("monstres" => $monstres));
        }
        public function royaume_data(){
            $royaumes = $this->getDoctrine()->getRepository(Royaume::class)->findAll();
            $data = [];
            foreach ($royaumes as $royaume){
                $monstresCount = [];
                $monstres = $royaume->getMonstres();
                foreach ($monstres as $monstre) {
                    $monstreType = $monstre->getType();
    
                    if (!isset($monstresCount[$monstreType])) {
                        $monstresCount[$monstreType] = 1;
                    } else {
                        $monstresCount[$monstreType]++;
                    }
                }
                $data[$royaume->getNom()] = $monstresCount;
            }
            return $this->render('royaumes/action13.html.twig', [
                'data' => $data,
            ]);
        }
        public function deplacer_monstres($A, $B){
            $royaumeA = $this->getdoctrine()->getRepository(Royaume::class)->findOneByNom($A);
            $royaumeB = $this->getdoctrine()->getRepository(Royaume::class)->findOneByNom($B);
            $monstresA = $royaumeA->getMonstres();
            foreach ($monstresA as $monstre) {
                $monstre->setRoyaume($royaumeB);
            }
            $monstresB = $royaumeB->getMonstres();
            foreach ($monstresB as $monstre) {
                $monstre->setRoyaume($royaumeA);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute("royaume_data");
        }
        public function afficher_monstres_avec_royaume(){
            $monstres = $this->getDoctrine()->getRepository(Monstre::class)->findAll();
            $data = [];
            foreach ($monstres as $monstre){
                $data[$monstre->getNom()] =  $monstre->getRoyaume();
            }
            return $this->render("monstres/afficher_monstre_avec_royaume.html.twig", array("monstres" => $data));
        }
        public function afficherAvecPartie($value){
            $repo = $this->getDoctrine()->getManager()->getRepository(Monstre::class);
            $monstres = $repo->findByNamePart($value);
            return $this->render("monstres/afficher_liste_monstres.html.twig", array("monstres" => $monstres));
        }
        public function afficherLePlusPuissant(){
            $repo = $this->getDoctrine()->getManager()->getRepository(Monstre::class);
            $monstres = $repo->findMostPowerfulMonsters();
            return $this->render("monstres/afficher_liste_monstres.html.twig", array("monstres" => $monstres));
        }

    }