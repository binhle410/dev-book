<?php

namespace Magenta\Bundle\CBookModelBundle\Security;

use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;

class UserBirthdayAuthenticator implements SimpleFormAuthenticatorInterface {
	
	public function __construct() {
	
	}
	
	public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey) {
		try {
			/** @var User $user */
			$user = $userProvider->loadUserByUsername($token->getUsername());
		} catch(UsernameNotFoundException $exception) {
			// CAUTION: this message will be returned to the client
			// (so don't put any un-trusted messages / error strings here)
			throw new CustomUserMessageAuthenticationException('Invalid username or password');
		}
		
		$currentUser     = $token->getUser();
		$isPasswordValid = false;
		
		if($currentUser instanceof User) {
			if($currentUser->getPerson()->getBirthDate()->format('Y-m-d') !== $user->getPerson()->getBirthDate()->format('Y-m-d')) {
				throw new BadCredentialsException('The credentials were changed from another session.');
			}
		} else {
			if('' === ($givenPassword = $token->getCredentials())) {
				throw new BadCredentialsException('The given password cannot be empty.');
			}
			$userPassword = $user->getPerson()->getBirthDate()->format('Ymd');
			if($givenPassword === $userPassword) {
				$isPasswordValid = true;
			} else {
				throw new BadCredentialsException('The given password is invalid.');
			}
		}
		
		if($isPasswordValid) {
			return new UsernamePasswordToken(
				$user,
				$user->getPerson()->getBirthDate()->format('Y-m-d'),
				$providerKey,
				$user->getRoles()
			);
		}
		
		// CAUTION: this message will be returned to the client
		// (so don't put any un-trusted messages / error strings here)
		throw new CustomUserMessageAuthenticationException('Invalid NRIC or Birthday');
	}
	
	public function supportsToken(TokenInterface $token, $providerKey) {
		return $token instanceof UsernamePasswordToken
		       && $token->getProviderKey() === $providerKey;
	}
	
	public function createToken(Request $request, $username, $password, $providerKey) {
		return new UsernamePasswordToken($username, $password, $providerKey);
	}
}