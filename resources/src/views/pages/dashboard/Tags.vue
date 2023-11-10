<template>
  <div class="grid">
    <div class="col-12">
      <div class="card p-fluid">
        <h1>Tags</h1>
        <DataTable
          :loading="loading"
          :paginator="true"
          :rows="10"
          :rowsPerPageOptions="[5, 10, 20, 50, 100]"
          :rowHover="true"
          :value="tags"
          v-model:filters="filters"
          v-model:selection="selectedTags"
          class="p-datatable-gridlines"
          dataKey="id"
          filterDisplay="row"
          exportFilename="Tags"
          ref="dt"
          removableSort
          responsiveLayout="scroll"
          :selectionOnly="true"
          showGridlines
        >
          <template #paginatorstart>
            <Button type="button" icon="pi pi-refresh" text @click="getAllTags()" />
          </template>
          <template #paginatorend>
            <!-- <Button type="button" icon="pi pi-download" text /> -->
          </template>
          <Toolbar class="mb-4">
            <template #start>
              <Button
                label="New"
                icon="pi pi-plus"
                severity="success"
                class="mr-2"
                @click="openNew"
              />
              <Button
                label="Delete"
                icon="pi pi-trash"
                severity="danger"
                @click="confirmDeleteSelected"
                :disabled="!selectedTags || !selectedTags.length"
              />
            </template>
            <template #end>
              <Button
                label="Export"
                icon="pi pi-upload"
                severity="help"
                @click="exportCSV($event)"
              />
            </template>
          </Toolbar>
          <template #empty> No tags found. </template>
          <template #loading> Loading tags data. Please wait. </template>
          <Column
            selectionMode="multiple"
            style="width: 3rem"
            :exportable="false"
          ></Column>
          <Column field="id" header="ID" exportHeader="ID" sortable style="">
            <template #body="{ data }">
              {{ data.id }}
            </template>
            <template #filter="{ filterModel, filterCallback }">
              <InputText
                v-model="filterModel.value"
                type="text"
                class="p-column-filter"
                @input="filterCallback()"
                placeholder="Search by ID"
              />
            </template>
          </Column>
          <Column field="name" header="Name" sortable style="">
            <template #body="{ data }">
              {{ data.name }}
            </template>
            <template #filter="{ filterModel, filterCallback }">
              <InputText
                v-model="filterModel.value"
                type="text"
                class="p-column-filter"
                @input="filterCallback()"
                placeholder="Search by Name"
              />
            </template>
          </Column>
          <Column field="description" header="Description" style="" sortable>
            <template #body="{ data }">
              {{ data.description }}
            </template>
            <template #filter="{ filterModel, filterCallback }">
              <InputText
                v-model="filterModel.value"
                type="text"
                class="p-column-filter"
                @input="filterCallback()"
                placeholder="Search by Description"
              />
            </template>
          </Column>
          <Column :exportable="false" style="min-width: 9rem">
            <template #body="slotProps">
              <Button
                icon="pi pi-pencil"
                outlined
                rounded
                class="mr-2"
                @click="editTag(slotProps.data)"
              />
              <Button
                icon="pi pi-trash"
                outlined
                rounded
                severity="danger"
                @click="confirmDeleteTag(slotProps.data)"
              />
            </template>
          </Column>
          <template #footer>
            In total there are {{ tags ? tags.length : 0 }} Tags.
          </template>
        </DataTable>
      </div>
    </div>
    <!-- Dialog Create Tag -->
    <Dialog
      v-model:visible="tagDialog"
      :closable="false"
      :style="{ width: '500px' }"
      header="Tag Details"
      :modal="true"
      class="p-fluid"
    >
      <!-- <Fieldset legend="Tag Information"> -->
      <InputNumber
        id="id"
        v-model.trim="tag.id"
        required="true"
        type="number"
        class="hidden"
      />
      <div class="grid formgrid">
        <div class="field col-12 mb-2">
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
        </div>
        <div class="field col-12 mb-2">
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
        </div>
      </div>
      <!-- </Fieldset> -->

      <template #footer>
        <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
        <Button label="Save" icon="pi pi-check" text @click="saveTag" />
      </template>
    </Dialog>

    <!-- Dialog Delete Tag -->
    <Dialog
      v-model:visible="deleteTagDialog"
      :closable="false"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="tag"
          >Are you sure you want to delete <b>{{ tag.name }}</b
          >?</span
        >
      </div>
      <template #footer>
        <Button label="No" icon="pi pi-times" text @click="deleteTagDialog = false" />
        <Button label="Yes" icon="pi pi-check" text @click="deleteTag" />
      </template>
    </Dialog>

    <!-- Dialog Delete Tags -->
    <Dialog
      v-model:visible="deleteTagsDialog"
      :closable="false"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="tags">Are you sure you want to delete the selected tags?</span>
      </div>
      <template #footer>
        <Button label="No" icon="pi pi-times" text @click="deleteTagsDialog = false" />
        <Button
          label="Yes"
          icon="pi pi-check"
          text
          v-if="tags"
          @click="deleteSelectedTags"
        />
      </template>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { onBeforeMount, ref } from "vue";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "primevue/api";
