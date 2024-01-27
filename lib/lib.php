<?php

/* Runtime */
$loader->addPsr4("Runtime\\",  __DIR__ . "/Runtime/php");
$loader->addPsr4("Runtime\\Console\\",  __DIR__ . "/Runtime.Console/php");
$loader->addPsr4("Runtime\\Crypt\\",  __DIR__ . "/Runtime.Crypt/php");
$loader->addPsr4("Runtime\\ORM\\",  __DIR__ . "/Runtime.ORM/php");
$loader->addPsr4("Runtime\\Unit\\",  __DIR__ . "/Runtime.Unit/php");
$loader->addPsr4("Runtime\\Web\\",  __DIR__ . "/Runtime.Web/php");
$loader->addPsr4("Runtime\\Widget\\",  __DIR__ . "/Runtime.Widget/php");
$loader->addPsr4("Runtime\\Widget\\Crud\\",  __DIR__ . "/Runtime.Widget.Crud/php");
$loader->addPsr4("Runtime\\Widget\\Dialog\\",  __DIR__ . "/Runtime.Widget.Dialog/php");
$loader->addPsr4("Runtime\\Widget\\Form\\",  __DIR__ . "/Runtime.Widget.Form/php");
$loader->addPsr4("Runtime\\Widget\\Tab\\",  __DIR__ . "/Runtime.Widget.Tab/php");
$loader->addPsr4("Runtime\\Widget\\Table\\",  __DIR__ . "/Runtime.Widget.Table/php");
$loader->addPsr4("Runtime\\XML\\",  __DIR__ . "/Runtime.XML/php");