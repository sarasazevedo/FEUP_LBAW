/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./public/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#405865',
        secondary: '#2f5772',
        tertiary: '#fffaee',
        fourth: '#1d3647',
        stars: '#f68538'
      },
    },
  },
  plugins: [],
}