import { tagsStore } from "@js/stores/Tags";
import { DatumTag } from "@js/interfaces/Tags/Tag";
import { TagLastID } from "@js/interfaces/index";
//
const toast = useToast();
const store = tagsStore();
const dt = ref<any>();
const tag = ref<DatumTag>();
const tags = ref<DatumTag[]>([]);
const loading = ref<boolean>();
const tagDialog = ref<boolean>(false);
const deleteTagDialog = ref<boolean>(false);
const deleteTagsDialog = ref<boolean>(false);
const lastID = ref<TagLastID>();
const createOrUpdate = ref<boolean>();
const errors = ref(null);
const selectedTags = ref<[]>([]);
const submitted = ref<boolean>(false);
const filters = ref<{}>({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  description: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});
const exportCSV = (event: any) => {
  dt.value.exportCSV();
};
const openNew = () => {
  tag.value = {
    id: 0,
    name: "",
    description: "",
  };
  submitted.value = false;
  tagDialog.value = true;
  errors.value = null;
};
const hideDialog = () => {
  tagDialog.value = false;
  submitted.value = false;
  errors.value = null;
  tag.value = {
    id: 0,
    name: "",
    description: "",
  };
};
const hideErrors = (field: string) => {
  if (errors.value && field in errors.value) {
    errors.value[field] = null as never;
  }
};
const getAllTags = () => {
  loading.value = true;
  store
    .getAllTags()
    .then((resp: any) => {
      tags.value = resp.data;
      loading.value = false;
    })
    .catch((error: string) => {
      console.error(error);
      loading.value = false;
      toast.add({
        severity: "error",
        summary: "Error",
        detail: "Can't get tags list: " + error,
        life: 3000,
      });
    });
};
const saveTag = () => {
  submitted.value = true;
  createOrUpdate.value = tag.value?.id === 0 ? true : false;

  if (tag.value?.name.trim()) {
    // Create Tag
    if (createOrUpdate.value) {
      store
        .storeTag(tag.value)
        .then((respStore: any) => {
          if (respStore && respStore.severity === "success") {
            store
              .getCurrentTagId()
              .then((respGetId: any) => {
                lastID.value = respGetId;
                tag.value.id = lastID.value?.nextId;
                tags.value.push(tag.value as DatumTag);
                tag.value = {
                  id: 0,
                  name: "",
                  description: "",
                };
                tagDialog.value = false;
                toast.add({
                  severity: respStore.severity,
                  summary: respStore.summary,
                  detail: respStore.detail,
                  life: 3000,
                });
              })
              .catch((error: string) => {
                console.error(error);
                loading.value = false;
                toast.add({
                  severity: "error",
                  summary: "Error",
                  detail: "Can't get last id of tags: " + error,
                  life: 3000,
                });
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
    // Update Tag
    else {
      store
        .updateTag(tag.value)
        .then((resp: any) => {
          if (resp && resp.severity === "info") {
            tags.value[findIndexById(tag.value.id)] = tag.value;
            tagDialog.value = false;
            tag.value = {
              id: 0,
              name: "",
              description: "",
            };
            toast.add({
              severity: resp.severity,
              summary: resp.summary,
              detail: resp.detail,
              life: 3000,
            });
          } else if (resp.response.status === 422) {
            toast.add({
              severity: resp.response.data.severity,
              summary: resp.response.data.summary,
              detail: resp.response.data.detail + " " + resp.response.data.name,
              life: 3000,
            });
            if (resp.response.data.errors) {
              errors.value = resp.response.data.errors;
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
  }
};
const editTag = (prod: DatumTag) => {
  tag.value = { ...prod };
  tagDialog.value = true;
  submitted.value = false;
  errors.value = null;
};
const confirmDeleteTag = (prod: DatumTag) => {
  tag.value = prod;
  deleteTagDialog.value = true;
};
const deleteTag = () => {
  if (tag.value) {
    store
      .destroyTag(tag.value)
      .then((resp: any) => {
        if (resp.severity === "warn") {
          tags.value = tags.value.filter((val) => val.id !== tag.value?.id);
          tag.value = {
            id: 0,
            name: "",
            description: "",
          };
          deleteTagDialog.value = false;
          toast.add({
            severity: resp.severity,
            summary: resp.summary,
            detail: resp.detail,
            life: 3000,
          });
        } else if (resp.response.status === 422) {
          toast.add({
            severity: resp.response.data.severity,
            summary: resp.response.data.summary,
            detail: resp.response.data.detail + ", " + resp.response.data.name,
            life: 3000,
          });
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
const findIndexById = (id: number) => {
  let index = -1;
  for (let i = 0; i < tags.value.length; i++) {
    if (tags.value[i].id === id) {
      index = i;
      break;
    }
  }

  return index;
};
const confirmDeleteSelected = () => {
  deleteTagsDialog.value = true;
};
const deleteSelectedTags = () => {
  const tagsID = ref<{ id: number }[]>([]);
  selectedTags.value.forEach((selectedTag: DatumTag) => {
    tagsID.value.push({ id: selectedTag.id });
  });
  store
    .destroyTags(tagsID.value)
    .then((resp: any) => {
      if (resp.severity === "warn") {
        tags.value = tags.value.filter(
          (val) => !selectedTags.value.includes(val as never)
        );
        deleteTagsDialog.value = false;
        selectedTags.value = [];
        toast.add({
          severity: resp.severity,
          summary: resp.summary,
          detail: resp.detail,
          life: 3000,
        });
      } else if (resp.severity === "warn") {
        deleteTagsDialog.value = false;
        selectedTags.value = [];
        toast.add({
          severity: resp.severity,
          summary: resp.summary,
          detail: resp.detail,
          life: 3000,
        });
      } else if (resp.response.status === 422) {
        toast.add({
          severity: resp.response.data.severity,
          summary: resp.response.data.summary,
          detail: resp.response.data.detail + " " + resp.response.data.name,
          life: 3000,
        });
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
};
//
onBeforeMount(() => {
  //Antes de que se monte el componente se obtienen todos los tags
  getAllTags();
});
//
</script>

<style scoped lang="scss">
::v-deep(.p-datatable-frozen-tbody) {
  font-weight: bold;
}

::v-deep(.p-datatable-scrollable .p-frozen-column) {
  font-weight: bold;
}
</style>
