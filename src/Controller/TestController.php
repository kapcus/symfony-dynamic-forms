<?php

namespace App\Controller;

use App\Form\PostSubmitAddressType;
use App\Form\PreSubmitAddressType;
use App\Model\AddressModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-post', name: 'test-post')]
	public function testPost(Request $request): Response {

		$form = $this->createForm(
			PostSubmitAddressType::class,
			['country' => AddressModel::COUNTRY_CZ_CODE, 'city' => AddressModel::CITY_PRAGUE_CODE, 'street' => AddressModel::STREET_HIGH_CODE],
			['action' => 'test-post']
		);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			if ($request->isXmlHttpRequest() && $form->getClickedButton() === null) {
				return $this->render(
					'test/_form.html.twig',
					[
						'form' => $form->createView(),
						'formtitle' => 'POST SUBMIT FORM'
					]
				);
			}

			// final form submit processing would be here
			$this->addFlash('success', 'Form submitted successfully.');

			return $this->redirectToRoute('index');

		}

		return $this->render(
			'test/address.html.twig',
			[
				'form' => $form->createView(),
				'formtitle' => 'POST SUBMIT FORM'
			]
		);
	}

	#[Route('/test-pre', name: 'test-pre')]
	public function testPre(Request $request): Response {

		$form = $this->createForm(
			PreSubmitAddressType::class,
			['country' => AddressModel::COUNTRY_CZ_CODE, 'city' => AddressModel::CITY_PRAGUE_CODE, 'street' => AddressModel::STREET_HIGH_CODE],
			['action' => 'test-pre']
		);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			if ($request->isXmlHttpRequest() && $form->getClickedButton() === null) {
				return $this->render(
					'test/_form.html.twig',
					[
						'form' => $form->createView(),
						'formtitle' => 'PRE SUBMIT FORM'
					]
				);
			}

			// final form submit processing would be here

			$this->addFlash('success', 'Form submitted successfully.');

			return $this->redirectToRoute('index');

		}

		return $this->render(
			'test/address.html.twig',
			[
				'form' => $form->createView(),
				'formtitle' => 'PRE SUBMIT FORM'
			]
		);
	}

	#[Route('/', name: 'index')]
	public function index(Request $request): Response {

		return $this->render(
			'test/index.html.twig',
			[
			]
		);
	}
}
