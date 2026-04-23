<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Throwable;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $catalogData = $this->loadCatalogData($request);

        return view('home', [
            'companyName' => 'Equipos Biomedicos y Servicios',
            'heroSlides' => $this->heroSlides(),
            'metrics' => $catalogData['metrics'],
            'services' => $this->services(),
            'catalogProducts' => $catalogData['products'],
            'catalogBrands' => $catalogData['brands'],
            'catalogCategories' => $catalogData['categories'],
            'catalogFilters' => $catalogData['filters'],
            'maintenancePlans' => $this->maintenancePlans(),
            'qualityPillars' => $this->qualityPillars(),
        ]);
    }

    /**
     * @return array{
     *     metrics: array<int, array{value:string|int, label:string}>,
     *     products: LengthAwarePaginator,
     *     brands: array<int, array{id:int, name:string}>,
     *     categories: array<int, array{id:int, name:string}>,
     *     filters: array{brand:?int, category:?int}
     * }
     */
    protected function loadCatalogData(Request $request): array
    {
        $filters = [
            'brand' => $this->normalizeFilterId($request->query('marca')),
            'category' => $this->normalizeFilterId($request->query('categoria')),
        ];

        $fallbackPayload = [
            'metrics' => $this->fallbackMetrics(),
            'products' => $this->emptyProductPaginator($request),
            'brands' => [],
            'categories' => [],
            'filters' => $filters,
        ];

        if (
            ! Schema::hasTable('products')
            || ! Schema::hasTable('product_categories')
            || ! Schema::hasTable('product_brands')
        ) {
            return $fallbackPayload;
        }

        try {
            $activeProductQuery = Product::query()->where('is_active', true);
            $activeProductCount = (clone $activeProductQuery)->count();
            $activeCategoryCount = ProductCategory::query()
                ->where('is_active', true)
                ->whereHas('products', fn ($query) => $query->where('is_active', true))
                ->count();
            $catalogUnits = (clone $activeProductQuery)->sum('stock_actual');
            $brands = ProductBrand::query()
                ->where('is_active', true)
                ->whereHas('products', fn ($query) => $query->where('is_active', true))
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (ProductBrand $brand): array => [
                    'id' => $brand->id,
                    'name' => $brand->name,
                ])
                ->values()
                ->all();

            $categories = ProductCategory::query()
                ->where('is_active', true)
                ->whereHas('products', fn ($query) => $query->where('is_active', true))
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (ProductCategory $category): array => [
                    'id' => $category->id,
                    'name' => $category->name,
                ])
                ->values()
                ->all();

            $productsQuery = Product::query()
                ->where('is_active', true)
                ->with(['category:id,name', 'productBrand:id,name']);

            if ($filters['brand'] !== null) {
                $productsQuery->where('brand_id', $filters['brand']);
            }

            if ($filters['category'] !== null) {
                $productsQuery->where('category_id', $filters['category']);
            }

            $products = $productsQuery
                ->latest('id')
                ->paginate(8)
                ->withQueryString()
                ->fragment('portafolio');

            return [
                'metrics' => [
                    [
                        'value' => $activeProductCount > 0 ? $activeProductCount : 'Venta',
                        'label' => $activeProductCount > 0 ? 'productos activos' : 'de equipos biomedicos',
                    ],
                    [
                        'value' => $activeCategoryCount > 0 ? $activeCategoryCount : 'Cobertura',
                        'label' => $activeCategoryCount > 0 ? 'categorias con productos' : 'por lineas de producto',
                    ],
                    [
                        'value' => $catalogUnits > 0 ? $catalogUnits : 'Inventario',
                        'label' => $catalogUnits > 0 ? 'unidades disponibles' : 'disponibilidad por revisar',
                    ],
                    [
                        'value' => count($brands) > 0 ? count($brands) : 'Marcas',
                        'label' => count($brands) > 0 ? 'marcas activas' : 'registradas en catalogo',
                    ],
                ],
                'products' => $products,
                'brands' => $brands,
                'categories' => $categories,
                'filters' => $filters,
            ];
        } catch (Throwable) {
            return $fallbackPayload;
        }
    }

    protected function emptyProductPaginator(Request $request): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            collect(),
            0,
            8,
            max((int) $request->query('page', 1), 1),
            [
                'path' => route('home'),
                'query' => $request->query(),
                'fragment' => 'portafolio',
            ]
        );
    }

    protected function normalizeFilterId(mixed $value): ?int
    {
        $filterId = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        return $filterId === false ? null : $filterId;
    }

    /**
     * @return array<int, array{
     *     eyebrow:string,
     *     title:string,
     *     description:string,
     *     image:string,
     *     highlights:array<int, string>,
     *     caption_title:string,
     *     caption:string
     * }>
     */
    protected function heroSlides(): array
    {
        return [
            [
                'eyebrow' => 'Venta especializada',
                'title' => 'Equipos biomedicos pensados para operacion clinica continua.',
                'description' => 'Presentamos soluciones para diagnostico, monitoreo, esterilizacion y soporte hospitalario con una experiencia comercial enfocada en rendimiento y trazabilidad.',
                'image' => asset('branding/site/hero-monitor.svg'),
                'highlights' => [
                    'Portafolio para consulta, hospitalizacion y areas criticas.',
                    'Catalogo alineado con marcas, categorias y fichas tecnicas.',
                    'Imagen comercial consistente con la identidad actual del sistema.',
                ],
                'caption_title' => 'Portafolio comercial',
                'caption' => 'Venta de equipos con presentacion clara, tecnica y visualmente coherente.',
            ],
            [
                'eyebrow' => 'Mantenimiento preventivo',
                'title' => 'Rutinas tecnicas que protegen la vida util de cada equipo.',
                'description' => 'La portada comunica inspecciones programadas, control de desgaste y continuidad operativa para que el mantenimiento preventivo tenga un espacio visible dentro del sitio.',
                'image' => asset('branding/site/hero-maintenance.svg'),
                'highlights' => [
                    'Planeacion de revisiones, limpieza tecnica y verificacion funcional.',
                    'Soporte a la gestion de inventario y estado operativo.',
                    'Mensajes enfocados en confianza para hospitales y clinicas.',
                ],
                'caption_title' => 'Cobertura preventiva',
                'caption' => 'Un enfoque visual para mantenimiento, control y seguimiento de activos biomedicos.',
            ],
            [
                'eyebrow' => 'Mantenimiento correctivo',
                'title' => 'Respuesta tecnica para recuperar disponibilidad cuando mas se necesita.',
                'description' => 'Se destaca la capacidad de intervenir fallas, atender incidentes y devolver equipos a servicio con una comunicacion comercial mas directa y orientada a continuidad.',
                'image' => asset('branding/site/hero-sterilization.svg'),
                'highlights' => [
                    'Atencion a fallas, reposicion de componentes y pruebas funcionales.',
                    'Narrativa orientada a continuidad clinica y soporte especializado.',
                    'Seccion preparada para crecer con mas vistas publicas en el futuro.',
                ],
                'caption_title' => 'Soporte correctivo',
                'caption' => 'Correctivos y reposiciones para reducir el tiempo fuera de servicio.',
            ],
        ];
    }

    /**
     * @return array<int, array{title:string, description:string, accent:string}>
     */
    protected function services(): array
    {
        return [
            [
                'title' => 'Venta de equipos biomedicos',
                'description' => 'Presentacion de lineas de producto para hospitales, clinicas, consultorios y laboratorios con un lenguaje comercial sobrio y tecnico.',
                'accent' => 'linear-gradient(135deg, rgba(6, 182, 212, 0.18), rgba(14, 165, 233, 0.08))',
            ],
            [
                'title' => 'Mantenimiento preventivo',
                'description' => 'Comunicacion enfocada en inspecciones programadas, verificaciones funcionales y continuidad operativa de activos criticos.',
                'accent' => 'linear-gradient(135deg, rgba(16, 185, 129, 0.18), rgba(132, 204, 22, 0.08))',
            ],
            [
                'title' => 'Mantenimiento correctivo',
                'description' => 'Espacio para evidenciar respuesta tecnica, diagnostico de fallas, reparacion y retorno a servicio del equipo intervenido.',
                'accent' => 'linear-gradient(135deg, rgba(15, 23, 42, 0.12), rgba(8, 145, 178, 0.08))',
            ],
            [
                'title' => 'Soporte de catalogo y trazabilidad',
                'description' => 'La portada se conecta con categorias y productos reales del sistema para reforzar el trabajo administrativo que ya tienes montado.',
                'accent' => 'linear-gradient(135deg, rgba(59, 130, 246, 0.16), rgba(16, 185, 129, 0.1))',
            ],
        ];
    }

    /**
     * @return array<int, array{title:string, description:string, steps:array<int, string>}>
     */
    protected function maintenancePlans(): array
    {
        return [
            [
                'title' => 'Plan preventivo',
                'description' => 'Pensado para mostrar rutinas de inspeccion, ajustes y seguimiento que disminuyen fallas y extienden la vida util del equipo.',
                'steps' => [
                    'Revision tecnica programada por linea o criticidad.',
                    'Verificacion funcional, limpieza y control de desgaste.',
                    'Registro de hallazgos y recomendaciones para continuidad.',
                ],
            ],
            [
                'title' => 'Plan correctivo',
                'description' => 'Dirigido a comunicar capacidad de respuesta ante fallas, recuperacion del activo y soporte tecnico orientado al retorno rapido a servicio.',
                'steps' => [
                    'Recepcion del caso y diagnostico del equipo afectado.',
                    'Intervencion tecnica con reemplazo o ajuste segun la falla.',
                    'Pruebas de funcionamiento y cierre con evidencia operativa.',
                ],
            ],
        ];
    }

    /**
     * @return array<int, array{title:string, description:string}>
     */
    protected function qualityPillars(): array
    {
        return [
            [
                'title' => 'Imagen alineada con tu logo actual',
                'description' => 'La paleta principal toma como base el azul y verde del logo para que la portada se sienta parte del mismo sistema.',
            ],
            [
                'title' => 'Header y footer listos para reutilizar',
                'description' => 'Quedaron separados como componentes para servir otras vistas publicas sin duplicar estructura.',
            ],
            [
                'title' => 'Portada preparada para crecer',
                'description' => 'La pagina ya contempla servicios, productos y mantenimiento como ejes para futuras secciones internas o landing pages.',
            ],
        ];
    }

    /**
     * @return array<int, array{value:string|int, label:string}>
     */
    protected function fallbackMetrics(): array
    {
        return [
            ['value' => 'Venta', 'label' => 'de equipos biomedicos'],
            ['value' => 'Preventivo', 'label' => 'mantenimiento planificado'],
            ['value' => 'Correctivo', 'label' => 'respuesta tecnica'],
            ['value' => 'Catalogo', 'label' => 'visual listo para crecer'],
        ];
    }

    /**
     * @return array<int, array{name:string, description:string, count:int|string}>
     */
    protected function fallbackCategories(): array
    {
        return [
            [
                'name' => 'Monitoreo y diagnostico',
                'description' => 'Equipos para lectura de variables, seguimiento clinico y apoyo al diagnostico en diferentes areas asistenciales.',
                'count' => 'Linea',
            ],
            [
                'name' => 'Esterilizacion',
                'description' => 'Soluciones para procesos de limpieza, esterilizacion y control operativo de dispositivos y material clinico.',
                'count' => 'Linea',
            ],
            [
                'name' => 'Soporte hospitalario',
                'description' => 'Activos biomedicos orientados a consulta, procedimientos, hospitalizacion y dotacion institucional.',
                'count' => 'Linea',
            ],
            [
                'name' => 'Servicio tecnico',
                'description' => 'Cobertura visual para mantenimiento preventivo, correctivo y seguimiento de estado operativo.',
                'count' => 'Servicio',
            ],
        ];
    }

    /**
     * @return array<int, array{name:string, category:string, description:string, image:string, brand:string, model:?string, stock:?int, badge:string}>
     */
    protected function fallbackProducts(): array
    {
        return [
            [
                'name' => 'Monitor multiparametro',
                'category' => 'Monitoreo',
                'description' => 'Ejemplo visual para presentar equipos de seguimiento clinico con una tarjeta de producto clara y comercial.',
                'image' => asset('branding/site/hero-monitor.svg'),
                'brand' => 'Portafolio biomedico',
                'model' => null,
                'stock' => null,
                'badge' => 'Linea destacada',
            ],
            [
                'name' => 'Autoclave clinico',
                'category' => 'Esterilizacion',
                'description' => 'Tarjeta enfocada en equipos para procesos de esterilizacion, con espacio para marca, modelo y descripcion corta.',
                'image' => asset('branding/site/hero-sterilization.svg'),
                'brand' => 'Portafolio biomedico',
                'model' => null,
                'stock' => null,
                'badge' => 'Linea destacada',
            ],
            [
                'name' => 'Servicio tecnico especializado',
                'category' => 'Mantenimiento',
                'description' => 'Bloque visual para comunicar mantenimiento preventivo y correctivo aun cuando el catalogo publico siga creciendo.',
                'image' => asset('branding/site/hero-maintenance.svg'),
                'brand' => 'Soporte tecnico',
                'model' => null,
                'stock' => null,
                'badge' => 'Servicio',
            ],
        ];
    }

    protected function illustrationForSlot(int $index): string
    {
        return match ($index % 3) {
            1 => 'branding/site/hero-maintenance.svg',
            2 => 'branding/site/hero-sterilization.svg',
            default => 'branding/site/hero-monitor.svg',
        };
    }
}
