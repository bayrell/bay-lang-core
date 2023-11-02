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


class RouteInfo
{
	public string $name = "";
	public string $uri = "";
	public string $uri_match = "";
	public string $domain = "";
	public Array $middleware = [];
	public Array $params = [];
	public Map $matches;
	public bool $enable_locale = true;
	public int $pos = 100;
	
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->matches = new Map();
	}
	
	
	/**
	 * Add matches
	 */
	function addMatches(Array $matches)
	{
		$this->matches = [];
		for ($i=0; i<count($this->params); $i++)
		{
			$param_name = $this->params[$i];
			$match_value = $matches[$i];
			$this->matches[$param_name] = $match_value;
		}
	}
	
	
}