<?php

return [
    'brand' => [
        'name' => 'BioMed Nexus',
        'tagline' => 'Control hospitalario',
        'logo' => [
            'path' => 'branding/logo-empresa.svg',
            'width' => 828,
            'height' => 319,
            'alt' => 'Logo oficial de la empresa',
        ],
    ],

    'navigation' => [
        [
            'label' => 'Monitoreo',
            'description' => 'Vista ejecutiva y alertas operativas.',
            'icon' => 'overview',
            'items' => [
                [
                    'label' => 'Panel general',
                    'description' => 'Indicadores clinicos, alertas y disponibilidad.',
                    'route' => 'admin.dashboard',
                ],
                [
                    'label' => 'Alertas clinicas',
                    'description' => 'Eventos priorizados por criticidad y servicio.',
                ],
                [
                    'label' => 'Cobertura por area',
                    'description' => 'Seguimiento para UCI, cirugia e imagenologia.',
                ],
            ],
        ],
        [
            'label' => 'Inventario biomedico',
            'description' => 'Activos, productos y trazabilidad.',
            'icon' => 'devices',
            'items' => [
                [
                    'label' => 'Productos biomedicos',
                    'description' => 'Catalogo, descripcion y especificaciones tecnicas.',
                    'route' => 'admin.products.index',
                    'permission' => 'ver-productos-biomedicos',
                ],
                [
                    'label' => 'Categorias',
                    'description' => 'Clasificacion funcional para el catalogo biomedico.',
                    'route' => 'admin.product-categories.index',
                    'permission' => 'ver-categorias-productos',
                ],
                [
                    'label' => 'Marcas',
                    'description' => 'Catalogo de marcas con logo para filtros y presentacion visual.',
                    'route' => 'admin.product-brands.index',
                    'permission' => 'ver-marcas-productos',
                ],
                [
                    'label' => 'Parametros filtrables',
                    'description' => 'Catalogo de parametros normalizados y unidades reutilizables.',
                    'route' => 'admin.product-parameters.index',
                    'permission' => 'ver-parametros-productos',
                ],
            ],
        ],
        [
            'label' => 'Mantenimiento',
            'description' => 'Preventivos, correctivos y calibracion.',
            'icon' => 'maintenance',
            'items' => [
                [
                    'label' => 'Preventivo',
                    'description' => 'Calendario de intervenciones y SLA tecnicos.',
                ],
                [
                    'label' => 'Correctivo',
                    'description' => 'Incidencias, tiempos de respuesta y cierre.',
                ],
                [
                    'label' => 'Calibracion',
                    'description' => 'Control metrologico y vencimientos proximos.',
                ],
            ],
        ],
        [
            'label' => 'Cumplimiento',
            'description' => 'Documentacion, auditorias y seguridad.',
            'icon' => 'shield',
            'items' => [
                [
                    'label' => 'Hojas de vida',
                    'description' => 'Historial tecnico y adjuntos por activo.',
                ],
                [
                    'label' => 'Auditorias',
                    'description' => 'Seguimiento normativo y hallazgos abiertos.',
                ],
                [
                    'label' => 'Tecnovigilancia',
                    'description' => 'Eventos adversos y acciones preventivas.',
                ],
            ],
        ],
        [
            'label' => 'Usuarios',
            'description' => 'Acceso, perfiles y autorizaciones.',
            'icon' => 'users',
            'items' => [
                [
                    'label' => 'Usuarios',
                    'description' => 'Registro, rol asignado y estado operativo.',
                    'route' => 'admin.users.index',
                    'permission' => 'ver-usuarios',
                ],
                [
                    'label' => 'Roles',
                    'description' => 'Perfiles funcionales con permisos agrupados.',
                    'route' => 'admin.roles.index',
                    'permission' => 'ver-roles',
                ],
                [
                    'label' => 'Permisos',
                    'description' => 'Acciones disponibles para cada perfil del sistema.',
                    'route' => 'admin.permissions.index',
                    'permission' => 'ver-permisos',
                ],
            ],
        ],
        [
            'label' => 'Configuracion',
            'description' => 'Catalogos e integraciones del sistema.',
            'icon' => 'settings',
            'items' => [
                [
                    'label' => 'Catalogos',
                    'description' => 'Marcas, modelos, riesgos y criticidad.',
                ],
                [
                    'label' => 'Parametros',
                    'description' => 'Ajustes generales para sedes, areas y servicios.',
                ],
                [
                    'label' => 'Integraciones',
                    'description' => 'Conexiones externas y sincronizacion con otros sistemas.',
                ],
            ],
        ],
    ],
];
