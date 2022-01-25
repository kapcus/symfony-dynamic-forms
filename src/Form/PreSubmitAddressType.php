<?php

namespace App\Form;

use App\Model\AddressModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreSubmitAddressType extends AbstractType {

	private AddressModel $addressModel;

	public function __construct(AddressModel $addressModel) {
		$this->addressModel = $addressModel;
	}
	public function configureOptions(OptionsResolver $resolver): void {
		$resolver->setDefaults(
			[
				//'csrf_protection' => false,
			]
		);
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add(
				'country',
				ChoiceType::class,
				[
					'label' => 'Country',
					'choices' => $this->addressModel->getCountries(),
				]
			);

		$this->handleCity($builder);
		$this->handleStreet($builder);

		$builder->add('submit', SubmitType::class, ['label' => 'Submit']);
	}

	public function handleCity(FormBuilderInterface $builder) {

		$cityFormModifier = function (FormInterface $form, ?string $country) {
			$cities = $this->addressModel->getCountryCities($country);
			$form->add(
				'city',
				ChoiceType::class,
				[
					'expanded' => true,
					'label' => 'City',
					'choices' => $this->addressModel->getCountryCities($country),
					'empty_data' => reset($cities),
				]
			);
		};

		$builder->addEventListener(
			FormEvents::PRE_SET_DATA,
			function (FormEvent $event) use ($cityFormModifier) {
				$data = $event->getData();
				$cityFormModifier($event->getForm(), $data['country'] ?? null);
			}
		);

		$builder->addEventListener(
			FormEvents::PRE_SUBMIT,
			function (FormEvent $event) use ($cityFormModifier) {
				$data = $event->getData();
				$cityFormModifier($event->getForm(), $data['country'] ?? null);
			}
		);
	}

	public function handleStreet(FormBuilderInterface $builder) {

		$streetFormModifier = function (FormInterface $form, ?string $country, ?string $city) {
			if (!isset($city)) {
				$city = $this->addressModel->getDefaultCountryCity($country);
			}
			$streets = $this->addressModel->getCityStreets($city);
			$form->add(
				'street',
				ChoiceType::class,
				[
					'expanded' => true,
					'label' => 'Street',
					'choices' => $this->addressModel->getCityStreets($city),
					'empty_data' => reset($streets),
				]
			);
		};

		$builder->addEventListener(
			FormEvents::PRE_SET_DATA,
			function (FormEvent $event) use ($streetFormModifier) {
				$data = $event->getData();
				$streetFormModifier($event->getForm(), $data['country'] ?? null, $data['city'] ?? null);
			}
		);

		$builder->addEventListener(
			FormEvents::PRE_SUBMIT,
			function (FormEvent $event) use ($streetFormModifier) {
				$data = $event->getData();
				$streetFormModifier($event->getForm(), $data['country'] ?? null, $data['city'] ?? null);
			}
		);
	}
}