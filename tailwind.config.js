/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./resources/**/*.{html,js,blade.php}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
  corePlugins: {
    preflight: true,
  },
}

