<?php

return [
    'name' => 'Tasks',

    'model' => Modules\Tasks\Models\Tasks::class,

    'admin' => [
        'navigationbar' => [
            "group" => "tasks",
            "link" => [ 
                "name" => "Matchmaker Pro", 
                "icon" => "fas fa-handshake", 
                "link" => "promatch.tasks.index" 
            ],
            "submenu" => [
                /*
                [ 
                    "name" => "Add post", 
                    "icon" => "fas fa-plus-circle",  // mere passende ikon
                    "link" => "promatch.tasks.create" 
                ],
                */
                [ 
                    "name" => "Matches", 
                    "icon" => "fas fa-cogs",  // ikon til settings
                    "link" => "promatch.matches.index"  // rettet fra settings.rating.index
                ],
                [ 
                    "name" => "Ratings", 
                    "icon" => "fas fa-star",  // ikon til ratings
                    "link" => "promatch.rating.index" 
                ],
                [ 
                    "name" => "Rating schedules", 
                    "icon" => "fas fa-star",  // ikon til ratings
                    "link" => "promatch.ratings-scheduled.index" 
                ],

                
                [ 
                    "name" => "Settings", 
                    "icon" => "fas fa-cogs",  // ikon til settings
                    "link" => "promatch.settings.index"  // rettet fra settings.rating.index
                ],
               
                [ "name" => "Categories", "icon"=> "fas fa-video", "link" => "categories.index", "params" => [ "type" => "tasks" ] ]
                
            ],
        ],
    ],

    "includes" => [
        'hires',
        'hires.user',

        'user',
        'user.favorites',
        'user.ratings',
        'user.ratingsByMe',
        'user.company',

        'matches',
        'matches.user',
        'matches.user.company',
        'matches.available',
        'matches.company',
        
        'matches.user.ratings',
        'matches.category',
        'matches.categories',

        'ratings',
        'categories',
        'available',
        'images',
        'image',
        'address',
        'contacts',
        'contact',
        'settings',
        'addresses',
        "applicant.user.ratings"
    ],

    'defaults' => [
        'lat-column' => 'latitude',
        'long-column' => 'longitude',
        'units' => 'miles', // miles, kilometers or meters
    ],

    'models' => [],

    'whitelisted-distance-from-field-names' => [
        'distance'
    ],


    "settings" => [
        "task_create_notify_admin_email" => null
    ]


];
