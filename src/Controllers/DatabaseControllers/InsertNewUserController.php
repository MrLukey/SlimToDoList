<?php

namespace App\Controllers\DatabaseControllers;
use Psr\Container\ContainerInterface;

class InsertNewUserController
{
	private ContainerInterface $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __invoke($request, $response, $args)
	{
		$userModel = $this->container->get('userModel');
		$userData = $request->getParsedBody();
		if ($userData['userName'] === '' || $userData['password'] === '')
			return $response->withStatus(500)->withHeader('Location', './login');

		$hashPassword = password_hash($userData['rawPassword'], PASSWORD_DEFAULT);
		$errorData = $userModel->insertNewUser($userData['userName'], $hashPassword);
		if ($errorData) {
			$errorLogger = $this->container->get('errorLoggerModel');
			$errorLogger->logDatabaseError($errorData['cause'], $errorData['exception']);
			$status = 500;
		} else
			$status = 200;
		return $response->withStatus($status)->withHeader('Location', './');
	}
}