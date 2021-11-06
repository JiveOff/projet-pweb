<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Entity\Facture;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Form\AjoutVoiture;

class AdminController extends AbstractController
{

	/**
	 * @Route("/admin/users", name="admin_users")
	 */
	public function adminUsers()
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
		
		$factures = $this->getDoctrine()
						  ->getRepository(Facture::class);

		$users = $this->getDoctrine()
						  ->getRepository(User::class)
						  ->findAll();

		$vehicules = $this->getDoctrine()
						  ->getRepository(Vehicule::class)
						  ->findAll();

		$usersFacture = [];
		foreach($users as $user){
			$usersFacture[$user->getId()] = $factures->findBy(['idUser' => $user->getId()]);
		}

		$vehiculesArr = [];
		foreach($vehicules as $vehicule){
			$vehiculesArr[$vehicule->getId()] = $vehicule;
		}

		return $this->render('location/admin/utilisateurs.html.twig', [
			'users' => $users,
			'factures' => $usersFacture,
			'vehicules' => $vehiculesArr
		]);
	}

	/**
	 * @Route("/admin/vehicules", name="admin_vehicule")
	 */
	public function adminVehicule()
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
		
		$vehicules = $this->getDoctrine()
						  ->getRepository(Vehicule::class)
						  ->findAll();

		return $this->render('location/admin/vehicules.html.twig', [
			'vehicules' => $vehicules
		]);
	}

    /**
	 * @Route("/admin/facture/annuler/{id}", name="annulerFacture")
	 */
	public function adminAnnulerFacture($id, Request $request)
	{	
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

		$manager = $this->getDoctrine()->getManager();
		$facture = $manager->getRepository(Facture::class)->find($id);
		$vehicule = $manager->getRepository(Vehicule::class)->find($facture->getIdVehic());
		$vehicule->setQuantite($vehicule->getQuantite() + 1);
		$facture->setCloture(true);
		$manager->flush();

		return $this->redirectToRoute('admin_locations');
	}

    /**
	 * @Route("/admin/facture/paiement/{id}", name="paiementFacture")
	 */
	public function adminPaiementFacture($id, Request $request)
	{	
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

		$manager = $this->getDoctrine()->getManager();
		$facture = $manager->getRepository(Facture::class)->find($id);
		$facture->setPayee(true);
		$manager->flush();

		return $this->redirectToRoute('admin_locations');
	}

	/**
	 * @Route("/admin/modifierDispo", name="modifierDispo")
	 */
	public function adminModifierDispo(Request $request)
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
		
		$manager = $this->getDoctrine()->getManager();

		$vehicule = $manager->getRepository(Vehicule::class)->find($request->query->get('idVehic'));
		
		$vehicule->toggleDispo();
		$manager->flush();

		return $this->redirectToRoute('admin_vehicule');
	}

	/**
	 * @Route("/admin/modifierStock", name="modifierStock")
	 */
	public function adminModifierStock(Request $request)
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
		
		$manager = $this->getDoctrine()->getManager();

		$vehicule = $manager->getRepository(Vehicule::class)->find($request->query->get('id'));
		
		$vehicule->setQuantite($request->request->get('quantite'));
		$manager->flush();

		return $this->redirectToRoute('admin_vehicule');
	}

	/**
	 * @Route("/admin/locations", name="admin_locations")
	 */
	public function adminLocations()
	{
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

		$repoUsers = $this->getDoctrine()
						->getRepository(User::class);

		$repoVehicules = $this->getDoctrine()
						->getRepository(Vehicule::class);

		$factures = $this->getDoctrine()
						->getRepository(Facture::class)
						->findBy(['cloture' => false]);

		$users = [];
		$vehicules = [];

        foreach($factures as $facture) {
            $users[$facture->getIdUser()] = $repoUsers->find($facture->getIdUser());
            $vehicules[$facture->getIdVehic()] = $repoVehicules->find($facture->getIdVehic());
        }

		return $this->render('location/admin/locations.html.twig', [
			'factures' => $factures,
			'vehicules' => $vehicules,
			'users' => $users
		]);
	}

	/**
	 * @Route("/admin/ajouter", name="admin_ajouter")
	 * Ajouter un vehicule à la location
	 */
	public function adminAjouter(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger)
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

		$vehicule = new Vehicule();

		$form = $this->createForm(AjoutVoiture::class, $vehicule);

		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()){
			$imageFile = $form->get('image')->getData();

			$originalFilename = $form->get('carac')->getData()['marque'] . $form->get('modele')->getData();
			$safeFilename = $slugger->slug($originalFilename);
			$newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
			
			$imageFile->move(
				$this->getParameter('image_directory'),
				$newFilename
			);

			$vehicule->setImage('/img/voitures/' . $newFilename);
			$vehicule->setCarac($vehicule->getCarac());
			$vehicule->setDisponible(1);
			$manager->persist($vehicule);
			$manager->flush();
				
			return $this->redirectToRoute('admin_vehicule');
		}
			
		return $this->render('location/admin/ajouter.html.twig', [
			'form' => $form->createView(),
		]);

	}

}
