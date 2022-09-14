<?php

use Bitrix\Main;
$eventManager = Main\EventManager::getInstance();

$eventManager->addEventHandler('iblock', 'OnIBlockPropertyBuildList', ['usertype\CUserTypeTest', 'GetUserTypeDescription']);

