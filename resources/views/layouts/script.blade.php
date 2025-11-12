<!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('js/adminlte.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!--begin::Theme Script-->
    <script>
      const THEME_KEY = 'lte-theme';

      function getStoredTheme() {
        return localStorage.getItem(THEME_KEY);
      }

      function setStoredTheme(theme) {
        localStorage.setItem(THEME_KEY, theme);
      }

      function getPreferredTheme() {
        const storedTheme = getStoredTheme();
        if (storedTheme) {
          return storedTheme;
        }

        // Fallback to system preference
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      }

      function setTheme(theme) {
        const html = document.documentElement;
        if (theme === 'dark') {
          html.setAttribute('data-bs-theme', 'dark');
        } else {
          html.setAttribute('data-bs-theme', 'light');
        }

        // Toggle icons
        const lightIcon = document.querySelector('i[data-lte-icon="light"]');
        const darkIcon = document.querySelector('i[data-lte-icon="dark"]');
        if (lightIcon && darkIcon) {
          lightIcon.style.display = theme === 'light' ? 'inline-block' : 'none';
          darkIcon.style.display = theme === 'dark' ? 'inline-block' : 'none';
        }
      }

      // Set theme on initial load
      setTheme(getPreferredTheme());

      window.addEventListener('DOMContentLoaded', () => {
        const themeToggler = document.querySelector('[data-lte-toggle="theme"]');

        if (themeToggler) {
          themeToggler.addEventListener('click', (event) => {
            event.preventDefault();
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setStoredTheme(newTheme);
            setTheme(newTheme);
          });
        }
      });
    </script>
    <!--end::Theme Script-->
    <!--end::Script-->