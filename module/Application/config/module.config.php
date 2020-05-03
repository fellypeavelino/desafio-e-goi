<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'add' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/add',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'addCategory',
                    ],
                ],
            ],
            'input' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/input',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'addCategory',
                    ],
                ],
            ],
            'listAll' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/list-all',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'listCategory',
                    ],
                ],
            ],
            'list' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/list/:id',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'listCategoryById',
                    ],
                ],
            ],
            'update' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/update',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'updateCategory',
                    ],
                ],
            ],
            'output' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/output/:id',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'output',
                    ],
                ],
            ],
            'delete' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/delete',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'deleteCategory',
                    ],
                ],
            ],
            'error-authorization' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/error-authorization',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'errorAuthorization',
                    ],
                ],
            ],
            /*'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],*/
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\ApiController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/505'               => __DIR__ . '/../view/error/505.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
