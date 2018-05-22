<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Magenta\Bundle\CBookModelBundle\Tests\User\Service;

use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\AbstractUserManager;
use Magenta\Bundle\CBookModelBundle\Util\User\CanonicalFieldsUpdater;
use Magenta\Bundle\CBookModelBundle\Util\User\PasswordUpdaterInterface;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
	/** @var AbstractUserManager|\PHPUnit_Framework_MockObject_MockObject */
	private $manager;
	
	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $passwordUpdater;
	
	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $fieldsUpdater;
	
	protected function setUp()
	{
		$this->passwordUpdater = $this->getMockBuilder(PasswordUpdaterInterface::class)->getMock();
		$this->fieldsUpdater = $this->getMockBuilder(CanonicalFieldsUpdater::class)
		                            ->disableOriginalConstructor()
		                            ->getMock();
		
		$this->manager = $this->getUserManager(array(
			$this->passwordUpdater,
			$this->fieldsUpdater,
		));
	}
	
	public function testUpdateCanonicalFields()
	{
		$user = $this->getUser();
		
		$this->fieldsUpdater->expects($this->once())
		                    ->method('updateCanonicalFields')
		                    ->with($this->identicalTo($user));
		
		$this->manager->updateCanonicalFields($user);
	}
	
	public function testUpdatePassword()
	{
		$user = $this->getUser();
		
		$this->passwordUpdater->expects($this->once())
		                      ->method('hashPassword')
		                      ->with($this->identicalTo($user));
		
		$this->manager->updatePassword($user);
	}
	
	public function testFindUserByUsername()
	{
		$this->manager->expects($this->once())
		              ->method('findUserBy')
		              ->with($this->equalTo(array('usernameCanonical' => 'jack')));
		$this->fieldsUpdater->expects($this->once())
		                    ->method('canonicalizeUsername')
		                    ->with('jack')
		                    ->will($this->returnValue('jack'));
		
		$this->manager->findUserByUsername('jack');
	}
	
	public function testFindUserByUsernameLowercasesTheUsername()
	{
		$this->manager->expects($this->once())
		              ->method('findUserBy')
		              ->with($this->equalTo(array('usernameCanonical' => 'jack')));
		$this->fieldsUpdater->expects($this->once())
		                    ->method('canonicalizeUsername')
		                    ->with('JaCk')
		                    ->will($this->returnValue('jack'));
		
		$this->manager->findUserByUsername('JaCk');
	}
	
	public function testFindUserByEmail()
	{
		$this->manager->expects($this->once())
		              ->method('findUserBy')
		              ->with($this->equalTo(array('emailCanonical' => 'jack@email.org')));
		$this->fieldsUpdater->expects($this->once())
		                    ->method('canonicalizeEmail')
		                    ->with('jack@email.org')
		                    ->will($this->returnValue('jack@email.org'));
		
		$this->manager->findUserByEmail('jack@email.org');
	}
	
	public function testFindUserByEmailLowercasesTheEmail()
	{
		$this->manager->expects($this->once())
		              ->method('findUserBy')
		              ->with($this->equalTo(array('emailCanonical' => 'jack@email.org')));
		$this->fieldsUpdater->expects($this->once())
		                    ->method('canonicalizeEmail')
		                    ->with('JaCk@EmAiL.oRg')
		                    ->will($this->returnValue('jack@email.org'));
		
		$this->manager->findUserByEmail('JaCk@EmAiL.oRg');
	}
	
	public function testFindUserByUsernameOrEmailWithUsername()
	{
		$this->manager->expects($this->once())
		              ->method('findUserBy')
		              ->with($this->equalTo(array('usernameCanonical' => 'jack')));
		$this->fieldsUpdater->expects($this->once())
		                    ->method('canonicalizeUsername')
		                    ->with('JaCk')
		                    ->will($this->returnValue('jack'));
		
		$this->manager->findUserByUsernameOrEmail('JaCk');
	}
	
	public function testFindUserByUsernameOrEmailWithEmail()
	{
		$this->manager->expects($this->once())
		              ->method('findUserBy')
		              ->with($this->equalTo(array('emailCanonical' => 'jack@email.org')))
		              ->willReturn($this->getUser());
		$this->fieldsUpdater->expects($this->once())
		                    ->method('canonicalizeEmail')
		                    ->with('JaCk@EmAiL.oRg')
		                    ->will($this->returnValue('jack@email.org'));
		
		$this->manager->findUserByUsernameOrEmail('JaCk@EmAiL.oRg');
	}
	
	public function testFindUserByUsernameOrEmailWithUsernameThatLooksLikeEmail()
	{
		$usernameThatLooksLikeEmail = 'bob@example.com';
		$user = $this->getUser();
		
		$this->manager->expects($this->at(0))
		              ->method('findUserBy')
		              ->with($this->equalTo(array('emailCanonical' => $usernameThatLooksLikeEmail)))
		              ->will($this->returnValue(null));
		$this->fieldsUpdater->expects($this->once())
		                    ->method('canonicalizeEmail')
		                    ->with($usernameThatLooksLikeEmail)
		                    ->willReturn($usernameThatLooksLikeEmail);
		
		$this->manager->expects($this->at(1))
		              ->method('findUserBy')
		              ->with($this->equalTo(array('usernameCanonical' => $usernameThatLooksLikeEmail)))
		              ->will($this->returnValue($user));
		$this->fieldsUpdater->expects($this->once())
		                    ->method('canonicalizeUsername')
		                    ->with($usernameThatLooksLikeEmail)
		                    ->willReturn($usernameThatLooksLikeEmail);
		
		$actualUser = $this->manager->findUserByUsernameOrEmail($usernameThatLooksLikeEmail);
		
		$this->assertSame($user, $actualUser);
	}
	
	/**
	 * @return mixed
	 */
	private function getUser()
	{
		return $this->getMockBuilder(User::class)
		            ->getMockForAbstractClass();
	}
	
	/**
	 * @param array $args
	 *
	 * @return mixed
	 */
	private function getUserManager(array $args)
	{
		return $this->getMockBuilder(AbstractUserManager::class)
		            ->setConstructorArgs($args)
		            ->getMockForAbstractClass();
	}
}