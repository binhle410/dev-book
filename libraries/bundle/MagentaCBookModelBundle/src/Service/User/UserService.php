<?php
namespace Magenta\Bundle\CBookModelBundle\Service\User;

use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\BaseService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserService extends BaseService
{
	public function addUserIfNotExist(User $user, $password = null, $criteria = [], $roles = []) {
		$flushable = false;
		$container = $this->container;
		$registry  = $container->get('doctrine');
		$userRepo  = $registry->getRepository(User::class);
		/** @var User $userFound */
		if( ! empty($userFound = $userRepo->findOneBy($criteria))) {
//			if( ! empty($userFound->getThanhVien())) {
//				throw new Exception();
//			}
			$user = $userFound;
//			$object->setUser($user);
		} else {
//		$user = $container->get('sonata.user.user_manager')->createUser();
//			$user = $object->getUser();
			$user->addRole(User::ROLE_HUYNH_TRUONG);
			$flushable = true;
		}

//		$username = $object->getUser()->getUsername();
		
		if( ! empty($password)) {
			$user->setPlainPassword($password);
			$flushable = true;
		}
		
		if(count($roles) > 0) {
			$user->setEnabled(true);
			$realRoles = $user->getRoles();
			$user->setRoles(array_merge($roles, $realRoles));
			$flushable = true;
		}
		
		if($flushable) {
			
			$manager = $container->get('doctrine.orm.default_entity_manager');
			$manager->persist($user);
			$manager->flush();
		}
		
		return $user;
	}
	
    public function logUserOut()
    {
        $this->container->get('security.token_storage')->setToken(null);
        $this->container->get('request_stack')->getCurrentRequest()->getSession()->invalidate();
    }

    /**
     * @param User $user
     */
    public function logUserIn(User $user, $firewall = 'main')
    {
        if ($this->isLoggedIn()) {
            return;
        }
        $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
        $this->container->get("security.token_storage")->setToken($token); //now the user is logged in

        //now dispatch the login event
        $request = $this->container->get("request_stack")->getCurrentRequest();
        $event = new InteractiveLoginEvent($request, $token);
        $this->container->get("event_dispatcher")->dispatch("security.interactive_login", $event);
    }

    public function isLoggedIn()
    {
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if (empty($this->getUser(false))) {
            return false;
        }
        return $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
    }

    /**
     * @param bool $throwException
     * @param string $msg
     * @return User
     */
    public function getUser($throwException = true, $msg = 'This user does not have access to this section.')
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        /** @var TokenInterface $token */
        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            if ($throwException) {
                throw new AccessDeniedException($msg);
            }
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            if ($throwException) {
                throw new AccessDeniedException($msg);
            }
            return null;
        }

        if (!($user instanceof UserInterface)) {
            if ($throwException) {
                throw new AccessDeniedException($msg);
            }
            return null;
        }

        return $user;
    }
}