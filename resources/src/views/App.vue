<template>
  <div>
    <div class="card flex justify-content-center">
      <!-- <Toast />
      <Button label="Show" @click="show()" /> -->
      <router-view />
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

<style lang="scss" scoped></style>
