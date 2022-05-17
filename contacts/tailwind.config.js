module.exports = {
  corePlugins: {
    preflight: false,
  },
  purge: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './vendor/laravel/jetstream/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      keyframes: {
        'fade-in-down': {
            '0%': {
                opacity: '0',
                transform: 'translateY(-10px)'
            },
            '100%': {
                opacity: '1',
                transform: 'translateY(0)'
            },
        },
      },
      animation: {
          'fade-in-down': 'fade-in-down 0.5s ease-out',
      }
    },
  },
  variants: {
    extend: {
      animation: ['hover', 'focus'],
      
    },
  },
  plugins: [],
  prefix: 'tw-',
}
