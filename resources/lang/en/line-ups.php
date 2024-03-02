<?php

return [
    'title' => 'Lineups',
    'go' => 'Go',
    'years' => [
        'title' => 'Year',
        'title_plural' => 'Years',
        'active_year' => 'Active year',
        'fields' => [
            'id' => 'Id',
            'id_helper' => '',
            'name' => 'Name',
            'name_helper' => ''
        ]
    ],
    'teams' => [
        'title' => 'Team',
        'title_plural' => 'Teams',
        'fields' => [
            'id' => 'Id',
            'id_helper' => '',
            'name' => 'Name',
            'name_helper' => '',
            'competition' => 'Competition',
            'competition_helper' => '',
        ]
    ],
    'competitions' => [
        'title' => 'Competition',
        'title_plural' => 'Competitions',
        'per_team' => 'Per team',
        'per_year' => 'Per year',
        'select_team' => 'Select team',
        'select_year' => 'Select year',
        'add_competition' => 'Add competition',
        'add_year' => 'Add year',
        'in' => 'in',
        'fields' => [
            'id' => 'Id',
            'id_helper' => '',
            'name' => 'Name',
            'name_helper' => '',
            'couples_number' => 'Couples number',
            'couples_number_helper' => '',
            'years' => 'Years',
            'years_helper' => '',
            'year' => 'Year',
            'year_helper' => ''
        ]
    ],
    'players' => [
        'title' => 'Player',
        'title_plural' => 'Players',
        'fields' => [
            'id' => 'Id',
            'id_helper' => '',
            'name' => 'Name',
            'name_helper' => '',
            'email' => 'Email',
            'email_helper' => '',
            'position' => 'Position',
            'position_helper' => '',
            'ranking' => 'Ranking',
            'ranking_helper' => '',
            'competition' => 'Team/Competition',
            'competition_helper' => '',
            'competitions' => 'Teams/Competitions',
            'competitions_helper' => '',
            'participations' => 'Participation',
            'participations_helper' => ''
        ]
    ],
    'dashboard' => [
        'title' => 'Dashboard',
        'title_plural' => 'Dashboards',
        'active_year' => 'Active year',
        'set_active_year' => 'Set active year'
    ],
    'calendar' => [
        'title' => 'Calendar',
        'title_plural' => 'Calendars'
    ],
    'rounds' => [
        'title' => 'Round',
        'title_plural' => 'Rounds',
        'add_round' => 'Add round',
        'no_competition_selected' => 'No competition selected',
        'select_competition' => 'Select competition',
        'select_team' => 'Select team',
        'select_round' => 'Select round',
        'fields' => [
            'id' => 'Id',
            'id_helper' => '',
            'match_day' => 'Match day',
            'match_day_helper' => '',
            'competition_name' => 'Competition name',
            'competition_name_helper' => '',
            'round_number' => 'Round number',
            'round_number_helper' => ''
    ]
    ],
    'administration' => [
        'title' => 'Administration',
        'title_plural' => 'Administration'
    ],
    'configuration' => [
        'title' => 'Configuration',
        'title_plural' => 'Configurations',
        'fields' => [
            'id' => 'Id',
            'id_helper' => '',
            'name' => 'Name',
            'name_helper' => '',
            'value' => 'Value',
            'value_helper' => ''
        ]
    ]
];
