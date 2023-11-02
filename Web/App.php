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

use Runtime\BaseProvider;
use Runtime\Rtl;


class App extends BaseProvider
{
	/**
	 * Run Web Application
	 */
	function run()
	{
		$container = $this->createRenderContainer();
		$this->initContainer($container);
		$this->resolveContainer($container);
		$this->responseBackend($container);
		return 0;
	}
	
	
	/**
	 * Create render container
	 */
	function createRenderContainer()
	{
		$context = Rtl::getContext();
		$container = $context->create(RenderContainer::class);
		$container->request = $this->createRequest();
		return $container;
	}
	
	
	/**
	 * Init container
	 */
	function initContainer(RenderContainer $container)
	{
		$context = Rtl::getContext();
		$context->setEnvironment("render_container", $container);
		
		/* Call hook */
		$context->callHook(AppHook::INIT_CONTAINER, [
			"container" => $container,
		]);
	}
	
	
	/**
	 * Resolve container
	 */
	function resolveContainer(RenderContainer $container)
	{
		$context = Rtl::getContext();
		
		/* Find route */
		$this->findRoute($container);
		
		/* Call route */
		$this->callRoute($container);
		
		/* Call hook response */
		$context->callHook(AppHook::RESPONSE, [
			"container" => $container,
		]);
	}
	
	
	/**
	 * Find route
	 */
	function findRoute(RenderContainer $container)
	{
		$context = Rtl::getContext();
		
		/* Call hook find route */
		$context->callHook(AppHook::FIND_ROUTE, new Map([
			"container" => $container,
		]));
		
		/* Find route */
		if ($container->route == null)
		{
			$routes = $context->provider("Runtime.Web.RouteList");
			$container->route = $routes->findRoute($container);
		}
		
		/* Call hook found route */
		$context->callHook(AppHook::FIND_ROUTE_AFTER, new Map([
			"container" => $container,
		]));
	}
	
	
	/**
	 * Call route
	 */
	function callRoute(RenderContainer $container)
	{
		$context = Rtl::getContext();
		
		/* Call route before */
		$context->callHook(AppHook::CALL_ROUTE_BEFORE, new Map([
			"container" => $container,
		]));
		
		/* Call middleware */
		$this->callRouteMiddleware($container);
		
		/* Call route */
		if ($container->route != null && $container->response == null)
		{
			$container->callRoute();
		}
		
		/* Call route after */
		$context->callHook(AppHook::CALL_ROUTE_AFTER, new Map([
			"container" => $container,
		]));
	}
	
	
	/**
	 * Call route middleware
	 */
	function callRouteMiddleware(RenderContainer $container)
	{
		/* Call hook middleware */
		$context->callHook(AppHook::MIDDLEWARE_BEFORE, new Map([
			"container" => $container,
		]));
		
		if ($container->route && $container->route->middleware)
		{
			for ($i = 0; $i < count($container->route->middleware); $i++)
			{
				$class_name_middleware = $container->route->middleware[$i];
				call_user_func_array([$class_name_middleware, "actionMiddleware"], [$container]);
			}
		}
		
		/* Call hook middleware */
		$context->callHook(AppHook::MIDDLEWARE_AFTER, new Map([
			"container" => $container,
		]));
	}
	
	
	/**
	 * Send response
	 */
	function responseBackend(RenderContainer $container)
	{
		$response = $container->response;
		
		if ($response != null)
		{
			http_response_code($response->http_code);
			
			/* Setup cookies */
			if ($response->cookies != null)
			{
				$response->cookies->each(
					function (Cookie $cookie)
					{
						if ($cookie == null) return;
						if ($cookie->name == "") return;
						
						setcookie
						(
							$cookie->name,
							$cookie->value,
							$cookie->getOptions()
						);
					}
				);
			}
			
			/* Setup headers */
			if ($response->headers != null)
			{
				$response->headers->each(
					function (string $value, string $key)
					{
						header($key . ":" . $value);
					}
				);
			}
			
			/* Redirect */
			if ($response instanceof RedirectResponse)
			{
				header("Location:" . $response->redirect);
			}
			
			echo $response->getContent();
		}
		
		else
		{
			http_response_code(404);
			echo "404 Response not found";
		}
	}
	
}