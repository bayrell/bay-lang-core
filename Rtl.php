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

namespace Runtime;

use \Runtime\Context;


class Rtl
{
	static $_global_context = null;
	
	
	/**
	 * Returns global context
	 * @return Context
	 */
	static function getContext()
	{
		if (!static::$_global_context) return new Context();
		return static::$_global_context;
	}
	
	
	/**
	 * Set global context
	 * @param Context context
	 */
	static function setContext($context)
	{
		static::$_global_context = $context;
		return $context;
	}
	
	
	/**
	 * Create context
	 */
	static function createContext(array $params)
	{
		$ctx = null;
		
		/* Create contenxt */
		$context = Context::create($params);
		
		/* Init context */
		$context = $context->init();
		
		/* Setup global context */
		static::setContext($context);
		
		return $context;
	}
	
	
	/**
	 * Run application
	 * @param Dict d
	 */
	static function runApp(string $class_name, array $modules, array $params=null)
	{
		if ($params == null)
		{
			$params = [];
		}
		
		Rtl::setAttr($params, ["entry_point"], $class_name);
		Rtl::setAttr($params, ["modules"], $modules);
		
		$context = static::createContext($d);
		$context->start();
		$code = $context->run();
		return $code;
	}
	
	
	/**
	 * Generate uuid
	 */
	static function uid()
	{
		$bytes = bin2hex(random_bytes(16));
		return substr($bytes, 0, 8) . "-" .
			substr($bytes, 8, 4) . "-" .
			substr($bytes, 12, 4) . "-" .
			substr($bytes, 16, 4) . "-" .
			substr($bytes, 20);
	}
	
	
	/**
	 * Generate timestamp based uuid
	 */
	static function time_uid()
	{
		$bytes = dechex(time()) . bin2hex(random_bytes(12));
		return substr($bytes, 0, 8) . "-" .
			substr($bytes, 8, 4) . "-" .
			substr($bytes, 12, 4) . "-" .
			substr($bytes, 16, 4) . "-" .
			substr($bytes, 20);
	}
	
	
	/**
	 * Returns random value x, where 0 <= x < 1
	 * @return double
	 */
	static function urandom()
	{
		return mt_rand() / (mt_getrandmax() + 1);
	}
	
	
	/**
	 * Returns random value x, where a <= x <= b
	 * @param int a
	 * @param int b
	 * @return int
	 */
	static function random($a, $b)
	{
		if (PHP_VERSION_ID < 70000) return mt_rand($a, $b);
		return random_int($a, $b);
	}
	
	
	/**
	 * Register PHP error handler
	 */
	function set_default_exception_handler()
	{
		set_exception_handler( function ($e){
		
			if (!$e) return;
			
			http_response_code(500);
			
			$message = "Fatal Error:\n";
			$message .= $e->getMessage() . "\n";
			$message .= "in file " . $e->getFile() . ":" . $e->getLine() . "\n";
			$message .= $e->getTraceAsString() . "\n";
			
			if (php_sapi_name() === 'cli')
			{
				$color = "0;91";
				echo chr(27) . "[" . $color . "m" . $message . chr(27) . "[0m";
			}
			else
			{
				echo nl2br($message);
			}
			
			exit (1);
		} );
	}
	
}