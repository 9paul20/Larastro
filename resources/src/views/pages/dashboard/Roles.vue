<template>
  <div class="grid">
    <div class="col-12">
      <div class="card p-fluid">
        <h1>Roles</h1>
        <DataTable
          :loading="loading"
          :paginator="true"
          :rows="10"
          :rowsPerPageOptions="[5, 10, 20, 50, 100]"
          :rowHover="true"
          :value="roles"
          v-model:filters="filters"
          v-model:selection="selectedRoles"
          class="p-datatable-gridlines"
          dataKey="id"
          filterDisplay="row"
          exportFilename="Roles"
          ref="dt"
          removableSort
          responsiveLayout="scroll"
          :selectionOnly="true"
          showGridlines
        >
          <template #paginatorstart>
            <Button type="button" icon="pi pi-refresh" text @click="getAllRoles()" />
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
                :disabled="!selectedRoles || !selectedRoles.length"
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
          <template #empty> No roles found. </template>
          <template #loading> Loading roles data. Please wait. </template>
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
          <Column
            field="description"
            header="Description"
            sortable
            style="min-width: 12rem"
          >
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
          <Column field="tags" header="Tags" sortable style="min-width: 12rem">
            <template #body="{ data }">
              {{ data.tags ? data.tags.join(", ") : "Without Tags" }}
            </template>
            <template #filter="{ filterModel, filterCallback }">
              <InputText
                v-model="filterModel.value"
                type="text"
                class="p-column-filter"
                @input="filterCallback()"
                placeholder="Search by Tags"
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
                @click="editRole(slotProps.data)"
              />
              <Button
                icon="pi pi-trash"
                outlined
                rounded
                severity="danger"
                @click="confirmDeleteRole(slotProps.data)"
              />
            </template>
          </Column>
          <template #footer>
            In total there are {{ roles ? roles.length : 0 }} Roles.
          </template>
        </DataTable>
      </div>
    </div>

    <!-- Dialog Create Role -->
    <Dialog
      v-model:visible="roleDialog"
      :style="{ width: '450px' }"
      header="Role Details"
      :modal="true"
      class="p-fluid"
    >
      <InputNumber
        id="id"
        v-model.trim="role.id"
        required="true"
        type="number"
        class="hidden"
      />
      <div class="field">
        <label for="name">Name</label>
        <InputText
          id="name"
          v-model.trim="role.name"
          required="true"
          autofocus
          type="text"
          placeholder="Write a name"
          :class="{
            'p-invalid': (submitted && !role?.name) || errors?.name,
          }"
          @blur="hideErrors('name')"
        />
        <small class="p-error" v-if="submitted && !role?.name">Name is required.</small>
        <div v-if="errors?.name">
          <div v-for="(errorName, indexName) in errors.name" :key="indexName">
            <small class="p-error">{{ errorName }}</small>
          </div>
        </div>
      </div>
      <div class="field">
        <label for="description">Description</label>
        <InputText
          id="description"
          v-model.trim="role.description"
          required="false"
          type="text"
          placeholder="Write a description"
          :class="{
            'p-invalid': errors?.description,
          }"
          @blur="hideErrors('description')"
        />
        <!-- <small class="p-error" v-if="submitted && !role?.description"
          >Description is required.</small
        > -->
        <div v-if="errors?.description">
          <div
            v-for="(errorDescription, indexDescription) in errors.description"
            :key="indexDescription"
          >
            <small class="p-error">{{ errorDescription }}</small>
          </div>
        </div>
      </div>
      <div class="field">
        <label for="tags">Tags</label>
        <Chips
          id="tags"
          v-model.trim="role.tags"
          separator=","
          required="false"
          placeholder="Write a description"
          :class="{
            'p-invalid': errors?.tags,
          }"
          @blur="hideErrors('tags')"
        />
        <!-- <small class="p-error" v-if="submitted && !role?.tags">Tags is required.</small> -->
        <div v-if="errors">
          <small class="p-error">{{ errors }}</small>
          <div v-for="(errorTags, indexTags) in errors.tags" :key="indexTags">
            <small class="p-error">{{ errorTags }}</small>
            <div v-for="(error, index) in errors.tags.errorTags" :key="index">
              <small class="p-error">{{ error }}</small>
            </div>
          </div>
        </div>
      </div>
      <h5>Permissions</h5>
      <div class="card px-3 py-3">
        <h5>Users</h5>
        <div class="grid">
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption1"
                name="option"
                value="Create"
                v-model="checkboxValue"
              />
              <label for="checkOption1">Create</label>
            </div>
          </div>
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption2"
                name="option"
                value="Read"
                v-model="checkboxValue"
              />
              <label for="checkOption2">Read</label>
            </div>
          </div>
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption3"
                name="option"
                value="Update"
                v-model="checkboxValue"
              />
              <label for="checkOption3">Update</label>
            </div>
          </div>
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption3"
                name="option"
                value="Delete"
                v-model="checkboxValue"
              />
              <label for="checkOption3">Delete</label>
            </div>
          </div>
        </div>
        <h5 class="my-3">Users</h5>
        <div class="grid">
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption1"
                name="option"
                value="Create"
                v-model="checkboxValue"
              />
              <label for="checkOption1">Create</label>
            </div>
          </div>
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption2"
                name="option"
                value="Read"
                v-model="checkboxValue"
              />
              <label for="checkOption2">Read</label>
            </div>
          </div>
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption3"
                name="option"
                value="Update"
                v-model="checkboxValue"
              />
              <label for="checkOption3">Update</label>
            </div>
          </div>
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption3"
                name="option"
                value="Delete"
                v-model="checkboxValue"
              />
              <label for="checkOption3">Delete</label>
            </div>
          </div>
        </div>
        <h5 class="my-3">Users</h5>
        <div class="grid">
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption1"
                name="option"
                value="Create"
                v-model="checkboxValue"
              />
              <label for="checkOption1">Create</label>
            </div>
          </div>
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption2"
                name="option"
                value="Read"
                v-model="checkboxValue"
              />
              <label for="checkOption2">Read</label>
            </div>
          </div>
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption3"
                name="option"
                value="Update"
                v-model="checkboxValue"
              />
              <label for="checkOption3">Update</label>
            </div>
          </div>
          <div class="col-12 md:col-3">
            <div class="field-checkbox mb-0">
              <Checkbox
                id="checkOption3"
                name="option"
                value="Delete"
                v-model="checkboxValue"
              />
              <label for="checkOption3">Delete</label>
            </div>
          </div>
        </div>
      </div>
      <template #footer>
        <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
        <Button label="Save" icon="pi pi-check" text @click="saveRole" />
      </template>
    </Dialog>

    <!-- Dialog Delete Role -->
    <Dialog
      v-model:visible="deleteRoleDialog"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="role"
          >Are you sure you want to delete <b>{{ role.name }}</b
          >?</span
        >
      </div>
      <template #footer>
        <Button label="No" icon="pi pi-times" text @click="deleteRoleDialog = false" />
        <Button label="Yes" icon="pi pi-check" text @click="deleteRole" />
      </template>
    </Dialog>

    <!-- Dialog Delete Roles -->
    <Dialog
      v-model:visible="deleteRolesDialog"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="roles">Are you sure you want to delete the selected roles?</span>
      </div>
      <template #footer>
        <Button label="No" icon="pi pi-times" text @click="deleteRolesDialog = false" />
        <Button
          label="Yes"
          icon="pi pi-check"
          text
          v-if="roles"
          @click="deleteSelectedRoles"
        />
      </template>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { onBeforeMount, ref } from "vue";
