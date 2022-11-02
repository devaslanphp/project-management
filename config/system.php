<?php

return [

    'projects' => [

        'affectations' => [

            'roles' => [

                'default' => 'collaborator',

                'can_manage' => 'Administrator',

                'list' => [
                    'collaborator' => 'Collaborator',
                    'administrator' => 'Administrator'
                ],

                'colors' => [
                    'primary' => 'collaborator',
                    'danger' => 'administrator'
                ],

            ],

        ],

    ],

];
