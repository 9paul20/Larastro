<template>
  <div
    class="py-4 px-4 mx-0 md:mx-6 lg:mx-0 lg:px-8 flex align-items-center justify-content-between relative lg:static mb-3 shadow-1"
  >
    <a class="flex align-items-center" href="#"
      ><span class="text-900 font-bold text-2xl line-height-3 mr-8"
        >Landing PrimeVue</span
      >
    </a>
    <a
      class="cursor-pointer block lg:hidden text-700 p-ripple"
      v-ripple
      v-styleclass="{
        selector: '@next',
        enterClass: 'hidden',
        leaveToClass: 'hidden',
        hideOnOutsideClick: true,
      }"
    >
      <i class="pi pi-bars text-4xl"></i>
    </a>
    <div
      class="align-items-center surface-0 flex-grow-1 justify-content-between hidden lg:flex absolute lg:static w-screen left-0 px-6 lg:px-0 z-2"
      style="top: 120px"
    >
      <ul
        class="list-none p-0 m-0 flex lg:align-items-center select-none flex-column lg:flex-row cursor-pointer"
      >
        <router-link to="/">
          <li>
            <a
              class="flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 p-ripple text-xl"
              :class="{ 'font-semibold': isActive('/') }"
              v-ripple
            >
              <span>Home</span>
            </a>
          </li>
        </router-link>
        <router-link to="/about">
          <li>
            <a
              class="flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 p-ripple text-xl"
              :class="{ 'font-semibold': isActive('/about') }"
              v-ripple
            >
              <span>About</span>
            </a>
          </li>
        </router-link>
        <router-link to="/developer">
          <li>
            <a
              class="flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 p-ripple text-xl"
              :class="{ 'font-semibold': isActive('/developer') }"
              v-ripple
            >
              <span>Developer</span>
            </a>
          </li>
        </router-link>
        <router-link to="/moreInfo">
          <li>
            <a
              class="flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 p-ripple text-xl"
              :class="{ 'font-semibold': isActive('/moreInfo') }"
              v-ripple
            >
              <span>More Info</span>
            </a>
          </li>
        </router-link>
        <!-- <li>
          <a
            @click="smoothScroll('#pricing')"
            class="flex m-0 md:ml-5 px-0 py-3 text-900 font-medium line-height-3 p-ripple"
            v-ripple
          >
            <span>Pricing</span>
          </a>
        </li> -->
      </ul>
      <div
        class="flex justify-content-between lg:block border-top-1 lg:border-top-none surface-border py-3 lg:py-0 mt-3 lg:mt-0"
      >
        <router-link to="/login">
          <Button
            label="Login"
            class="p-button-text p-button-rounded border-none font-light line-height-2 text-blue-500 text-xl font-semibold"
          ></Button>
        </router-link>
        <router-link to="/register">
          <Button
            label="Register"
            class="p-button-rounded border-none ml-5 font-light text-white line-height-2 bg-blue-500 text-xl font-semibold"
          ></Button>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router';
//
const isActive = route => {
  return useRoute().path === route;
};
// //
// const smoothScroll = id => {
//   document.querySelector(id).scrollIntoView({
//     behavior: 'smooth',
//   });
// };
</script>

<style lang="scss" scoped></style>
