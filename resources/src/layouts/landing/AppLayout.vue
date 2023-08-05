<template>
  <div class="surface-0 flex justify-content-center h-screen">
    <div id="home" class="landing-wrapper overflow-hidden">
      <AppTopbar />
      <div class="grid justify-content-center">
        <router-view v-slot="{ Component }">
          <transition name="scale" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </div>
      <AppFooter />
      <div class="layout-mask"></div>
    </div>
  </div>
</template>

<script setup>
import AppTopbar from '@src/layouts/landing/AppTopbar.vue';
import AppFooter from '@src/layouts/landing/AppFooter.vue';
</script>

<style lang="scss" scoped>
.scale-enter-active,
.scale-leave-active {
  transition: all 0.18s ease;
}

.scale-enter-from,
.scale-leave-to {
  opacity: 0;
  transform: scale(0.9);
}
</style>
