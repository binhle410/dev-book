<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magenta\Bundle\CBookModelBundle\Doctrine\User;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Entity\User\UserInterface;
use Magenta\Bundle\CBookModelBundle\Util\User\CanonicalFieldsUpdater;
use Magenta\Bundle\CBookModelBundle\Util\User\PasswordUpdaterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Doctrine listener updating the canonical username and password fields.
 *
 * @author Christophe Coevoet <stof@notk.org>
 * @author David Buchmann <mail@davidbu.ch>
 */
class UserEventSubsriber implements EventSubscriber {
	private $passwordUpdater;
	private $canonicalFieldsUpdater;
	private $container;
	
	public function __construct(PasswordUpdaterInterface $passwordUpdater, CanonicalFieldsUpdater $canonicalFieldsUpdater, ContainerInterface $c) {
		$this->passwordUpdater        = $passwordUpdater;
		$this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
		$this->container              = $c;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents() {
		return array(
			'prePersist',
			'preUpdate',
		);
	}
	
	/**
	 * Pre persist listener based on doctrine common.
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args) {
		$object = $args->getObject();
		if($object instanceof UserInterface) {
			$this->updateUserFields($object);
		}
	}
	
	/**
	 * Pre update listener based on doctrine common.
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function preUpdate(LifecycleEventArgs $args) {
		$object = $args->getObject();
		if($object instanceof UserInterface) {
			/** @var User $user */
			$user = $object;
			if( ! empty($person = $user->getPerson()) && $person->getEmail() !== $user->getEmail()) {
				$personRepo = $this->container->get('doctrine')->getRepository(Person::class);
				if( ! empty($email = $person->getEmail())) {
					/** @var Person $m_person */
					$m_person = $personRepo->findOneByEmail($email);
					if( ! empty($m_person)) {
						$person = $m_person;
					}
				}
				$user->setPerson($person);
				$person->setUser($user);
				$manager = $this->container->get('doctrine.orm.default_entity_manager');
				$manager->persist($person);
			}
			
			$this->updateUserFields($object);
			$this->recomputeChangeSet($args->getObjectManager(), $object);
		}
	}
	
	/**
	 * Updates the user properties.
	 *
	 * @param UserInterface $user
	 */
	private function updateUserFields(UserInterface $user) {
		//////// MODIF 001 ///////
		if($user instanceof User) {
			if( ! empty($person = $user->getPerson())) {
				if(empty($person->getEmail())) {
					$person->setEmail($user->getEmail());
				}
//				$user->setUsername($person->getEmail());
			}
		}
		//////// END MODIF 001 ///////
		
		$this->canonicalFieldsUpdater->updateCanonicalFields($user);
		$this->passwordUpdater->hashPassword($user);
	}
	
	/**
	 * Recomputes change set for Doctrine implementations not doing it automatically after the event.
	 *
	 * @param ObjectManager $om
	 * @param UserInterface $user
	 */
	private function recomputeChangeSet(ObjectManager $om, UserInterface $user) {
		$meta = $om->getClassMetadata(get_class($user));
		
		if($om instanceof EntityManager) {
			$om->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $user);
			
			return;
		}
		
		if($om instanceof DocumentManager) {
			$om->getUnitOfWork()->recomputeSingleDocumentChangeSet($meta, $user);
		}
	}
	
}
