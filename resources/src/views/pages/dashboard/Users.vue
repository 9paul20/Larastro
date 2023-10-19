<template>
  <div class="grid">
    <div class="col-12">
      <div class="card p-fluid">
        <h1>Users</h1>
        <DataTable
          :loading="loading"
          :paginator="true"
          :rows="10"
          :rowsPerPageOptions="[5, 10, 20, 50, 100]"
          :rowHover="true"
          :value="users"
          v-model:filters="filters"
          v-model:selection="selectedUsers"
          class="p-datatable-gridlines"
          dataKey="id"
          filterDisplay="row"
          exportFilename="Users"
          ref="dt"
          removableSort
          responsiveLayout="scroll"
          :selectionOnly="true"
          showGridlines
        >
          <template #paginatorstart>
            <Button type="button" icon="pi pi-refresh" text @click="getAllUsers()" />
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
                :disabled="!selectedUsers || !selectedUsers.length"
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
          <template #empty> No users found. </template>
          <template #loading> Loading users data. Please wait. </template>
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
          <Column field="email" header="Email" style="" sortable>
            <template #body="{ data }">
              {{ data.email }}
            </template>
            <template #filter="{ filterModel, filterCallback }">
              <InputText
                v-model="filterModel.value"
                type="text"
                class="p-column-filter"
                @input="filterCallback()"
                placeholder="Search by Email"
              />
            </template>
          </Column>
          <Column field="roles" header="Roles" sortable style="">
            <template #body="{ data }">
              <div
                v-if="data.roles && data.roles.length > 0"
                class="flex flex-wrap justify-content-center gap-2"
              >
                <template v-for="(role, index) in data.roles.slice(0, 4)">
                  <Tag
                    icon="pi pi-user"
                    :class="{ 'p-mr-2': index !== data.roles.slice(0, 4).length - 1 }"
                    severity="info"
                  >
                    {{ role.name }}
                  </Tag>
                </template>
                <template v-if="data.roles.length > 4">
                  <Tag icon="pi pi-plus" severity="info">{{ data.roles.length - 4 }}</Tag>
                </template>
              </div>
              <div v-else class="flex flex-wrap justify-content-center gap-2">
                <Tag icon="pi pi-times" severity="danger">Without Roles</Tag>
              </div>
            </template>
          </Column>
          <Column field="permissions" header="Permissions" sortable style="">
            <template #body="{ data }">
              <div
                v-if="data.permissions && data.permissions.length > 0"
                class="flex flex-wrap justify-content-center gap-2"
              >
                <template v-for="(permission, index) in data.permissions.slice(0, 4)">
                  <Tag
                    icon="pi pi-user"
                    :class="{
                      'p-mr-2': index !== data.permissions.slice(0, 4).length - 1,
                    }"
                    severity="success"
                  >
                    {{ permission.name }}
                  </Tag>
                </template>
                <template v-if="data.permissions.length > 4">
                  <Tag icon="pi pi-plus" severity="success">{{
                    data.permissions.length - 4
                  }}</Tag>
                </template>
              </div>
              <div v-else class="flex flex-wrap justify-content-center gap-2">
                <Tag icon="pi pi-times" severity="danger">Without Permissions</Tag>
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
                @click="editUser(slotProps.data)"
              />
              <Button
                icon="pi pi-trash"
                outlined
                rounded
                severity="danger"
                @click="confirmDeleteUser(slotProps.data)"
              />
            </template>
          </Column>
          <template #footer>
            In total there are {{ users ? users.length : 0 }} Users.
          </template>
        </DataTable>
      </div>
    </div>
    <!-- Dialog Create User -->
    <Dialog
      v-model:visible="userDialog"
      :closable="false"
      :style="{ width: '500px' }"
      header="User Details"
      :modal="true"
      class="p-fluid"
    >
      <!-- <Fieldset legend="User Information"> -->
      <InputNumber
        id="id"
        v-model.trim="user.id"
        required="true"
        type="number"
        class="hidden"
      />
      <div class="grid formgrid">
        <div class="field col-12 mb-2">
          <label for="name">Name</label>
          <InputText
            id="name"
            v-model.trim="user.name"
            required="true"
            autofocus
            type="text"
            placeholder="Write a name"
            :class="{
              'p-invalid': (submitted && !user?.name) || errors?.name,
            }"
            @blur="hideErrors('name')"
          />
          <small class="p-error" v-if="submitted && !user?.name">Name is required.</small>
          <div v-if="errors?.name">
            <div v-for="(errorName, indexName) in errors.name" :key="indexName">
              <small class="p-error">{{ errorName }}</small>
            </div>
          </div>
        </div>
        <div class="field col-12 mb-2">
          <label for="email">Email</label>
          <InputText
            id="email"
            v-model.trim="user.email"
            required="true"
            type="email"
            placeholder="Write a email"
            :class="{ 'p-invalid': (submitted && !user.email) || errors?.email }"
            @blur="hideErrors('email')"
          />
          <small class="p-error" v-if="submitted && !user?.email"
            >Email is required.</small
          >
          <div v-if="errors?.email">
            <div v-for="(errorEmail, indexEmail) in errors.email" :key="indexEmail">
              <small class="p-error">{{ errorEmail }}</small>
            </div>
          </div>
        </div>
        <div class="field col-12 mb-2 lg:col-6">
          <label for="password">Password</label>
          <InputText
            id="password"
            v-model.trim="user.password"
            :required="passwordRequired"
            type="password"
            placeholder="Write a password"
            :class="{
              'p-invalid':
                (submitted && !user.password && passwordRequired) || errors?.password,
            }"
            @blur="hideErrors('password')"
          />
          <small class="p-error" v-if="submitted && !user?.password && passwordRequired"
            >Password is required.</small
          >
          <div v-if="errors?.password">
            <div
              v-for="(errorPassword, indexPassword) in errors.password"
              :key="indexPassword"
            >
              <small class="p-error">{{ errorPassword }}</small>
            </div>
          </div>
        </div>
        <div class="field col-12 mb-2 lg:col-6">
          <label for="password_confirmation">Repeat Password</label>
          <InputText
            id="password_confirmation"
            v-model.trim="user.password_confirmation"
            :required="passwordRequired"
            type="password"
            placeholder="Repeat a password"
            :class="{
              'p-invalid':
                (submitted && !user?.password_confirmation && passwordRequired) ||
                errors?.password_confirmation,
            }"
            @blur="hideErrors('password_confirmation')"
          />
          <small
            class="p-error"
            v-if="submitted && !user?.password_confirmation && passwordRequired"
            >Password is required.</small
          >
          <div v-if="errors?.password_confirmation">
            <div
              v-for="(
                errorPasswordConfirmation, indexPasswordConfirmation
              ) in errors.password_confirmation"
              :key="indexPasswordConfirmation"
            >
              <small class="p-error">{{ errorPasswordConfirmation }}</small>
            </div>
          </div>
        </div>
      </div>
      <!-- </Fieldset> -->

      <h5>Roles</h5>
      <div class="card px-3 py-3">
        <div class="grid">
          <div class="col-12 md:col-3" v-for="role in roles">
            <div class="field-checkbox mb-0">
              <Checkbox
                :id="role.name"
                :name="role.name"
                :value="role.name"
                v-model="checkboxRoles"
              />
              <label :for="role.name">{{ role.name }}</label>
            </div>
          </div>
        </div>
      </div>

      <h5>Permissions</h5>
      <div class="card px-3 py-3">
        <div
          v-for="(catalogPermission, indexCatalogPermission) in catalogPermissions"
          :key="indexCatalogPermission"
        >
          <div class="flex">
            <h5>For {{ catalogPermission }}s</h5>
            <InputSwitch
              v-model="switchPermissions[catalogPermission + 'sSwitchPermissions']"
              class="ml-auto custom-margin-right"
            />
          </div>
          <div class="grid">
            <div
              class="col-12 md:col-3"
              v-for="(permission, indexPermission) in categorizedPermissions[
                catalogPermission + 'sPermissions'
              ]"
              :key="indexPermission"
            >
              <div class="field-checkbox mb-0">
                <Checkbox
                  :id="permission"
                  :name="permission"
                  :value="permission"
                  v-model="checkboxPermissions"
                />
                <label :for="permission">{{ permission }}</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <template #footer>
        <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
        <Button label="Save" icon="pi pi-check" text @click="saveUser" />
      </template>
    </Dialog>

    <!-- Dialog Delete User -->
    <Dialog
      v-model:visible="deleteUserDialog"
      :closable="false"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="user"
          >Are you sure you want to delete <b>{{ user.name }}</b
          >?</span
        >
      </div>
      <template #footer>
        <Button label="No" icon="pi pi-times" text @click="deleteUserDialog = false" />
        <Button label="Yes" icon="pi pi-check" text @click="deleteUser" />
      </template>
    </Dialog>

    <!-- Dialog Delete Users -->
    <Dialog
      v-model:visible="deleteUsersDialog"
      :closable="false"
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="users">Are you sure you want to delete the selected users?</span>
      </div>
      <template #footer>
        <Button label="No" icon="pi pi-times" text @click="deleteUsersDialog = false" />
        <Button
          label="Yes"
          icon="pi pi-check"
          text
          v-if="users"
          @click="deleteSelectedUsers"
        />
      </template>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { onBeforeMount, ref, watch } from "vue";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "primevue/api";
