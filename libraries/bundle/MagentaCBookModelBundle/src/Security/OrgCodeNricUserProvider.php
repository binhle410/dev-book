<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magenta\Bundle\CBookModelBundle\Security;

class OrgCodeNricUserProvider extends UserProvider {
	/**
	 * {@inheritdoc}
	 */
	protected function findUser($username) {
		$usernameExploded = explode('@at@', $username);
		if(count($usernameExploded) != 2) {
			return null;
		}
		$companyCode = $usernameExploded[0];
		$nric        = $usernameExploded[1];
		
		return $this->userManager->findUserByOrganisationCodeNric($companyCode, $nric);
	}
}