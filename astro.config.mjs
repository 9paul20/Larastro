import { defineConfig } from 'astro/config';

import vue from "@astrojs/vue";

// https://astro.build/config
export default defineConfig({
  integrations: [vue({
    appEntrypoint: "/src/app.ts",
    reactivityTransform: true,
  })]
});