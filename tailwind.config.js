/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [ 
    "./profil.php",   
    "./connection.php",   
    "./index.php",  
    "./inscription.php",   
    "./about.php",   

  ],
  theme: {
    extend: {
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

