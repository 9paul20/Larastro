<template>
  <div class="surface-0 flex justify-content-center min-h-screen">
    <div id="home" class="landing-wrapper overflow-hidden">
      <AppTopbar />
      <div class="grid justify-content-center">
        <router-view v-slot="{ Component }">
          <transition name="scale" mode="out-in" appear>
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
  transition: all 0.2s ease;
}

.scale-enter-from,
.scale-leave-to {
  opacity: 0.05;
  transform: translateY(20px);
}
//
.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(100px);
  opacity: 0;
}
</style>
