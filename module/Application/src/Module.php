<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application;

use Laminas\ModuleManager\ModuleManager;
use Laminas\ModuleManager\ModuleEvent;
use Laminas\EventManager\EventInterface as Event;
use Util\UtilResponse;

class Module
{
    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(Event $e)
    {
        $auth = (array) $e->getRequest()->getHeaders("Authorization", null);
        $val = "";
        foreach ($auth as $key => $value) {
          $val = $value;
        }
        if ($val != "e-goi") {
          $e->getResponse()->setStatusCode(500);
          (new UtilResponse)->responseToken();
        }
    }

}