import { useToast } from "primevue/usetoast";
import { rolesStore } from "@js/stores/Roles";
import { permissionsStore } from "@js/stores/Permissions";
import { FilterMatchMode } from "primevue/api";
import { DatumRole } from "@js/interfaces/Roles/Role";
import { DatumPermission } from "@js/interfaces/Permissions/Permission";
import { RoleLastID } from "@js/interfaces/index";
//
const toast = useToast();
const store = rolesStore();
const storePermissions = permissionsStore();
const dt = ref<any>();
const role = ref<DatumRole>();
const roles = ref<DatumRole[]>([]);
const permissions = ref<DatumPermission[]>([]);
const checkboxValue = ref([]);
const loading = ref<boolean>();
const roleDialog = ref<boolean>(false);
const deleteRoleDialog = ref<boolean>(false);
const deleteRolesDialog = ref<boolean>(false);
const lastID = ref<RoleLastID>();
const createOrUpdate = ref<boolean>();
const errors = ref(null);
const selectedRoles = ref<[]>([]);
const submitted = ref<boolean>(false);
const filters = ref<{}>({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  description: { value: null, matchMode: FilterMatchMode.CONTAINS },
  tags: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const exportCSV = (event: any) => {
  dt.value.exportCSV();
};
const openNew = () => {
  role.value = {
    id: 0,
    name: "",
    description: "",
    tags: [],
  };
  submitted.value = false;
  roleDialog.value = true;
  errors.value = null;
};
const hideDialog = () => {
  roleDialog.value = false;
  submitted.value = false;
  errors.value = null;
  role.value = {
    id: 0,
    name: "",
    description: "",
    tags: [],
  };
  checkboxValue.value = [];
};
const hideErrors = (field: string) => {
  if (errors.value && field in errors.value) {
    errors.value[field] = null as never;
  }
};
//
const getAllRoles = () => {
  loading.value = true;
  store
    .getAllRoles()
    .then((resp: any) => {
      roles.value = resp.data;
      // console.log(roles.value);
      loading.value = false;
    })
    .catch((error: string) => {
      console.error(error);
      loading.value = false;
      toast.add({
        severity: "error",
        summary: "Error",
        detail: "Can't get roles list: " + error,
        life: 3000,
      });
    });
};
const getAllPermissions = () => {
  storePermissions
    .getAllPermissions()
    .then((resp: any) => {
      permissions.value = resp.data;
      console.log(permissions.value);
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
const saveRole = () => {
  submitted.value = true;
  createOrUpdate.value = role.value?.id === 0 ? true : false;

  if (role.value?.name.trim()) {
    // Create Role
    if (createOrUpdate.value) {
      store
        .storeRole(role.value)
        .then((respStore: any) => {
          //console.log(resp);
          if (respStore && respStore.severity === "success") {
            store
              .getCurrentRoleId()
              .then((respGetId: any) => {
                lastID.value = respGetId;
                roleDialog.value = false;
                role.value.id = lastID.value?.nextId;
                role.value.tags = role.value.tags.join(", ");
                //console.log("ID: ", lastID.value?.nextId, "Role: ", role.value);
                roles.value.push(role.value as DatumRole);
                role.value = {
                  id: 0,
                  name: "",
                  description: "",
                  tags: [],
                };
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
                  detail: "Can't get last id of roles: " + error,
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
              console.log(errors.value);
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
    // Update Role
    else {
      store
        .updateRole(role.value)
        .then((resp: any) => {
          //console.log(resp);
          if (resp && resp.severity === "info") {
            roles.value[findIndexById(role.value.id)] = role.value;
            roleDialog.value = false;
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
const editRole = (prod: DatumRole) => {
  role.value = { ...prod };
  roleDialog.value = true;
  submitted.value = false;
  errors.value = null;
};
const confirmDeleteRole = (prod: DatumRole) => {
  role.value = prod;
  deleteRoleDialog.value = true;
};
const deleteRole = () => {
  if (role.value) {
    store
      .destroyRole(role.value)
      .then((resp: any) => {
        //console.log(resp);
        if (resp.severity === "warn") {
          roles.value = roles.value.filter((val) => val.id !== role.value?.id);
          role.value = {
            id: 0,
            name: "",
            description: "",
            tags: [],
          };
          deleteRoleDialog.value = false;
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
  for (let i = 0; i < roles.value.length; i++) {
    if (roles.value[i].id === id) {
      index = i;
      break;
    }
  }

  return index;
};
const confirmDeleteSelected = () => {
  deleteRolesDialog.value = true;
};
const deleteSelectedRoles = () => {
  const rolesID = ref<{ id: number }[]>([]);
  selectedRoles.value.forEach((selectedRole: DatumRole) => {
    rolesID.value.push({ id: selectedRole.id });
  });
  store
    .destroyRoles(rolesID.value)
    .then((resp: any) => {
      // console.log(resp);
      if (resp.severity === "warn") {
        roles.value = roles.value.filter(
          (val) => !selectedRoles.value.includes(val as never)
        );
        deleteRolesDialog.value = false;
        selectedRoles.value = [];
        toast.add({
          severity: resp.severity,
          summary: resp.summary,
          detail: resp.detail,
          life: 3000,
        });
      } else if (resp.severity === "warn") {
        deleteRolesDialog.value = false;
        selectedRoles.value = [];
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
  getAllRoles();
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
