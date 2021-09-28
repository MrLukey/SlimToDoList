<?php

namespace App\Controllers\DatabaseControllers;
use Psr\Container\ContainerInterface;

class LoginUserController
{
	private ContainerInterface $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __invoke($request, $response, $args)
	{
		$_SESSION['loggedIn'] = false;
		$_SESSION['user'] = null;
		$_SESSION['error'] = true;
		$_SESSION['errorMessage'] = 'Username and password are incorrect.';
		$userInputData = $request->getParsedBody();
		if ($userInputData['userName'] === '' || $userInputData['rawPassword'] === ''){
			$_SESSION['error'] = 'Username and password must be supplied.';
			return $response->withStatus(500)->withHeader('Location', './login');
		}
		$userModel = $this->container->get('userModel');
		$hashPasswordData = $userModel->getHashedPasswordForUser($userInputData['userName'])[0];
		if (isset($hashPasswordData['exception'])){
			$errorLogger = $this->container->get('errorLoggerModel');
			$errorLogger->logDataBaseError($hashPasswordData['cause'], $hashPasswordData['exception']);
		} elseif (isset($hashPasswordData['hashPassword'])){
			if (password_verify($userInputData['rawPassword'], $hashPasswordData['hashPassword'])){
				$userData = $userModel->getUserByName($userInputData['userName']);
				if (isset($userData['exception'])) {
					$errorLogger = $this->container->get('errorLoggerModel');
					$errorLogger->logDatabaseError($userData['cause'], $userData['exception']);
					$_SESSION['error'] = 'Unexpected database error.';
				} else {
					$_SESSION['loggedIn'] = true;
					$_SESSION['user'] = $userData[0];
					$_SESSION['error'] = false;
					$_SESSION['errorMessage'] = '';
					return $response->withStatus(200)->withHeader('Location', './');
				}
			} else {
				$_SESSION['loginAttempts']++;
			}
		}
		return $response->withStatus(500)->withHeader('Location', './login');
	}
}