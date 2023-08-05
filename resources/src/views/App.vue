<template>
  <div>
    <div>
      <!-- <Toast />
      <Button label="Show" @click="show()" /> -->
      <router-view v-slot="{ Component }">
        <transition name="scale" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </div>
    <!-- <rippleComponent /> -->
  </div>
</template>

<script setup>
import { usePrimeVue } from 'primevue/config';
import { useToast } from 'primevue/usetoast';
import rippleComponent from '@src/components/ripple.vue';
//
const $primevue = usePrimeVue();
const toast = useToast();
const show = () => {
  toast.add({
    severity: 'info',
    summary: 'Info',
    detail: 'Message Content',
    life: 3000,
  });
};
//
defineExpose({
  $primevue,
});
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
