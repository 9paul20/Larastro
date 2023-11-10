<template>
  <div>
    <Dialog
      v-model:visible="dialogVisible"
      :closable="false"
      :style="{ width: '500px' }"
      header="Tag Details"
      :modal="true"
      class="p-fluid"
      @onHide="onHideDialog"
    >
      <!-- <Fieldset legend="Tag Information"> -->
      <div class="grid formgrid">
        <!-- <div class="field col-12 mb-2">
          <label for="name">Name</label>
          <InputText
            id="name"
            v-model.trim="tag.name"
            required="true"
            autofocus
            type="text"
            placeholder="Write a name"
            :class="{
              'p-invalid': (submitted && !tag?.name) || errors?.name,
            }"
            @blur="hideErrors('name')"
          />
          <small class="p-error" v-if="submitted && !tag?.name">Name is required.</small>
          <div v-if="errors?.name">
            <div v-for="(errorName, indexName) in errors.name" :key="indexName">
              <small class="p-error">{{ errorName }}</small>
            </div>
          </div>
        </div> -->
        <!-- <div class="field col-12 mb-2">
          <label for="description">Description</label>
          <Textarea
            id="description"
            v-model.trim="tag.description"
            rows="5"
            autoResize
            required="false"
            type="text"
            placeholder="Write a description"
            :class="{
              'p-invalid': errors?.description,
            }"
            @blur="hideErrors('description')"
          />
          <div v-if="errors?.description">
            <div
              v-for="(errorDescription, indexDescription) in errors.description"
              :key="indexDescription"
            >
              <small class="p-error">{{ errorDescription }}</small>
            </div>
          </div>
        </div> -->
      </div>
      <!-- </Fieldset> -->

      <template #footer>
        <Button label="Cancel" icon="pi pi-times" text @click="hideTagDialog" />
        <Button label="Save" icon="pi pi-check" text @click="saveTag" />
      </template>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import { useToast } from "primevue/usetoast";
import { tagsStore } from "@js/stores/Tags";
import { DatumTag } from "@js/interfaces/Tags/Tag";
//
const props = defineProps({
  tagDialog: {
    type: Boolean,
    required: false,
    default: false,
  },
});
//
const emits = defineEmits();
//
const dialogVisible = ref(props.tagDialog);
//
const toast = useToast();
const storeTagsDialog = tagsStore();
const tag = ref<DatumTag>();
// const tagDialog = ref<boolean>(false);
const errors = ref(null);
const submitted = ref<boolean>(false);
//
const hideErrors = (field: string) => {
  if (errors.value && field in errors.value) {
    errors.value[field] = null as never;
  }
};
const hideTagDialog = () => {
  dialogVisible.value = false;
  submitted.value = false;
  errors.value = null;
  tag.value = {
    id: 0,
    name: "",
    description: "",
  };
  // emits("permissionDialogChanged", true);
  emits("closeDialog", true);
};
const onHideDialog = () => {
  dialogVisible.value = false;
  emits("closeDialog", true);
};
const saveTag = () => {
  submitted.value = true;

  if (tag.value?.name.trim()) {
    storeTagsDialog
      // storeTags
      .storeTag(tag.value)
      .then((respStore: any) => {
        if (respStore && respStore.severity === "success") {
          tag.value = {
            id: 0,
            name: "",
            description: "",
          };
          dialogVisible.value = false;
          toast.add({
            severity: respStore.severity,
            summary: respStore.summary,
            detail: respStore.detail,
            life: 3000,
          });
        } else if (respStore.response.status === 422) {
          toast.add({
            severity: respStore.response.data.severity,
            summary: respStore.response.data.summary,
            detail: respStore.response.data.detail + " " + respStore.response.data.name,
            life: 3000,
          });
          if (respStore.response.data.errors) {
            errors.value = respStore.response.data.errors;
          }
        }
      })
      .catch((error: string) => {
        toast.add({
          severity: "error",
          summary: "Error",
          detail: "Error of server: " + error,
          life: 3000,
        });
        console.error(error);
      });
  }
};
//
watch(
  () => props.tagDialog,
  (newValue) => {
    dialogVisible.value = newValue;
  }
);
</script>

<style scoped></style>
