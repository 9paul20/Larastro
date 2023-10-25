<template>
  <div class="grid">
    <div class="col-12">
      <div class="card p-fluid">
        <h1>Permissions</h1>
        <DataTable
          :loading="loading"
          :paginator="true"
          :rows="10"
          :rowsPerPageOptions="[5, 10, 20, 50, 100]"
          :rowHover="true"
          :value="permissions"
          v-model:filters="filters"
          v-model:selection="selectedPermissions"
          class="p-datatable-gridlines"
          dataKey="id"
          filterDisplay="row"
          exportFilename="Permissions"
          ref="dt"
          removableSort
          responsiveLayout="scroll"
          :selectionOnly="true"
          showGridlines
        >
          <template #paginatorstart>
            <Button
              type="button"
              icon="pi pi-refresh"
              text
              @click="getAllPermissions(), getAllTags()"
            />
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
                :disabled="!selectedPermissions || !selectedPermissions.length"
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
          <template #empty> No permissions found. </template>
          <template #loading> Loading permissions data. Please wait. </template>
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
          <Column field="description" header="Description" sortable style="">
            <template #body="{ data }">
              {{ data.description ? data.description : "Without Description" }}
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
          <Column field="tags" header="Tags" sortable style="">
            <template #body="{ data }">
              <div
                v-if="data.tags && data.tags.length > 0"
                class="flex flex-wrap justify-content-center gap-2"
              >
                <template v-for="tag in data.tags.slice(0, 4)">
                  <Tag
                    icon="pi pi-user"
                    :class="{ 'p-mr-2': tag.id !== data.tags.slice(0, 4).length - 1 }"
                    severity="info"
                  >
                    {{ tag.name }}
                  </Tag>
                </template>
                <template v-if="data.tags.length > 4">
                  <Tag icon="pi pi-plus" severity="info">{{ data.tags.length - 4 }}</Tag>
                </template>
              </div>
              <div v-else class="flex flex-wrap justify-content-center gap-2">
                <Tag icon="pi pi-times" severity="danger">Without Tags</Tag>
              </div>
            </template>
          </Column>
          <Column :exportable="false" style="min-width: 9rem">
            <template #body="slotProps">
              <Button
                icon="pi pi-pencil"
                outlined
                rounded
                class="mr-2"
                @click="editPermission(slotProps.data)"
              />
              <Button
                icon="pi pi-trash"
                outlined
                rounded
                severity="danger"
                @click="confirmDeletePermission(slotProps.data)"
              />
            </template>
          </Column>
          <template #footer>
            In total there are {{ permissions ? permissions.length : 0 }} Permissions.
          </template>
        </DataTable>
      </div>
    </div>

    <!-- Dialog Create Permission -->
    <Dialog
      v-model:visible="permissionDialog"
      :closable="false"
      :style="{ width: '450px' }"
      header="Permission Details"
      :modal="true"
      class="p-fluid"
    >
      <InputNumber
        id="id"
        v-model.trim="permission.id"
        required="true"
        type="number"
        class="hidden"
      />
      <div class="field">
        <label for="name">Name</label>
        <InputText
          id="name"
          v-model.trim="permission.name"
          required="true"
          autofocus
          type="text"
          placeholder="Write a name"
          :class="{
            'p-invalid': (submitted && !permission?.name) || errors?.name,
          }"
          @blur="hideErrors('name')"
        />
        <small class="p-error" v-if="submitted && !permission?.name"
          >Name is required.</small
        >
        <div v-if="errors?.name">
          <div v-for="(errorName, indexName) in errors.name" :key="indexName">
            <small class="p-error">{{ errorName }}</small>
          </div>
        </div>
      </div>
      <div class="field">
        <label for="description">Description</label>
        <Textarea
          id="description"
          v-model.trim="permission.description"
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

      <div
        class="flex flex-column sm:flex-row sm:align-items-center sm:justify-content-between"
      >
        <div class="field d-flex align-items-center flex-1">
          <label for="tags" class="flex-shrink-0">Tags</label>
          <MultiSelect
            id="tags"
            v-model.trim="selectedTags"
            :options="tags"
            required="false"
            filter
            optionLabel="name"
            placeholder="Select Tags"
            :maxSelectedLabels="4"
            :class="{
              'p-invalid': errors?.tags,
            }"
            @blur="hideErrors('tags')"
          />
          <div v-for="(error, key) in errors" :key="key">
            <div v-if="typeof key === 'string' && key.startsWith('tags')">
              <span v-for="(errorMsg, index) in error" :key="index">
                <small class="p-error">{{ errorMsg }}</small>
              </span>
            </div>
            <div v-else>
              <small class="p-error"></small>
            </div>
          </div>
        </div>
        <div class="flex align-items-center flex-3 mt-3 ml-2">
          <button
            class="p-button p-component"
            type="button"
            aria-label="Add Tag"
            data-pc-name="button"
            data-pc-section="root"
            data-pd-ripple="true"
            style="font-size: 1.2rem; padding: 0.9rem 1rem"
            @click="console.log('VAMOS A ABRIR EL DIALOGO DE TAGS')"
          >
            <span class="p-button-icon p-c pi pi-plus"></span>
          </button>
        </div>
      </div>
      <template #footer>
        <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
        <Button label="Save" icon="pi pi-check" text @click="savePermission" />
      </template>
    </Dialog>

    <!-- Dialog Delete Permission -->
    <Dialog
      v-model:visible="deletePermissionDialog"
      :closable="false"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="permission"
          >Are you sure you want to delete <b>{{ permission.name }}</b
          >?</span
        >
      </div>
      <template #footer>
        <Button
          label="No"
          icon="pi pi-times"
          text
          @click="deletePermissionDialog = false"
        />
        <Button label="Yes" icon="pi pi-check" text @click="deletePermission" />
      </template>
    </Dialog>

    <!-- Dialog Delete Permissions -->
    <Dialog
      v-model:visible="deletePermissionsDialog"
      :closable="false"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="permissions"
          >Are you sure you want to delete the selected permissions?</span
        >
      </div>
      <template #footer>
        <Button
          label="No"
          icon="pi pi-times"
          text
          @click="deletePermissionsDialog = false"
        />
        <Button
          label="Yes"
          icon="pi pi-check"
          text
          v-if="permissions"
          @click="deleteSelectedPermissions"
        />
      </template>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { onBeforeMount, ref } from "vue";
