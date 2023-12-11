<template>
  <div class="layout-topbar">
    <button
      class="p-link layout-menu-button layout-topbar-button"
      @click="onMenuToggle()"
      v-if="layoutState.showButtonHover.value"
    >
      <i class="pi pi-bars"></i>
    </button>

    <a class="layout-topbar-logo"><span>Dashboard PrimeVue</span></a>

    <button
      class="p-link layout-topbar-menu-button layout-topbar-button"
      @click="onTopBarMenuButton()"
    >
      <i class="pi pi-ellipsis-v"></i>
    </button>

    <div class="layout-topbar-menu" :class="topbarMenuClasses">
      <button class="p-link layout-topbar-button" @click="onTopBarMenuButton()">
        <i class="pi pi-calendar"></i>
        <span>Calendar</span>
      </button>
      <div class="flex flex-wrap">
        <button class="p-link layout-topbar-button" @click="toggle">
          <i class="pi pi-user"></i>
          <span>Profile</span>
        </button>
        <Menu ref="menu" :model="items" :popup="true">
          <template #item="{ item, props }">
            <router-link
              v-if="item.route"
              v-slot="{ href, navigate }"
              :to="item.route"
              custom
            >
              <a v-ripple :href="href" v-bind="props.action" @click="navigate">
                <span :class="item.icon" />
                <span class="ml-2">{{ item.label }}</span>
              </a>
            </router-link>
            <a
              v-else
              v-ripple
              :href="item.url"
              :target="item.target"
              v-bind="props.action"
            >
              <span :class="item.icon" />
              <span class="ml-2">{{ item.label }}</span>
            </a>
          </template>
        </Menu>
      </div>
      <button class="p-link layout-topbar-button">
        <i class="pi pi-cog"></i>
        <span>Settings</span>
      </button>
      <button class="p-link layout-topbar-menu-button layout-topbar-button">
        <i class="pi pi-ellipsis-v"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useLayout } from "@src/layouts/composables/layout";
// import { useRouter } from 'vue-router';
//
const { onMenuToggle, layoutState, changeThemeSettings, layoutConfig } = useLayout();
//
const outsideClickListener = ref(null);
const topbarMenuActive = ref(false);
const darkTheme = ref(layoutConfig.darkTheme);
// const router = useRouter();
//
onMounted(() => {
  bindOutsideClickListener();
});
//
onBeforeUnmount(() => {
  unbindOutsideClickListener();
});
//
const onTopBarMenuButton = () => {
  topbarMenuActive.value = !topbarMenuActive.value;
};
// const onSettingsClick = () => {
//   topbarMenuActive.value = false;
//   router.push("/documentation");
// };
const topbarMenuClasses = computed(() => {
  return {
    "layout-topbar-menu-mobile-active": topbarMenuActive.value,
  };
});
//
const bindOutsideClickListener = () => {
  if (!outsideClickListener.value) {
    outsideClickListener.value = (event) => {
      if (isOutsideClicked(event)) {
        topbarMenuActive.value = false;
      }
    };
    document.addEventListener("click", outsideClickListener.value);
  }
};
const unbindOutsideClickListener = () => {
  if (outsideClickListener.value) {
    document.removeEventListener("click", outsideClickListener);
    outsideClickListener.value = null;
  }
};
const isOutsideClicked = (event) => {
  if (!topbarMenuActive.value) return;

  const sidebarEl = document.querySelector(".layout-topbar-menu");
  const topbarEl = document.querySelector(".layout-topbar-menu-button");

  return !(
    sidebarEl.isSameNode(event.target) ||
    sidebarEl.contains(event.target) ||
    topbarEl.isSameNode(event.target) ||
    topbarEl.contains(event.target)
  );
};
const menu = ref();
const items = ref([
  { label: "Profile", icon: "pi pi-fw pi-user" },
  { label: "Settings", icon: "pi pi-fw pi-cog" },
  { separator: true },
  {
    label: "Log Out",
    icon: "pi pi-sign-out",
    route: "/",
  },
  {
    label: "Change Theme",
    icon: "pi pi-sun",
    command: () => {
      if (darkTheme.value) {
        onChangeTheme("lara-light-indigo", "light");
      } else {
        onChangeTheme("lara-dark-indigo", "dark");
      }
    },
  },
]);
const onChangeTheme = (theme, mode) => {
  const elementId = "theme-css";
  const linkElement = document.getElementById(elementId);
  const cloneLinkElement = linkElement.cloneNode(true);
  const newThemeUrl = linkElement
    .getAttribute("href")
    .replace(layoutConfig.theme.value, theme);
  cloneLinkElement.setAttribute("id", elementId + "-clone");
  cloneLinkElement.setAttribute("href", newThemeUrl);
  cloneLinkElement.addEventListener("load", () => {
    linkElement.remove();
    cloneLinkElement.setAttribute("id", elementId);
    changeThemeSettings(theme, mode === "dark");
  });
  linkElement.parentNode.insertBefore(cloneLinkElement, linkElement.nextSibling);
  darkTheme.value = mode === "dark";
};
const toggle = (event) => {
  onTopBarMenuButton();
  menu.value.toggle(event);
};
//
</script>

<style lang="scss" scoped></style>
