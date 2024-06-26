<?php

return [
    'abilities' => [
        'aseguradoras' => [
            'index' => 'view-aseguradoras',
            'show' => 'view-aseguradoras',
            'store' => 'create-aseguradoras',
            'update' => 'update-aseguradoras',
            'destroy' => 'delete-aseguradoras',
        ],
        'clientes' => [
            'index' => 'view-clientes',
            'show' => 'view-clientes',
            'store' => 'create-clientes',
            'update' => 'update-clientes',
            'destroy' => 'delete-clientes',
        ],
        'polizas' => [
            'index' => 'view-polizas',
            'show' => 'view-polizas',
            'store' => 'create-polizas',
            'update' => 'update-polizas',
            'destroy' => 'delete-polizas',
        ],
        'pagos' => [
            'index' => 'view-pagos',
            'show' => 'view-pagos',
            'store' => 'create-pagos',
            'update' => 'update-pagos',
            'destroy' => 'delete-pagos',
        ],
        'usuarios' => [
            'index' => 'view-usuarios',
            'show' => 'view-usuarios',
            'store' => 'create-usuarios',
            'update' => 'update-usuarios',
            'destroy' => 'delete-usuarios',
        ],
        'dashboard' => [
            'index' => 'view-dashboard',
        ],
        'contabilidad' => [
            'index' => 'view-contabilidad',
        ],
        'reportes' => [
            'index' => 'view-reportes',
        ],
        'polizas-vencimiento' => [
            'index' => 'view-polizas-vencimiento',
        ],
        'archivos' => [
            'destroy' => 'delete-archivos',
        ],
        'roles' => [
            'index' => 'view-roles',
        ],
    ],
    'estadosComunes' => [
        'Pendiente' => 'Pendiente',
    ],
    'polizaEstados' => [
        'Vigente' => 'Vigente',
        'Vencida' => 'Vencida',
        'Expirada' => 'Expirada',
        'Cancelada' => 'Cancelada',
    ],
    'pagoEstados' => [
        'Vencido' => 'Vencido',
        'Pagado' => 'Pagado',
    ]
];
