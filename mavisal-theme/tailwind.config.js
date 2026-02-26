module.exports = {
  content: ['./**/*.php', './assets/js/**/*.js'],
  theme: {
    extend: {
      colors: {
        mavisal: {
          blue: '#0f172a',
          dark: '#020617',
          orange: '#f97316',
          orangeHover: '#ea580c',
          light: '#f8fafc',
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        heading: ['Montserrat', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
