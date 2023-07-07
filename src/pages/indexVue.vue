<template>
  <div>
    <h2>Lorem temporal de ejemplo:</h2>
    <p>
      Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas error dicta, necessitatibus quos magnam nobis reprehenderit minima possimus,
      sint quia esse quasi dolor totam ullam adipisci, quae veritatis in eveniet!
    </p>
    <div class="card flex justify-content-center">
      <Toast />
      <Button label="Show" @click="show()" />
    </div>
    <br />
    <div class="card card-container flex justify-content-center align-items-center">
      <div
        v-ripple
        class="p-ripple bg-primary flex select-none justify-content-center align-items-center shadow-2 border-round p-6 font-bold"
      >Default</div>
    </div>
    <br />
    <div class="card flex flex-column align-items-center gap-3">
      <Button
        v-styleclass="{ selector: '@next', toggleClass: 'p-disabled' }"
        label="Toggle p-disabled"
      />
      <InputText />
    </div>
  </div>
</template>

<script setup>
import { usePrimeVue } from "primevue/config";
import { useToast } from "primevue/usetoast";

const toast = useToast();
const $primevue = usePrimeVue();

const show = () => {
  toast.add({
    severity: "info",
    summary: "Info",
    detail: "Message Content",
    life: 3000
  });
};

defineExpose({
  $primevue
});
</script>

<style scoped>
</style>