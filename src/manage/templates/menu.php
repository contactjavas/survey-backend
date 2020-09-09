<?php

declare(strict_types=1);

use App\Shared\Models\Survey;

return function () {
    $menu = [
    'menu_utama' => [
        [
            'url'     => '/manage/',
            'text'    => 'Survey',
            'icon'    => 'fas fa-home',
            'submenu' => [
                [
                    'url'  => '/manage/',
                    'text' => 'Lihat Semua',
                ],
                [
                    'url'  => '/manage/add/',
                    'text' => 'Tambah Baru',
                ],
            ]
        ],
        [
            'url'     => '/manage/respondents/',
            'text'    => 'Responden',
            'icon'    => 'fas fa-users',
            'submenu' => [
                [
                    'url'  => '/manage/respondents/',
                    'text' => 'Lihat Semua',
                ],
                [
                    'url'  => '/manage/respondents/add/',
                    'text' => 'Tambah Baru',
                ],
            ]
        ],
        [
            'url'     => '/manage/users/',
            'text'    => 'Pengguna',
            'icon'    => 'fas fa-user',
            'submenu' => [
                [
                    'url'  => '/manage/users/',
                    'text' => 'Lihat Semua',
                ],
                [
                    'url'  => '/manage/users/add/',
                    'text' => 'Tambah Baru',
                ],
                [
                    'url'  => '/manage/profile/edit/',
                    'text' => 'Profil',
                ],
                [
                    'url'  => '/logout/',
                    'text' => 'Logout',
                ],
            ]
        ],
    ],
    'lainnya' => [
        [
            'url'     => '/logout/',
            'text'    => 'Logout',
            'icon'    => 'fas fa-sign-out-alt',
            'submenu' => []
        ],
    ]
    ];

    $surveys = Survey::where('is_active', 1)->get();
    $menu['daftar_survey'] = [];

    foreach ($surveys as $survey) {
        $survey_menu = [
            'url'     => '/manage/survey/' . $survey->id . '/',
            'text'    => $survey->title,
            'icon'    => 'fas fa-pen',
            'submenu' => [
                [
                    'url'  => '/manage/survey/' . $survey->id . '/questions/',
                    'text' => 'Daftar Pertanyaan',
                ],
                [
                    'url'  => '/manage/survey/' . $survey->id . '/questions/add/',
                    'text' => 'Tambah Pertanyaan',
                ],
                [
                    'url'  => '/manage/survey/' . $survey->id . '/votes/',
                    'text' => 'Data Survey',
                ],
                [
                    'url'  => '/manage/survey/' . $survey->id . '/votes/add/',
                    'text' => 'Input Survey',
                ],
                [
                    'url'  => '/manage/survey/' . $survey->id . '/result/',
                    'text' => 'Hasil Survey',
                ]
            ]
        ];

        array_push($menu['daftar_survey'], $survey_menu);
    }

    assocArrayMoveAfter($menu, 'daftar_survey', 'lainnya');

    return $menu;
};
