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
                    950: '#181818',
                    900: '#181818',
                    850: '#1d1d1d',
                    800: '#222222',
                    700: '#2a2a2a',
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
