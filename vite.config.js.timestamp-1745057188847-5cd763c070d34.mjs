// vite.config.js
import { defineConfig } from "file:///Applications/MAMP/htdocs/curacel-hackings/node_modules/vite/dist/node/index.js";
import laravel from "file:///Applications/MAMP/htdocs/curacel-hackings/node_modules/laravel-vite-plugin/dist/index.mjs";
import vue from "file:///Applications/MAMP/htdocs/curacel-hackings/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import svgLoader from "file:///Applications/MAMP/htdocs/curacel-hackings/node_modules/vite-svg-loader/index.js";
var vite_config_default = defineConfig({
  build: {
    chunkSizeWarningLimit: 3e3,
    // in KB
    rollupOptions: {
      output: {
        manualChunks: {
          "vendor": [
            "vue",
            "@inertiajs/vue3",
            "chart.js",
            "flowbite",
            "@vuepic/vue-datepicker"
          ]
        }
      }
    }
  },
  plugins: [
    laravel({
      input: "resources/js/app.js",
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    svgLoader()
  ],
  optimizeDeps: {
    include: ["vue", "@inertiajs/vue3", "chart.js", "flowbite", "@vuepic/vue-datepicker"],
    exclude: []
  },
  server: {
    hmr: {
      overlay: false
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvQXBwbGljYXRpb25zL01BTVAvaHRkb2NzL2N1cmFjZWwtaGFja2luZ3NcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIi9BcHBsaWNhdGlvbnMvTUFNUC9odGRvY3MvY3VyYWNlbC1oYWNraW5ncy92aXRlLmNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vQXBwbGljYXRpb25zL01BTVAvaHRkb2NzL2N1cmFjZWwtaGFja2luZ3Mvdml0ZS5jb25maWcuanNcIjtpbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tICd2aXRlJztcbmltcG9ydCBsYXJhdmVsIGZyb20gJ2xhcmF2ZWwtdml0ZS1wbHVnaW4nO1xuaW1wb3J0IHZ1ZSBmcm9tICdAdml0ZWpzL3BsdWdpbi12dWUnO1xuaW1wb3J0IHN2Z0xvYWRlciBmcm9tICd2aXRlLXN2Zy1sb2FkZXInO1xuXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xuICAgIGJ1aWxkOiB7XG4gICAgICAgIGNodW5rU2l6ZVdhcm5pbmdMaW1pdDogMzAwMCwgLy8gaW4gS0JcbiAgICAgICAgcm9sbHVwT3B0aW9uczoge1xuICAgICAgICAgICAgb3V0cHV0OiB7XG4gICAgICAgICAgICAgICAgbWFudWFsQ2h1bmtzOiB7XG4gICAgICAgICAgICAgICAgICAgICd2ZW5kb3InOiBbXG4gICAgICAgICAgICAgICAgICAgICAgICAndnVlJyxcbiAgICAgICAgICAgICAgICAgICAgICAgICdAaW5lcnRpYWpzL3Z1ZTMnLFxuICAgICAgICAgICAgICAgICAgICAgICAgJ2NoYXJ0LmpzJyxcbiAgICAgICAgICAgICAgICAgICAgICAgICdmbG93Yml0ZScsXG4gICAgICAgICAgICAgICAgICAgICAgICAnQHZ1ZXBpYy92dWUtZGF0ZXBpY2tlcidcbiAgICAgICAgICAgICAgICAgICAgXVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH0sXG4gICAgcGx1Z2luczogW1xuICAgICAgICBsYXJhdmVsKHtcbiAgICAgICAgICAgIGlucHV0OiAncmVzb3VyY2VzL2pzL2FwcC5qcycsXG4gICAgICAgICAgICByZWZyZXNoOiB0cnVlLFxuICAgICAgICB9KSxcbiAgICAgICAgdnVlKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiB7XG4gICAgICAgICAgICAgICAgdHJhbnNmb3JtQXNzZXRVcmxzOiB7XG4gICAgICAgICAgICAgICAgICAgIGJhc2U6IG51bGwsXG4gICAgICAgICAgICAgICAgICAgIGluY2x1ZGVBYnNvbHV0ZTogZmFsc2UsXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIH0sXG4gICAgICAgIH0pLFxuICAgICAgICBzdmdMb2FkZXIoKSxcbiAgICBdLFxuICAgIG9wdGltaXplRGVwczoge1xuICAgICAgICBpbmNsdWRlOiBbJ3Z1ZScsICdAaW5lcnRpYWpzL3Z1ZTMnLCAnY2hhcnQuanMnLCAnZmxvd2JpdGUnLCAnQHZ1ZXBpYy92dWUtZGF0ZXBpY2tlciddLFxuICAgICAgICBleGNsdWRlOiBbXVxuICAgIH0sXG4gICAgc2VydmVyOiB7XG4gICAgICAgIGhtcjoge1xuICAgICAgICAgICAgb3ZlcmxheTogZmFsc2VcbiAgICAgICAgfVxuICAgIH1cbn0pO1xuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUFnVCxTQUFTLG9CQUFvQjtBQUM3VSxPQUFPLGFBQWE7QUFDcEIsT0FBTyxTQUFTO0FBQ2hCLE9BQU8sZUFBZTtBQUV0QixJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4QixPQUFPO0FBQUEsSUFDSCx1QkFBdUI7QUFBQTtBQUFBLElBQ3ZCLGVBQWU7QUFBQSxNQUNYLFFBQVE7QUFBQSxRQUNKLGNBQWM7QUFBQSxVQUNWLFVBQVU7QUFBQSxZQUNOO0FBQUEsWUFDQTtBQUFBLFlBQ0E7QUFBQSxZQUNBO0FBQUEsWUFDQTtBQUFBLFVBQ0o7QUFBQSxRQUNKO0FBQUEsTUFDSjtBQUFBLElBQ0o7QUFBQSxFQUNKO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxRQUFRO0FBQUEsTUFDSixPQUFPO0FBQUEsTUFDUCxTQUFTO0FBQUEsSUFDYixDQUFDO0FBQUEsSUFDRCxJQUFJO0FBQUEsTUFDQSxVQUFVO0FBQUEsUUFDTixvQkFBb0I7QUFBQSxVQUNoQixNQUFNO0FBQUEsVUFDTixpQkFBaUI7QUFBQSxRQUNyQjtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxJQUNELFVBQVU7QUFBQSxFQUNkO0FBQUEsRUFDQSxjQUFjO0FBQUEsSUFDVixTQUFTLENBQUMsT0FBTyxtQkFBbUIsWUFBWSxZQUFZLHdCQUF3QjtBQUFBLElBQ3BGLFNBQVMsQ0FBQztBQUFBLEVBQ2Q7QUFBQSxFQUNBLFFBQVE7QUFBQSxJQUNKLEtBQUs7QUFBQSxNQUNELFNBQVM7QUFBQSxJQUNiO0FBQUEsRUFDSjtBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
