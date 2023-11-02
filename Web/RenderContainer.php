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


class RenderContainer
{
	public ?BaseRoute $base_route = null;
	public ?Request $request = null;
	public ?Response $response = null;
	public ?RouteInfo $route = null;
	public ?Layout $layout = null;
	public Map $frontend_env;
	public Map $backend_storage;
	
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->frontend_env = new Map();
		$this->backend_storage = new Map();
	}
	
	
	/**
	 * Call route
	 */
	function callRoute()
	{
		if ($this->base_route != null)
		{
			$this->base_route->cancelRoute();
		}
		
		if ($this->route == null)
		{
			return;
		}
		
		$class_name = $this->route->class_name;
		$method_name = $this->route->method_name;
		
		/* Create route */
		$this->base_route = \Runtime\rtl::newInstance($class_name, [$this]);
		
		/* Action before */
		$this->base_route->onActionBefore();
		
		/* Call route */
		call_user_func([$this->base_route, $method_name]);
		
		/* Action after */
		if (!$this->base_route->isCancelRoute())
		{
			$this->base_route->onActionAfter();
		}
	}
	
}