import { usersStore } from "@js/stores/Users";
import { DatumUser, PermissionUser, RoleUser } from "@js/interfaces/Users/User";
import { UserLastID } from "@js/interfaces/index";
import { rolesStore } from "@js/stores/Roles";
import { permissionsStore } from "@js/stores/Permissions";
//
const toast = useToast();
const store = usersStore();
const storeRoles = rolesStore();
const storePermissions = permissionsStore();
const dt = ref<any>();
const user = ref<DatumUser>();
const users = ref<DatumUser[]>([]);
const roles = ref<RoleUser[]>([]);
const permissions = ref<PermissionUser[]>([]);
const catalogPermissions = ref<string[]>(["User", "Role", "Permission"]);
const categorizedPermissions = ref<Record<string, string[]>>({});
const switchPermissions = ref<Record<string, boolean>>({});
catalogPermissions.value.forEach((catalogPermission) => {
  categorizedPermissions.value[catalogPermission + "sPermissions"] = [];
  switchPermissions.value[catalogPermission + "sSwitchPermissions"] = false;
});
const checkboxPermissions = ref<string[]>([]);
const checkboxRoles = ref<string[]>([]);
const loading = ref<boolean>();
const userDialog = ref<boolean>(false);
const deleteUserDialog = ref<boolean>(false);
const deleteUsersDialog = ref<boolean>(false);
const lastID = ref<UserLastID>();
const passwordRequired = ref<boolean>();
const errors = ref(null);
const selectedUsers = ref<[]>([]);
const submitted = ref<boolean>(false);
const filters = ref<{}>({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  email: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});
const exportCSV = (event: any) => {
  dt.value.exportCSV();
};
const openNew = () => {
  user.value = {
    id: 0,
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    permissions: [],
    roles: [],
  };
  checkboxPermissions.value = [];
  checkboxRoles.value = [];
  catalogPermissions.value.forEach((catalogPermission) => {
    switchPermissions.value[catalogPermission + "sSwitchPermissions"] = false;
  });
  submitted.value = false;
  userDialog.value = true;
  errors.value = null;
};
const hideDialog = () => {
  userDialog.value = false;
  submitted.value = false;
  errors.value = null;
  user.value = {
    id: 0,
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    permissions: [],
    roles: [],
  };
  checkboxPermissions.value = [];
  checkboxRoles.value = [];
  catalogPermissions.value.forEach((catalogPermission) => {
    switchPermissions.value[catalogPermission + "sSwitchPermissions"] = false;
  });
};
const hideErrors = (field: string) => {
  if (errors.value && field in errors.value) {
    errors.value[field] = null as never;
  }
};
const getAllUsers = () => {
  loading.value = true;
  store
    .getAllUsers()
    .then((resp: any) => {
      users.value = resp.data;
      loading.value = false;
    })
    .catch((error: string) => {
      console.error(error);
      loading.value = false;
      toast.add({
        severity: "error",
        summary: "Error",
        detail: "Can't get users list: " + error,
        life: 3000,
      });
    });
};
const getAllRoles = () => {
  storeRoles
    .getAllRoles()
    .then((resp: any) => {
      if (resp.data) {
        roles.value = resp.data;
        loading.value = false;
        // console.log("ROLES SON:", roles.value);
      }
    })
    .catch((error: string) => {
      console.error(error);
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
      if (resp.data) {
        permissions.value = resp.data;
        loading.value = false;
        permissions.value.forEach((permission) => {
          permission.tags.forEach((tag) => {
            catalogPermissions.value.forEach((catalogPermission) => {
              if (tag === catalogPermission) {
                categorizedPermissions.value[catalogPermission + "sPermissions"].push(
                  permission.name
                );
              }
            });
          });
        });
      }
    })
    .catch((error: string) => {
      console.error(error);
      toast.add({
        severity: "error",
        summary: "Error",
        detail: "Can't get roles list: " + error,
        life: 3000,
      });
    });
};
const saveUser = () => {
  submitted.value = true;
  passwordRequired.value = user.value?.id === 0 ? true : false;

  if (user.value?.name.trim() && user.value?.email.trim()) {
    if (checkboxPermissions.value.length > 0) {
      user.value.permissions = checkboxPermissions.value.map((permissionName) => {
        return {
          name: permissionName,
        };
      });
    } else {
      user.value.permissions = [];
    }
    if (checkboxRoles.value.length > 0) {
      user.value.roles = checkboxRoles.value.map((roleName) => {
        return {
          name: roleName,
        };
      });
    } else {
      user.value.roles = [];
    }

    // Create User
    if (passwordRequired.value) {
      if (user.value?.password.trim() && user.value?.password_confirmation.trim()) {
        store
          .storeUser(user.value)
          .then((respStore: any) => {
            //console.log(resp);
            if (respStore && respStore.severity === "success") {
              store
                .getCurrentUserId()
                .then((respGetId: any) => {
                  lastID.value = respGetId;
                  user.value.id = lastID.value?.nextId;
                  users.value.push(user.value as DatumUser);
                  user.value = {
                    id: 0,
                    name: "",
                    email: "",
                    password: "",
                    password_confirmation: "",
                    permissions: [],
                    roles: [],
                  };
                  checkboxPermissions.value = [];
                  checkboxRoles.value = [];
                  catalogPermissions.value.forEach((catalogPermission) => {
                    switchPermissions.value[
                      catalogPermission + "sSwitchPermissions"
                    ] = false;
                  });
                  userDialog.value = false;
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
                    detail: "Can't get last id of users: " + error,
                    life: 3000,
                  });
                });
            } else if (respStore.response.status === 422) {
              toast.add({
                severity: respStore.response.data.severity,
                summary: respStore.response.data.summary,
                detail:
                  respStore.response.data.detail + " " + respStore.response.data.name,
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
    }
    // Update User
    else {
      store
        .updateUser(user.value)
        .then((resp: any) => {
          if (resp && resp.severity === "info") {
            users.value[findIndexById(user.value.id)] = user.value;
            userDialog.value = false;
            user.value = {
              id: 0,
              name: "",
              email: "",
              password: "",
              password_confirmation: "",
              permissions: [],
              roles: [],
            };
            checkboxPermissions.value = [];
            checkboxRoles.value = [];
            catalogPermissions.value.forEach((catalogPermission) => {
              switchPermissions.value[catalogPermission + "sSwitchPermissions"] = false;
            });
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
const editUser = (prod: DatumUser) => {
  user.value = { ...prod };
  user.value.permissions.forEach((permission) => {
    checkboxPermissions.value.push(permission.name);
  });
  user.value.roles.forEach((role) => {
    checkboxRoles.value.push(role.name);
  });
  userDialog.value = true;
  submitted.value = false;
  errors.value = null;
};
const confirmDeleteUser = (prod: DatumUser) => {
  user.value = prod;
  deleteUserDialog.value = true;
};
const deleteUser = () => {
  if (user.value) {
    store
      .destroyUser(user.value)
      .then((resp: any) => {
        if (resp.severity === "warn") {
          users.value = users.value.filter((val) => val.id !== user.value?.id);
          user.value = {
            id: 0,
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
            permissions: [],
            roles: [],
          };
          deleteUserDialog.value = false;
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
  for (let i = 0; i < users.value.length; i++) {
    if (users.value[i].id === id) {
      index = i;
      break;
    }
  }

  return index;
};
const confirmDeleteSelected = () => {
  deleteUsersDialog.value = true;
};
const deleteSelectedUsers = () => {
  const usersID = ref<{ id: number }[]>([]);
  selectedUsers.value.forEach((selectedUser: DatumUser) => {
    usersID.value.push({ id: selectedUser.id });
  });
  store
    .destroyUsers(usersID.value)
    .then((resp: any) => {
      if (resp.severity === "warn") {
        users.value = users.value.filter(
          (val) => !selectedUsers.value.includes(val as never)
        );
        deleteUsersDialog.value = false;
        selectedUsers.value = [];
        toast.add({
          severity: resp.severity,
          summary: resp.summary,
          detail: resp.detail,
          life: 3000,
        });
      } else if (resp.severity === "warn") {
        deleteUsersDialog.value = false;
        selectedUsers.value = [];
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
  //Antes de que se monte el componente se obtienen todos los usuarios
  getAllUsers();
  getAllRoles();
  getAllPermissions();
});
//
watch(switchPermissions.value, (newValues) => {
  const selectedPermissions = <string[]>[];
  Object.keys(newValues).forEach((key) => {
    const value: boolean = newValues[key];
    const permissionType = key.replace("SwitchPermissions", "Permissions");
    const permissions = categorizedPermissions.value[permissionType];

    if (value) {
      selectedPermissions.push(...permissions);
    }
  });

  checkboxPermissions.value = selectedPermissions;
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
