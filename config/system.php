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

    // Tickets configuration
    'tickets' => [

        // Ticket relations types
        'relations' => [

            // Default type
            'default' => 'related_to',

            // Types list
            'list' => [
                'related_to' => 'Related to',
                'blocked_by' => 'Blocked by',
                'duplicate_of' => 'Duplicate of'
            ],

            // Types colors
            'colors' => [
                'related_to' => 'primary',
                'blocked_by' => 'warning',
                'duplicate_of' => 'danger',
            ],

        ],

    ],

    // System constants
    'max_file_size' => 10240,

];
