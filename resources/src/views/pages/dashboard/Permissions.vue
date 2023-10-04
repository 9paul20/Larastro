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
              @click="getAllPermissions()"
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
          <Column
            field="id"
            header="ID"
            exportHeader="ID"
            sortable
            style="min-width: 12rem"
          >
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
          <Column field="name" header="Name" sortable style="min-width: 12rem">
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
          <Column :exportable="false" style="min-width: 8rem">
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
      :style="{ width: '450px' }"
      header="Role Details"
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
      <template #footer>
        <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
        <Button label="Save" icon="pi pi-check" text @click="savePermission" />
      </template>
    </Dialog>

    <!-- Dialog Delete Permission -->
    <Dialog
      v-model:visible="deletePermissionDialog"
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
import { FilterMatchMode } from "primevue/api";
import { Datum } from "@js/interfaces/Roles/Role";
import { PermissionLastID } from "@js/interfaces/index";
//
const toast = useToast();
const store = permissionsStore();
const dt = ref<any>();
const permission = ref<Datum>();
const permissions = ref<Datum[]>([]);
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
  name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});
const exportCSV = (event: any) => {
  dt.value.exportCSV();
};
const openNew = () => {
  permission.value = {
    id: 0,
    name: "",
  };
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
  };
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
      permissions.value = resp.data;
      //   console.log(permissions.value);
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
const savePermission = () => {
  submitted.value = true;
  createOrUpdate.value = permission.value?.id === 0 ? true : false;

  if (permission.value?.name.trim()) {
    // Create Permission
    if (createOrUpdate.value) {
      store
        .storePermission(permission.value)
        .then((respStore: any) => {
          //console.log(respStore);
          if (respStore && respStore.severity === "success") {
            store
              .getCurrentPermissionId()
              .then((respGetId: any) => {
                lastID.value = respGetId;
                permission.value.id = lastID.value?.nextId;
                //console.log("ID: ", lastID.value?.nextId, "Permission: ", permission.value);
                permissions.value.push(permission.value as Datum);
                permission.value = {
                  id: 0,
                  name: "",
                };
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
    // Update Permission
    else {
      store
        .updatePermission(permission.value)
        .then((resp: any) => {
          //console.log(resp);
          if (resp && resp.severity === "info") {
            permissions.value[findIndexById(permission.value.id)] = permission.value;
            permissionDialog.value = false;
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
const editPermission = (prod: Datum) => {
  permission.value = { ...prod };
  permissionDialog.value = true;
  submitted.value = false;
  errors.value = null;
};
const confirmDeletePermission = (prod: Datum) => {
  permission.value = prod;
  deletePermissionDialog.value = true;
};
const deletePermission = () => {
  if (permission.value) {
    store
      .destroyPermission(permission.value)
      .then((resp: any) => {
        //console.log(resp);
        if (resp.severity === "warn") {
          permissions.value = permissions.value.filter(
            (val) => val.id !== permission.value?.id
          );
          permission.value = {
            id: 0,
            name: "",
          };
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
  selectedPermissions.value.forEach((selectedPermission: Datum) => {
    permissionsID.value.push({ id: selectedPermission.id });
  });
  store
    .destroyPermissions(permissionsID.value)
    .then((resp: any) => {
      // console.log(resp);
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
  getAllPermissions();
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