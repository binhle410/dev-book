<?php

namespace Magenta\Bundle\CBookAdminBundle\Twig;

use Magenta\Bundle\SWarrantyModelBundle\Entity\Media\Media;
use Magenta\Bundle\SWarrantyModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\SWarrantyModelBundle\Entity\Organisation\OrganisationMember;
use Magenta\Bundle\SWarrantyModelBundle\Service\User\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MagentaTwigExtension extends AbstractExtension {
	
	const TYPE_PDF_DOWNLOAD_SERVICE_SHEET = 'PDF_DOWNLOAD_SERVICE_SHEET';
	
	/** @var ContainerInterface $container */
	private $container;
	
	public function __construct(ContainerInterface $c) {
		$this->container = $c;
	}
	
	public function getFilters() {
		return array(
			new TwigFilter('privateMediumUrl', array( $this, 'privateMediumUrl' )),
		);
	}
	
	public function getFunctions() {
		return array(
			new \Twig_SimpleFunction('currentOrganisation', array( $this, 'getCurrentOrganisation' )),
			new \Twig_SimpleFunction('organisationBySubdomain', array( $this, 'organisationBySubdomain' )),
			new \Twig_SimpleFunction('privateMediumUrl', array( $this, 'privateMediumUrl' )),
		);
	}
	
	public function getCurrentOrganisation() {
		$repo = $this->container->get('doctrine')->getRepository(Organisation::class);
		$user = $this->container->get(UserService::class)->getUser();
		if(empty($org = $user->getAdminOrganisation())) {
			if( ! empty($person = $user->getPerson())) {
				/** @var OrganisationMember $m */
				$m = $person->getMembers()->first();
				if( ! empty($m)) {
					return $m->getOrganization();
				}
			}
		}
		
		return $org;
	}
	
	public function downloadPdfUrl($type) {
//		if($type === )
	
	}
	
	public function privateMediumUrl($mediumId, $format = 'admin') {
		$c = $this->container;
		
		return $c->get('sonata.media.manager.media')->generatePrivateUrl($mediumId, $format);
	}
}
