<?php

return [

    // Projects configuration
    'projects' => [

        // Users affectations
        'affectations' => [

            // Users affectations roles
            'roles' => [

                // Default role
                'default' => 'employee',

                // Role that can manage
                'can_manage' => 'administrator',

                // Roles list
                'list' => [
                    'employee' => 'Employee',
                    'customer' => 'Customer',
                    'administrator' => 'Administrator'
                ],

                // Roles colors
                'colors' => [
                    'primary' => 'employee',
                    'warning' => 'customer',
                    'danger' => 'administrator'
                ],

            ],

        ],

    ],

    // System constants
    'max_file_size' => 10240,

];
