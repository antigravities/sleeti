<?php

namespace Eeti\Controllers;

class InstallController extends Controller
{
	public function getInstall($request, $response) {
		return $this->container->view->render($response, 'install.twig');
	}

	public function postInstall($request, $response) {
		$settings = [
			'site' => [
				'title' => $request->getParam('title'),
			],
			'db' => [
				'driver'    => $request->getParam('dbdriver'),
				'host'      => $request->getParam('dbhost'),
				'database'  => $request->getParam('dbname'),
				'username'  => $request->getParam('dbuser'),
				'password'  => $request->getParam('dbpass'),
				'charset'   => $request->getParam('dbcharset'),
				'collation' => $request->getParam('dbcollation'),
			],
			'password' => [
				'cost' => (int) ($request->getParam('hashcost')),
			],
			'upload' => [
				'path' => $request->getParam('uploadpath'),
			],
		];

		file_put_contents(__DIR__ . '/../../config/config.json', json_encode($settings, JSON_PRETTY_PRINT));
		touch(__DIR__ . '/../../config/lock');

		$this->container->flash->addMessage('success', '<b>Success!</b> Your new instance of eeti slim has been configured! To edit the config, see <code>/config/config.json</code> and the ACP.');
		$this->container->flash->addMessage('info', 'The first registered account will have administrator permissions. Register an account now.');
		return $response->withRedirect($this->container->router->pathFor('home'));
	}
}