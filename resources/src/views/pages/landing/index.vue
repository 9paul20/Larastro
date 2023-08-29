<template>
  <div class="py-1">
    <!-- <div class="text-center">
      <h1>Home!</h1>
      <router-link to="/dashboard"
        ><span class="text-right">Clik Me for go in Dashboard!</span>
      </router-link>
      <br />
      <router-link to="/dashboard/hello"
        ><span>Clik Me for go to Hello in Dashboard!</span>
      </router-link>
    </div> -->
    <div class="grid grid-nogutter surface-section text-800">
      <div
        class="col-12 md:col-6 p-6 text-center md:text-left flex align-items-center"
      >
        <section>
          <span class="block text-6xl font-bold mb-1"
            >Create the screens your</span
          >
          <div class="text-6xl text-primary font-bold mb-3">
            your visitors deserve to see
          </div>
          <p class="mt-0 mb-4 text-700 line-height-3">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            eiusmod tempor incididunt ut labore et dolore magna aliqua.
          </p>

          <Button
            label="Learn More"
            type="button"
            class="mr-3 p-button-raised"
          ></Button>
          <Button
            label="Live Demo"
            type="button"
            class="p-button-outlined"
          ></Button>
        </section>
      </div>
      <div class="col-12 md:col-6 overflow-hidden">
        <img
          src="@images/simGill.jpg"
          alt="Image"
          class="md:ml-auto block md:h-full"
          style="clip-path: polygon(8% 0, 100% 0%, 100% 100%, 0 100%)"
        />
      </div>
    </div>
    <Divider type="solid" />
    <div id="pricing" class="py-4 px-4 lg:px-8 my-2 md:my-4">
      <div class="text-center">
        <h2 class="text-900 font-normal mb-2">Matchless Pricing</h2>
        <span class="text-600 text-2xl"
          >Amet consectetur adipiscing elit...</span
        >
      </div>
      <div class="grid justify-content-between mt-8 md:mt-0">
        <div class="col-12 lg:col-4 p-0 md:p-3">
          <div
            class="p-3 flex flex-column border-200 pricing-card cursor-pointer border-2 hover:border-primary transition-duration-300 transition-all"
            style="border-radius: 10px"
          >
            <h3 class="text-900 text-center my-5">Free</h3>
            <img src="@svg/free.svg" class="w-10 h-10 mx-auto" alt="free" />
            <div class="my-5 text-center">
              <span class="text-5xl font-bold mr-2 text-900">$0</span
              ><span class="text-600">per month</span
              ><button
                class="p-button p-component block mx-auto mt-4 p-button-rounded border-none ml-3 font-light line-height-2 bg-blue-500 text-white"
                type="button"
                aria-label="Get Started"
              >
                <!----><!----><span class="p-button-label">Get Started</span
                ><!----><span
                  class="p-ink"
                  role="presentation"
                  aria-hidden="true"
                ></span>
              </button>
            </div>
            <div
              class="p-divider p-component p-divider-horizontal p-divider-solid p-divider-left w-full bg-surface-200"
              role="separator"
              aria-orientation="horizontal"
            >
              <!---->
            </div>
            <ul class="my-5 list-none p-0 flex text-900 flex-column">
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3">Responsive Layout</span>
              </li>
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3"
                  >Unlimited Push Messages</span
                >
              </li>
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3">50 Support Ticket</span>
              </li>
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3">Free Shipping</span>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-12 lg:col-4 p-0 md:p-3 mt-4 md:mt-0">
          <div
            class="p-3 flex flex-column border-200 pricing-card cursor-pointer border-2 hover:border-primary transition-duration-300 transition-all"
            style="border-radius: 10px"
          >
            <h3 class="text-900 text-center my-5">Startup</h3>
            <img
              src="@svg/startup.svg"
              class="w-10 h-10 mx-auto"
              alt="startup"
            />
            <div class="my-5 text-center">
              <span class="text-5xl font-bold mr-2 text-900">$1</span
              ><span class="text-600">per month</span
              ><button
                class="p-button p-component block mx-auto mt-4 p-button-rounded border-none ml-3 font-light line-height-2 bg-blue-500 text-white"
                type="button"
                aria-label="Try Free"
              >
                <!----><!----><span class="p-button-label">Try Free</span
                ><!----><span
                  class="p-ink"
                  role="presentation"
                  aria-hidden="true"
                ></span>
              </button>
            </div>
            <div
              class="p-divider p-component p-divider-horizontal p-divider-solid p-divider-left w-full bg-surface-200"
              role="separator"
              aria-orientation="horizontal"
            >
              <!---->
            </div>
            <ul class="my-5 list-none p-0 flex text-900 flex-column">
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3">Responsive Layout</span>
              </li>
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3"
                  >Unlimited Push Messages</span
                >
              </li>
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3">50 Support Ticket</span>
              </li>
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3">Free Shipping</span>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-12 lg:col-4 p-0 md:p-3 mt-4 md:mt-0">
          <div
            class="p-3 flex flex-column border-200 pricing-card cursor-pointer border-2 hover:border-primary transition-duration-300 transition-all"
            style="border-radius: 10px"
          >
            <h3 class="text-900 text-center my-5">Enterprise</h3>
            <img
              src="@svg/enterprise.svg"
              class="w-10 h-10 mx-auto"
              alt="enterprise"
            />
            <div class="my-5 text-center">
              <span class="text-5xl font-bold mr-2 text-900">$999</span
              ><span class="text-600">per month</span
              ><button
                class="p-button p-component block mx-auto mt-4 p-button-rounded border-none ml-3 font-light line-height-2 bg-blue-500 text-white"
                type="button"
                aria-label="Get a Quote"
              >
                <!----><!----><span class="p-button-label">Get a Quote</span
                ><!----><span
                  class="p-ink"
                  role="presentation"
                  aria-hidden="true"
                ></span>
              </button>
            </div>
            <div
              class="p-divider p-component p-divider-horizontal p-divider-solid p-divider-left w-full bg-surface-200"
              role="separator"
              aria-orientation="horizontal"
            >
              <!---->
            </div>
            <ul class="my-5 list-none p-0 flex text-900 flex-column">
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3">Responsive Layout</span>
              </li>
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3"
                  >Unlimited Push Messages</span
                >
              </li>
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3">50 Support Ticket</span>
              </li>
              <li class="py-2">
                <i class="pi pi-fw pi-check text-xl text-cyan-500 mr-2"></i
                ><span class="text-xl line-height-3">Free Shipping</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup></script>

<style lang="scss" scoped></style>
