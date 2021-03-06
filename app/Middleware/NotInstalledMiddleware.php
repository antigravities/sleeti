<?php

/**
 * This file is part of sleeti.
 * Copyright (C) 2016  Eliot Partridge
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Sleeti\Middleware;

/**
 * Only allows access if the sleeti instance isn't fully installed
 */
class NotInstalledMiddleware extends Middleware
{
	public function __invoke($request, $response, $next) {
		if (file_exists(__DIR__ . '/../../config/lock')) {
			$this->container->flash->addMessage('danger', '<b>Hey!</b> This instance of sleeti is already configured!');

			if ($this->container->auth->check()) {
				$user   = $this->container->auth->user();
				$viewer = $user->username . ' (' . $user->id . ')';
			} else {
				$viewer = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
			}

			$this->container->log->warning('install', $viewer . ' tried to access the install page when Sleeti was already installed.');

			return $response->withStatus(403)->withRedirect($this->container->router->pathFor('home'));
		}

		$response = $next($request, $response);
		return $response;
	}
}
