// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';
// import vue from '@vitejs/plugin-vue';

// export default defineConfig({
//   plugins: [
//     laravel([
//       'resources/css/app.css', // Add your CSS entry point here
//       // Other asset paths as needed
//     ]),
//     vue(),
//   ],
//   css: {
//     postcss: {
//         plugins: {
//           tailwindcss: { config: './tailwind.config.js' }, // Adjust path if needed
//           autoprefixer: {},
//         },
//       },
//   },
// });



// vite.config.js
// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';
// import vue from '@vitejs/plugin-vue'

// export default defineConfig({
//     plugins: [
//         vue(),
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
// });


import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';
import tailwindcss from 'tailwindcss';

export default defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true
    })
  ],
  css: {
    postcss: {
      plugins: [
        tailwindcss(),
        autoprefixer(),
        // Add other PostCSS plugins here
      ],
    },
  },
//   server: {
//     hmr: {
//       host: 'localhost'
//     },
//     watch: {
//       usePolling: true
//     }
//   }
})
