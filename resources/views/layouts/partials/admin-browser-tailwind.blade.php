<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<style type="text/tailwindcss">
    @theme {
        --font-sans: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
            'Segoe UI Symbol', 'Noto Color Emoji';
        --font-display: 'Sora', var(--font-sans);

        --color-medical-50: #ecfeff;
        --color-medical-100: #cffafe;
        --color-medical-200: #a5f3fc;
        --color-medical-300: #67e8f9;
        --color-medical-400: #22d3ee;
        --color-medical-500: #06b6d4;
        --color-medical-600: #0891b2;
        --color-medical-700: #0e7490;
        --color-medical-800: #155e75;
        --color-medical-900: #164e63;
    }

    @layer base {
        :root {
            color-scheme: light;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(34, 211, 238, 0.22), transparent 26rem),
                radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.16), transparent 32rem),
                linear-gradient(180deg, #ecfeff 0%, #f8fafc 56%, #f0fdf4 100%);
            color: #0f172a;
        }
    }

    @layer components {
        .font-display {
            font-family: var(--font-display);
        }

        .app-panel {
            border: 1px solid rgba(148, 163, 184, 0.18);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, rgba(248, 250, 252, 0.86) 100%);
            box-shadow:
                0 24px 70px rgba(15, 23, 42, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(22px);
        }

        .app-panel-soft {
            border: 1px solid rgba(148, 163, 184, 0.16);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.82) 0%, rgba(240, 249, 255, 0.74) 100%);
            box-shadow:
                0 20px 60px rgba(8, 47, 73, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(18px);
        }

        .app-chip {
            border: 1px solid rgba(8, 145, 178, 0.14);
            background: rgba(236, 254, 255, 0.88);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }

        .sidebar-link {
            border: 1px solid transparent;
            background: rgba(255, 255, 255, 0.04);
            transition:
                border-color 180ms ease,
                background-color 180ms ease,
                transform 180ms ease;
        }

        .sidebar-link:hover {
            border-color: rgba(103, 232, 249, 0.18);
            background: rgba(12, 74, 110, 0.28);
            transform: translateX(4px);
        }

        .sidebar-link-active {
            border-color: rgba(165, 243, 252, 0.24);
            background: linear-gradient(135deg, rgba(8, 145, 178, 0.34), rgba(16, 185, 129, 0.16));
            box-shadow: 0 14px 30px rgba(8, 47, 73, 0.25);
        }

        .grid-mesh {
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.09) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.09) 1px, transparent 1px);
            background-size: 72px 72px;
        }

        .soft-divider {
            height: 1px;
            background: linear-gradient(90deg, rgba(34, 211, 238, 0), rgba(34, 211, 238, 0.42), rgba(34, 211, 238, 0));
        }

        .status-dot {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.48);
            animation: pulse-ring 2.3s infinite;
        }

        .float-orb {
            animation: soft-float 7s ease-in-out infinite;
        }

        .enter-fade {
            animation: enter-fade 700ms cubic-bezier(0.22, 1, 0.36, 1) both;
        }
    }

    @keyframes soft-float {
        0%,
        100% {
            transform: translate3d(0, 0, 0);
        }

        50% {
            transform: translate3d(0, -14px, 0);
        }
    }

    @keyframes pulse-ring {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.48);
        }

        70% {
            box-shadow: 0 0 0 12px rgba(16, 185, 129, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    @keyframes enter-fade {
        from {
            opacity: 0;
            transform: translateY(24px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
