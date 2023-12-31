module.exports = {
    mode: 'jit',
    presets: [
            require('./vendor/wireui/wireui/tailwind.config.js')
    ],
    content: [
        "./node_modules/flowbite/**/*.js",
        './resources/views/**/*.blade.php',
        "./storage/framework/views/*.php",
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php',
        './vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php'
    ],
    theme: {
        extend: {
            screens: {
                'sm': {'max': '639px'},
                'md': {'max': '767px'},
                'lg': {'max': '1023px'},
                'xl': {'max': '1279px'},
            },
            fontFamily: {
                'sans': ['Ubuntu', 'Sans-serif']
            },
            extend: {
                spacing: {
                    '72': '18rem',
                    '84': '21rem',
                    '96': '24rem',
                },
            },
        }
  },
  variants: [
    'responsive',
    'group-hover',
    'focus-within',
    'first',
    'last',
    'odd',
    'even',
    'hover',
    'focus',
    'active',
    'visited',
    'disabled'
  ],
  plugins: [
      require('@tailwindcss/forms')(),
      require('@tailwindcss/typography')(),
      require('@tailwindcss/aspect-ratio'),
      require('flowbite/plugin'),
      require('tailwindcss'),
      require('autoprefixer'),
  ]
}
