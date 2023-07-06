<template>
  <div>
    <h1>HERE is VueJS.</h1>
    <p>El valor del contador es: {{ cont }}</p>
    <PrimeButton label="+" @click="cont++" />
    <PrimeButton label="-" @click="cont--" />
  </div>
</template>

<script setup>
import { ref } from "vue";
import PrimeButton from "primevue/button";

const cont = ref(0);
</script>

<style lang="scss" scoped>
</style>