<?php



return [



    'menu' => [

        [
            'text' => 'Dashboard',
            'is_header' => true
        ],
        [
            'url' => '/',
            'icon' => 'fa fa-laptop',
            'text' => 'Dashboard'
        ],

        [
            'text' => 'CST Flow',
            'is_header' => true,
            'permission' => 'cst.view',

        ],
        [
            'url' => '/cstform',
            'icon' => 'fa fa-file-alt', // document icon
            'text' => 'CST Request',
            'permission' => 'cst.view',
        ],
        [
            'url' => '/todo-list',
            'icon' => 'fa fa-tasks', // task list icon
            'text' => 'Task Todo',
            'permission' => 'todo.list',
        ],
        [
            'url' => '/redo-list',
            'icon' => 'fa fa-redo', // redo/repeat icon
            'text' => 'Redo Tasks',
            'permission' => 'redo.list',
        ],

        [
            'text' => 'Configuration',
            'is_header' => true,
            'permission' =>  "form.config",

        ],
        [
            'icon' => 'fa fa-lock',
            'text' => 'Role & Permission',

            'children' => [
                [
                    'url' => '/users',
                    'text' => 'View List',
                   'permission' =>  'role.view',
                ],
                [
                    'url' => '/users/create',
                    'text' => 'Add User',
                    'permission' =>  'role.view',
                ],
                [
                    'url' => '/roles',
                    'text' => 'Roles',
                    'permission' =>  'role.view',
                ],
            ],
        ],
         [
            'icon' => 'fa fa-dollar-sign',
            'text' => 'Pricing',

            'children' => [
                [
                    'url' => '/pricing',
                    'text' => 'Manage Pricing',
                   'permission' =>  'form.config',
                ],
            ],
        ],
        [
            'icon' => 'fa fa-edit',
            'text' => 'Form Configuration',
         'permission' =>  'form.config',
            'children' => [
                [
                    'url' => '/team',
                    'text' => 'Team',
                  'permission' =>  'form.config',
                ],
                [
                    'url' => '/scenarios',
                    'text' => 'Scenarios',
                   'permission' =>  'form.config',
                ],
                [
                    'url' => '/checklists',
                    'text' => 'Drive Tester Check List',
                     'permission' =>  'form.config',
                ],
                [
                    'url' => '/post-processor-checklists',
                    'text' => 'Post Processor Check List',
                   'permission' =>  'form.config',
                ],
                [
                    'url' => '/reviewer_rejections',
                    'text' => 'Rejection Reasons',
                     'permission' =>  'form.config',
                ],
                [
                    'url' => '/post_processor_rejections',
                    'text' => 'Post Processor Rejection',
                     'permission' =>  'form.config',
                ],
                [
                    'url' => '/region',
                    'text' => 'Region and City List',
                     'permission' =>  'form.config',
                ],
                [
                    'url' => '/import',
                    'text' => 'Import Data',
                    'permission' => 'form.config',
                ],
            ],
        ],

        [
            'url' => '/emailconfig',
            'icon' => 'fa fa-envelope',
            'text' => 'Email Configuration',
             'permission' =>  'email.config',
        ],

        [
            'is_divider' => true,
        ],

        [
            'text' => 'Setting',

            'is_header' => true,
        ],
        [
            'url' => '/profile',
            'icon' => 'fa fa-cog',
            'text' => 'Settings',
        ],


    ],
];
