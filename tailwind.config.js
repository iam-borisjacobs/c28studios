/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/View/Components/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                mono: ['"JetBrains Mono"', 'monospace'],
            },
            colors: {
                obsidian: {
                    950: '#040705',
                    900: '#050806',
                    850: '#070c09',
                    800: '#0A100D',
                    700: '#101f16',
                },
                neon: {
                    400: '#4ade80',
                    500: '#10B981',
                    accent: '#00E676',
                    600: '#16a34a',
                },
            },
        },
    },
    plugins: [],
};
