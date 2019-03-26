<?php
return [

    'adminEmail' => 'admin@example.com',
    'datepickerFormat' => 'dd.mm.yy',
    'datepickerFormatWithTime' => 'dd.mm.yy H:i:s',
    'dateFormat' => 'd.m.y',
    'resize' => [
        'AgencyForm' => [
            'LogoForm' => [
                'w'=>512,
                'h'=>256,
            ]
        ],
        'CardTypeForm' => [
          'PhotoForm' => [
              'w'=>512,
              'h'=>256,
          ]
        ],
        'PartnerForm'=>[
            'LogoForm' => [
                'w'=>512,
                'h'=>512,
                'bigSide'=>true
            ],
            'HeaderPhotoForm' => [
                'w'=>800,
                'h'=>false,
            ],
            'PhotosForm' => [
                'w'=>false,
                'h'=>400
            ]
        ],
        'AdminForm'=>[
            'PhotoForm' => [
                'w'=>100,
                'h'=>100
            ],
        ],
        'PartnerCategoryForm'=>[
            'IconForm' => [
                'w'=>100,
                'h'=>100
            ],
        ],
        'default'=>[
            'w'=>200,
            'h'=>200
        ]

    ]
];
