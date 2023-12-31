import { toRefs, reactive, computed } from 'vue';
// import '@node_modules/primevue/resources/themes/lara-light-indigo/theme.css';
// import '@node_modules/primevue/resources/themes/lara-dark-indigo/theme.css';
// import '@node_modules/primeflex/themes/primeone-light.scss';
// import '@node_modules/primeflex/themes/primeone-dark.scss';
//
const layoutConfig = reactive({
    ripple: false,
    darkTheme: false,
    inputStyle: 'outlined',
    menuMode: 'static',
    theme: 'lara-light-indigo',
    scale: 14,
    activeMenuItem: null
});
//
const layoutState = reactive({
    staticMenuDesktopInactive: true,
    overlayMenuActive: false,
    staticMenuMobileActive: false,
    profileSidebarVisible: false,
    configSidebarVisible: false,
    menuHoverActive: false,
    showButtonHover: true,
});
//
export function useLayout() {
    const changeThemeSettings = (theme: string, darkTheme: boolean) => {
        layoutConfig.darkTheme = darkTheme;
        layoutConfig.theme = theme;
    };

    const setScale = (scale: number) => {
        layoutConfig.scale = scale;
    };

    const setActiveMenuItem = (item: any) => {
        layoutConfig.activeMenuItem = item.value || item;
    };

    const onMenuToggle = () => {
        if (layoutConfig.menuMode === 'overlay') {
            layoutState.overlayMenuActive = !layoutState.overlayMenuActive;
        }

        if (window.innerWidth > 991) {
            layoutState.staticMenuDesktopInactive = !layoutState.staticMenuDesktopInactive;
        } else {
            layoutState.staticMenuMobileActive = !layoutState.staticMenuMobileActive;
        }
    };

    const isSidebarActive = computed(() => layoutState.overlayMenuActive || layoutState.staticMenuMobileActive);

    const isDarkTheme = computed(() => layoutConfig.darkTheme);

    return { layoutConfig: toRefs(layoutConfig), layoutState: toRefs(layoutState), changeThemeSettings, setScale, onMenuToggle, isSidebarActive, isDarkTheme, setActiveMenuItem };
}
