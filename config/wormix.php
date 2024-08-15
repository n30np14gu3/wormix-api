<?php
return [
    'starter' => [
        'money' => 450,
        'real_money' => 3,
        'missions' => 10,
        'race' => 0
    ],
    'game' => [
        'missions' => [
            'delay' => 120,
            'max' => 5,
            'awards' => [
                'loose' => [
                    'money' => 5,
                    'experience' => 3
                ],
                'draw' => [
                    'money' => 5,
                    'experience' => 3
                ],

                'low' => [
                    'money' => 30,
                    'experience' => 8
                ],
                'medium' => [
                    'money' => 35,
                    'experience' => 10
                ],
                'high' => [
                    'money' => 40,
                    'experience' => 12
                ],


                'buy' => [
                    'money' => 100,
                    'real_money' => 1,
                ]
            ],
        ],
        'buy' => [
            'reaction' => 1,
            'boss_mission' => 10,
            'reset_stats' => [
                'money' => 10,
                'real_money' => 10,
            ],
            'teammate' => [
                'money' => 10,
                'real_money' => 10,
            ],
            'full_reset' => 10
        ],
        'search_keys_per_day' => 10,
        'next_level_award' => [
            'money' => 150,
            'real_money' => 0,
        ]
    ]
];