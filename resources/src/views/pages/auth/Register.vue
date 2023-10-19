<template>
  <div class="flex align-items-center justify-content-center min-h-screen min-w-screen">
    <div class="flex flex-column align-items-center justify-content-center">
      <!-- <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" /> -->
      <div
        style="
          border-radius: 56px;
          padding: 0.3rem;
          background: linear-gradient(
            180deg,
            var(--primary-color) 10%,
            rgba(33, 150, 243, 0) 30%
          );
        "
      >
        <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
          <div class="text-center mb-5">
            <!-- <img
              src="/demo/images/login/avatar.png"
              alt="Image"
              height="50"
              class="mb-3"
            /> -->
            <div class="text-900 text-3xl font-medium mb-3">Welcome!</div>
            <span class="text-600 font-medium">Register to continue</span>
          </div>

          <div>
            <label for="email1" class="block text-900 text-xl font-medium mb-2"
              >Email</label
            >
            <InputText
              id="email1"
              type="text"
              placeholder="Email address"
              class="w-full md:w-30rem mb-5"
              style="padding: 1rem"
              v-model="email"
            />

            <label for="password1" class="block text-900 font-medium text-xl mb-2"
              >Password</label
            >
            <Password
              id="password1"
              v-model="password"
              placeholder="Password"
              :toggleMask="true"
              class="w-full mb-5"
              inputClass="w-full p-3"
              promptLabel="Password Stronger"
              weakLabel="Too simple"
              mediumLabel="Average complexity"
              strongLabel="Complex password"
            />

            <label for="repeatPassword1" class="block text-900 font-medium text-xl mb-2"
              >Repeat Password</label
            >
            <Password
              id="repeatPassword1"
              v-model="repeatPassword"
              placeholder="Repeat Password"
              :toggleMask="true"
              class="w-full mb-5"
              inputClass="w-full p-3"
              :feedback="false"
            />

            <div class="flex align-items-center justify-content-between mb-5 gap-5">
              <div class="flex align-items-center">
                <Checkbox
                  v-model="checked"
                  id="rememberme1"
                  binary
                  class="mr-2"
                ></Checkbox>
                <label for="rememberme1"
                  >I Accept the
                  <span
                    class="font-semibold text-primary cursor-pointer"
                    @click.prevent=""
                    >terms and conditions</span
                  >
                  of Larastro.</label
                >
              </div>
            </div>
            <router-link to="/dashboard">
              <Button label="Register" class="w-full p-3 text-xl" />
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// import { useLayout } from '@src/layouts/composables/layout';
import { ref, computed } from "vue";

// const { layoutConfig } = useLayout();
const email = ref("");
const password = ref("");
const repeatPassword = ref("");
const checked = ref(false);
// const logoUrl = computed(() => {
//     return `layout/images/${layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'}.svg`;
// });
</script>

<style scoped>
.pi-eye {
  transform: scale(1.6);
  margin-right: 1rem;
}

.pi-eye-slash {
  transform: scale(1.6);
  margin-right: 1rem;
}
</style>
