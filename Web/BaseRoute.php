<?php

/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2023 "Ildar Bikmamatov" <support@bayrell.org>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace Runtime\Web;

use Runtime\Map;


class BaseRoute
{
	public bool $is_cancel_route = false;
	public RenderContainer $container;
	
	
	/**
	 * Constructor
	 */
	public function __construct(RenderContainer $container)
	{
		$this->container = $container;
	}
	
	
	/**
	 * Returns routes
	 */
	static function getRoutes()
	{
		return [];
	}
	
	
	/**
	 * Before route
	 */
	function onActionBefore()
	{
	}
	
	
	/**
	 * After route
	 */
	function onActionAfter()
	{
	}
	
	
	/**
	 * Cancel route
	 */
	function cancelRoute()
	{
		$this->is_cancel_route = true;
	}
	
	
	/**
	 * Returns true if current route is canceled
	 */
	function isCancelRoute()
	{
		return $this->is_cancel_route;
	}
	
	
	/**
	 * Returns url
	 */
	static function url(string $route_name, Map $route_params=null)
	{
		$context = Rtl::getContext();
		$routes = $context->provider("Runtime.Web.RouteList");
		return $routes->url($route_name, $route_params);
	}
}