import { useToast } from "primevue/usetoast";
import { permissionsStore } from "@js/stores/Permissions";
import { tagsStore } from "@js/stores/Tags";
import { FilterMatchMode } from "primevue/api";
import { DatumPermission, TagPermission } from "@js/interfaces/Permissions/Permission";
import { DatumTag } from "@js/interfaces/Tags/Tag";
import { PermissionLastID } from "@js/interfaces/index";
//
const toast = useToast();
const store = permissionsStore();
const storeTags = tagsStore();
const dt = ref<any>();
const permission = ref<DatumPermission>();
const permissions = ref<DatumPermission[]>([]);
const tags = ref<DatumTag[]>([]);
const selectedTags = ref<[]>([]);
const loading = ref<boolean>();
const permissionDialog = ref<boolean>(false);
const deletePermissionDialog = ref<boolean>(false);
const deletePermissionsDialog = ref<boolean>(false);
const lastID = ref<PermissionLastID>();
const createOrUpdate = ref<boolean>();
const errors = ref(null);
const selectedPermissions = ref<[]>([]);
const submitted = ref<boolean>(false);
const filters = ref<{}>({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  name: { value: null, matchMode: FilterMatchMode.CONTAINS },
  description: { value: null, matchMode: FilterMatchMode.CONTAINS },
  tags: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const exportCSV = (event: any) => {
  dt.value.exportCSV();
};
const openNew = () => {
  permission.value = {
    id: 0,
    name: "",
    description: "",
    tags: [],
  };
  selectedTags.value = [];
  submitted.value = false;
  permissionDialog.value = true;
  errors.value = null;
};
const hideDialog = () => {
  permissionDialog.value = false;
  submitted.value = false;
  errors.value = null;
  permission.value = {
    id: 0,
    name: "",
    description: "",
    tags: [],
  };
  selectedTags.value = [];
};
const hideErrors = (field: string) => {
  if (errors.value && field in errors.value) {
    errors.value[field] = null as never;
  }
};
//
const getAllPermissions = () => {
  loading.value = true;
  store
    .getAllPermissions()
    .then((resp: any) => {
      permissions.value = resp.data.map((permission: DatumPermission) => {
        return {
          ...permission,
          tags: permission.tags.map((tag) => ({ id: tag.id, name: tag.name })),
        };
      });
      loading.value = false;
    })
    .catch((error: string) => {
      console.error(error);
      loading.value = false;
      toast.add({
        severity: "error",
        summary: "Error",
        detail: "Can't get permissions list: " + error,
        life: 3000,
      });
    });
};
const getAllTags = () => {
  loading.value = true;
  storeTags
    .getAllTags()
    .then((resp: any) => {
      tags.value = resp.data.map((tag: TagPermission) => ({
        id: tag.id,
        name: tag.name,
      }));
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
const savePermission = () => {
  submitted.value = true;
  createOrUpdate.value = permission.value?.id === 0 ? true : false;

  if (permission.value?.name.trim()) {
    permission.value.tags = selectedTags.value;
    // Create Permission
    if (createOrUpdate.value) {
      store
        .storePermission(permission.value)
        .then((respStore: any) => {
          if (respStore && respStore.severity === "success") {
            store
              .getCurrentPermissionId()
              .then((respGetId: any) => {
                lastID.value = respGetId;
                permission.value.id = lastID.value?.nextId;
                permissions.value.push(permission.value as DatumPermission);
                permission.value = {
                  id: 0,
                  name: "",
                  description: "",
                  tags: [],
                };
                selectedTags.value = [];
                permissionDialog.value = false;
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
                  detail: "Can't get last id of permissions: " + error,
                  life: 3000,
                });
              });
          } else if (respStore.response.status === 422) {
            toast.add({
              severity: respStore.response.data.severity,
              summary: respStore.response.data.summary,
              detail: respStore.response.data.detail,
              life: 3000,
            });
            if (respStore.response.data.errors) {
              errors.value = respStore.response.data.errors;
              console.error(errors.value);
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
    // Update Permission
    else {
      store
        .updatePermission(permission.value)
        .then((resp: any) => {
          if (resp && resp.severity === "info") {
            permissions.value[findIndexById(permission.value.id)] = permission.value;
            permissionDialog.value = false;
            permission.value = {
              id: 0,
              name: "",
              description: "",
              tags: [],
            };
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
              detail: resp.response.data.detail,
              life: 3000,
            });
            if (resp.response.data.errors) {
              errors.value = resp.response.data.errors;
              console.error(errors.value);
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
const editPermission = (prod: DatumPermission) => {
  permission.value = { ...prod };
  permission.value.tags.forEach((tag) => {
    selectedTags.value.push(tag);
  });
  permissionDialog.value = true;
  submitted.value = false;
  errors.value = null;
};
const confirmDeletePermission = (prod: DatumPermission) => {
  permission.value = prod;
  deletePermissionDialog.value = true;
};
const deletePermission = () => {
  if (permission.value) {
    store
      .destroyPermission(permission.value)
      .then((resp: any) => {
        if (resp.severity === "warn") {
          permissions.value = permissions.value.filter(
            (val) => val.id !== permission.value?.id
          );
          permission.value = {
            id: 0,
            name: "",
            description: "",
            tags: [],
          };
          selectedTags.value = [];
          deletePermissionDialog.value = false;
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
            detail: resp.response.data.detail,
            life: 3000,
          });
          console.error(resp.response.data.errors);
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
  for (let i = 0; i < permissions.value.length; i++) {
    if (permissions.value[i].id === id) {
      index = i;
      break;
    }
  }

  return index;
};
const confirmDeleteSelected = () => {
  deletePermissionsDialog.value = true;
};
const deleteSelectedPermissions = () => {
  const permissionsID = ref<{ id: number }[]>([]);
  selectedPermissions.value.forEach((selectedPermission: DatumPermission) => {
    permissionsID.value.push({ id: selectedPermission.id });
  });
  store
    .destroyPermissions(permissionsID.value)
    .then((resp: any) => {
      if (resp.severity === "warn") {
        permissions.value = permissions.value.filter(
          (val) => !selectedPermissions.value.includes(val as never)
        );
        deletePermissionsDialog.value = false;
        selectedPermissions.value = [];
        toast.add({
          severity: resp.severity,
          summary: resp.summary,
          detail: resp.detail,
          life: 3000,
        });
      } else if (resp.severity === "warn") {
        deletePermissionsDialog.value = false;
        selectedPermissions.value = [];
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
          detail: resp.response.data.detail,
          life: 3000,
        });
        console.error(resp.response.data.errors);
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
onBeforeMount(() => {
  getAllPermissions();
  getAllTags();
});
</script>

<style scoped lang="scss">
::v-deep(.p-datatable-frozen-tbody) {
  font-weight: bold;
}

::v-deep(.p-datatable-scrollable .p-frozen-column) {
  font-weight: bold;
}
</style>
