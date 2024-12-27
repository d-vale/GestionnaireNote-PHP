/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php","./*.html","./*.js"
  ],
  theme: {
    extend: {
      backgroundImage: {
        'hero-pattern': "url('/assets/space.jpeg')",
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

