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
        <Menu ref="menu" :model="items" :popup="true" />
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
const { onMenuToggle, layoutState } = useLayout();
//
const outsideClickListener = ref(null);
const topbarMenuActive = ref(false);
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
  { label: "Log Out", icon: "pi pi-sign-out", to: "/" },
]);

const toggle = (event) => {
  onTopBarMenuButton();
  menu.value.toggle(event);
};
//
// console.log(layoutState.showButtonHover.value);
</script>

<style lang="scss" scoped></style>
