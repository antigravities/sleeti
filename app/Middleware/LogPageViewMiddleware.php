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
 * Logs all page views to debug channel
 */
class LogPageViewMiddleware extends Middleware
{
	public function __invoke($request, $response, $next) {
		$path = $request->getUri()->getPath();
		if ($this->container->auth->check()) {
			$user   = $this->container->auth->user();
			$viewer = $user->username . ' (' . $user->id . ')';
		} else {
			$viewer = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
		}

		$this->container->log->debug('pageview', 'Pageview from ' . $viewer . ' (' . $path . ').');

		$response = $next($request, $response);
		return $response;
	}
}
