<?php
return [
    'DOC_TYPES'=>[
        'SUBSCRIPTION'=>'SUBSCRIPTION',
        'EGAZETTE'=>'EGAZETTE',
        'ADVERT'=>'ADVERT'
    ],
    'MOBILE_NETWORKS'=>['AIRTEL','MTN'],
    'SUB_STATUSES'=>[
        "PENDING PAYMENT"=>"PENDING PAYMENT",
        "PAYMENT FAILED"=>"PAYMENT FAILED",
        "ACTIVE"=>"ACTIVE",
        "EXPIRED"=>"EXPIRED"
    ],
    'GAZETTE_STATUSES'=>[
        'PUBLISHED'=>'PUBLISHED',
        'DRAFTED'=>'DRAFTED'
    ],
    'SUB_PAY_STATES'=>[
        "PENDING"=>"PENDING",
        "FAILED"=>"FAILED",
        "COMPLETED"=>"COMPLETED"
    ],
    'SUB_TYPES'=>['ANNUAL'],
    'SUB_FEES'=>[1400000],
    'ADVERT_STATES'=>[
        'PENDING PAYMENT'=>'PENDING PAYMENT',
        'PAID'=>'PAID',
        'PAYMENT FAILED'=>'PAYMENT FAILED',
        'REGISTERED'=>'REGISTERED'
    ],
    'ADVERT_SERVICES' => [
        [
            'name' => 'The Marriage Act - (Notice of place for celebration of marriages)',
            'price' => 345000,
            'currency'=>'UGX'
        ],
        [
            'name' => 'The Companies Act - (Notice of change of company name, resolutions)',
            'price' => 300000,
            'currency' => 'UGX'
        ],
        [
            'name' => 'The Insolvency Act- Notice',
            'price' => 300000,
            'currency' => 'UGX'
        ],
        [
            'name' => 'Appointments/Replacements to Board of Governors Notice',
            'price' => 650000,
            'currency'=>'UGX'
        ],
        [
            'name' => 'The Advocates Act (Notice of Application for Certificate of Eligibility)',
            'price' => 300000,
            'currency'=>'UGX'
        ],
        [
            'name' => 'The Commissioners for Oaths (Advocates) Act Notice',
            'price' => 345000,
            'currency' => 'UGX'
        ],
        [
            'name' => 'Gazetting of Security Uniforms/Change of Security Uniforms',
            'price' => 450000,
            'currency'=>'UGX'
        ],
        [
            'name' => 'The Mining Act - Notice',
            'price' => 345000,
            'currency' => 'UGX'
        ],
        [
            'name' => 'Trademark Applications - Local Adverts',
            'price' => 100000,
            'currency' => 'UGX'
        ],
        [
            'name' => 'Alteration of a registered Trademark - Local Adverts',
            'price' => 100000,
            'currency' => 'UGX'
        ],
        [
            'name' => 'Copyright & Patents',
            'price' => 150000,
            'currency' => 'UGX'
        ],
        [
            'name' => 'Industrial Design',
            'price' => 100000,
            'currency' => 'UGX'
        ],
        [
            'name' => 'Caveats',
            'price' => 300000,
            'currency' => 'UGX'
        ],
        [
            'name' => 'Administrator General Notice',
            'price' => 150000,
            'currency' => 'UGX'
        ],
        [
            'name' => 'Deed Poll',
            'price' => 345000,
            'currency'=>'UGX'
        ]
    ],
    
    'STATUS' => [
        "PENDING" => 'PENDING',
        "ACTIVE" => 'ACTIVE',
        "BLOCK" => 'BLOCK',
        "REJECT" => 'REJECT',
        "APPROVED" => 'APPROVED',
    ],
    'GLOBAL_PERMISSIONS' => [ //permission is = permission=>label of permission
        'USERS' => [
            'create users' => 'create',
            'read users' => 'read',
            'update users' => 'update',
            'delete users' => 'delete',
            'user manage permission' => 'permission management',
        ],
        'TAGS' => [
            'create tags' => 'create',
            'read tags' => 'read',
            'update tags' => 'update',
            'delete tags' => 'delete',
        ],
        'DOCUMENTS' => [
            'create documents' => 'create',
            'read documents' => 'read',
            'update documents' => 'update',
            'delete documents' => 'delete',
            'verify documents' => 'verify',
        ]
    ],
    'TAG_LEVEL_PERMISSIONS' => [
        'read documents in tag ' => 'read',
        'create documents in tag ' => 'create',
        'update documents in tag ' => 'update',
        'delete documents in tag ' => 'delete',
        'verify documents in tag ' => 'verify',
    ],
    'DOCUMENT_LEVEL_PERMISSIONS' => [
        'read document ' => 'read',
        'update document ' => 'update',
        'delete document ' => 'delete',
        'verify document ' => 'verify',
    ]
];
