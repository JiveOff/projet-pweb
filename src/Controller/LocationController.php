<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Entity\Facture;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Form\AjoutVoiture;

class LocationController extends AbstractController
{
	/**
	 * @Route("/", name="home")
	 */
	public function home()
	{
		return $this->homeOrdre("asc");
	}

	/**
	 * @Route("/catalogue/{ordre}", name="homeOrdre")
	 */
	public function homeOrdre($ordre)
	{
		$vehicules = $this->getDoctrine()
						  ->getRepository(Vehicule::class)
						  ->findAllAvailable($ordre);

		return $this->render('home.html.twig', [
			'vehicules' => $vehicules,
			'ordre' => $ordre
		]);
	}

	/**
	 * @Route("/voiture/{id}", name="louerVoiture")
	 */
	public function louerVoiture($id, Request $request)
	{
		$message = null;
		if (count($request->query)) $message = "Date saisie invalide.";

		$vehicule = $this->getDoctrine()
						 ->getRepository(Vehicule::class)
						 ->find($id);

		return $this->render('location/voiture.html.twig', [
			'vehicule' => $vehicule,
			'message' => $message,
		]);	
	}

	/**
	 * @Route("/creationFacture", name="creationFacture")
	 */
	public function creationFacture(Request $request, EntityManagerInterface $manager) {
		if(!$this->getUser()) {
			return $this->redirectToRoute('home');
		}
		
		dump($request);
		$dateD = $request->request->get('dateDebut');
		$dateF = $request->request->get('dateFin');
		$prix = $request->request->get('prix');
		dump($dateD, $dateF);
		if (strlen($dateD) > 0 && strlen($dateF) > 0) {
			$vehic = $this->getDoctrine()->getRepository(Vehicule::class)->find($request->request->get('id'));
			$dateD = new \DateTime($dateD);
			$dateF = new \DateTime($dateF);
			$facture = new Facture();
			$facture->setIdVehic($request->request->get('id'))
					->setIdUser($this->getUser()->getId())
					->setMontant(date_diff($dateD, $dateF)->days * $prix)
					->setDateD($dateD)
					->setDateF($dateF)
					->setPayee(False)
					->setCloture(False);
			$manager->persist($facture);
			$vehic->setQuantite($vehic->getQuantite() - 1);
			$manager->flush();
			return $this->redirectToRoute("facture", ['idFacture' => $facture->getId()]);
		} else {
			return $this->redirectToRoute("louerVoiture", ['id' => $request->request->get('id'), 'error' => 1]);
		}
	}

	/**
	 * @Route("/locations", name="compte")
	 */
	public function locations()
	{
		if(!$this->getUser()) {
			return $this->redirectToRoute('home');
		}

		$factures = $this->getDoctrine()
						 ->getRepository(Facture::class)
						 ->findBy(["idUser" => $this->getUser()->getId(), "cloture" => 0]);

		$repo = $this->getDoctrine()
					 ->getRepository(Vehicule::class);
		
		$vehicules = [];
		foreach($factures as $facture){
			$vehicules[$facture->getIdVehic()] = $repo->findOneBy(['id' => $facture->getIdVehic()]);
		}

		return $this->render('location/locations.html.twig', [
			'factures' => $factures,
			'vehicules' => $vehicules
		]);
	}

	/**
	 * @Route("/locations/annuler/{id}", name="annulerLocation")
	 */
	public function annulerLocation($id, Request $request)
	{	
		if(!$this->getUser()) {
			return $this->redirectToRoute('home');
		}

		$manager = $this->getDoctrine()->getManager();
		$facture = $manager->getRepository(Facture::class)->find($id);
		$vehicule = $manager->getRepository(Vehicule::class)->find($facture->getIdVehic());

		if($facture->getIdUser() != $this->getUser()->getId()) {
			return $this->redirectToRoute('home');
		}

		$vehicule->setQuantite($vehicule->getQuantite() + 1);
		$facture->setCloture(true);
		$manager->flush();

		return $this->redirectToRoute('compte');
	}
}
