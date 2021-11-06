<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Facture;
use App\Entity\User;
use App\Entity\Vehicule;

class FacturationController extends AbstractController
{

	/**
	 * @Route("/facture/{idFacture}", name="facture")
	 */
	public function facture($idFacture)
	{
		if(!$this->getUser()) {
			return $this->redirectToRoute('home');
		}

		$facture = $this->getDoctrine()
						  ->getRepository(Facture::class)
						  ->find($idFacture);

		if(!$facture) {
			return $this->redirectToRoute('home');
		}

		$vehicule = $this->getDoctrine()
						 ->getRepository(Vehicule::class)
						 ->find($facture->getIdVehic());

		$user = $this->getDoctrine()
					 ->getRepository(User::class)
					 ->find($facture->getIdUser());

		if($user->getId() != $this->getUser()->getId() && !$this->isGranted('ROLE_ADMIN')) {
			return $this->redirectToRoute('home');
		}

		$days = date_diff($facture->getDateD(), $facture->getDateF())->days;
		$prixTotal = round($vehicule->getPrix() * $days, 2);
		$prixHT = round($prixTotal / 1.2, 2);

		return $this->render('location/facture/individuelle.html.twig', [
			'facture' => $facture,
			'user' => $user,
			'vehicule' => $vehicule,
			'prixHT' => $prixHT,
			'prixTotal' => $prixTotal,
			'days' => $days,
		]);
	}

	/**
	 * @Route("/factures", name="factureTotale")
	 */
	public function factureTotale()
	{
		if(!$this->getUser()) {
			return $this->redirectToRoute('home');
		}

		$facturesAll = $this->getDoctrine()
						 ->getRepository(Facture::class)
						 ->findBy(["idUser" => $this->getUser()->getId(), "cloture" => 0]);

		$repoVehic = $this->getDoctrine()
						  ->getRepository(Vehicule::class);

		$factures = [];
		$prixFinal = 0;

		foreach($facturesAll as $facture){
			
			$vehicule = $repoVehic->findOneBy(['id' => $facture->getIdVehic()]);
			$jours = date_diff($facture->getDateD(), $facture->getDateF())->days;
			$prixTotal = $vehicule->getPrix() * $jours;

			$factures[$facture->getId()] = (object) array(
				'jours' => $jours,
				'prixTotal' => $prixTotal,
				'prixHT' => round($prixTotal / 1.2, 2),
				'vehicule' => $vehicule,
			);

			$prixFinal += $prixTotal;
			
		}
	
		return $this->render('location/facture/total.html.twig', [
			'factures' => $factures,
			'prixFinal' => $prixFinal
		]);
	}
}
