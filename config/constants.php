<?php
return [
    'DOC_TYPES'=>[
        'SUBSCRIPTION'=>'SUBSCRIPTION',
        'EGAZETTE'=>'EGAZETTE'
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
