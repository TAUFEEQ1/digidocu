<?php
return [
    'DOC_TYPES'=>[
        'LETTER'=>'LETTER',
        'LETTER_RESPONSE'=>'LETTER_RESPONSE',
        'LEAVE_REQUESTS'=>'LEAVE_REQUESTS'
    ],
    'LETTER_STATES'=>[
        'SUBMITTED'=>'SUBMITTED',
        'EXECUTED'=>'EXECUTED',
        'MANAGED'=>'MANAGED',
        'ASSIGNED'=>'ASSIGNED',
        'DISCARDED'=>'DISCARDED',
        'RESPONSE_SUBMITTED' => 'RESPONSE SUBMITTED',
        'RESPONSE_EXEC_APPROVED'=>'RESPONSE APPROVED BY EXECUTIVE SEC',
        'RESPONSE_MGR_APPROVED' => 'RESPONSE APPROVED BY MANAGER'
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
