<?php

return [

    // Projects configuration
    'projects' => [

        // Users affectations
        'affectations' => [

            // Users affectations roles
            'roles' => [

                // Default role
                'default' => 'collaborator',

                // Role that can manage
                'can_manage' => 'administrator',

                // Roles list
                'list' => [
                    'collaborator' => 'Collaborator',
                    'administrator' => 'Administrator'
                ],

                // Roles colors
                'colors' => [
                    'primary' => 'collaborator',
                    'danger' => 'administrator'
                ],

            ],

        ],

    ],

    // System constants
    'max_file_size' => 10240,

];
