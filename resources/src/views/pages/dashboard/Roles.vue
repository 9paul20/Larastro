<template>
  <div class="grid">
    <!-- DataTable -->
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
            <Button
              type="button"
              icon="pi pi-refresh"
              text
              @click="getAllRoles(), getAllTags(), getAllPermissions()"
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
                <template v-for="(tag, index) in data.tags.slice(0, 4)">
                  <Tag
                    icon="pi pi-user"
                    :class="{ 'p-mr-2': index !== data.tags.slice(0, 4).length - 1 }"
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
          <Column field="permissions" header="Permissions" sortable style="">
            <template #body="{ data }">
              <div
                v-if="data.permissions && data.permissions.length > 0"
                class="flex flex-wrap justify-content-center gap-2"
              >
                <template v-for="(permission, index) in data.permissions.slice(0, 4)">
                  <Tag
                    icon="pi pi-check"
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
      :closable="false"
      :style="{ width: '500px' }"
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
        <Textarea
          id="description"
          v-model.trim="role.description"
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
          <label for="tags">Tags</label>
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
            @click="openNewTag"
          >
            <span class="p-button-icon p-c pi pi-plus"></span>
          </button>
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
        <Button label="Save" icon="pi pi-check" text @click="saveRole" />
      </template>
    </Dialog>

    <addTagDialog
      :tagDialog="tagDialog"
      @closeDialog="handleDialogClose"
      @updateListTags="updateListTags"
    />

    <!-- Dialog Delete Role -->
    <Dialog
      v-model:visible="deleteRoleDialog"
      :closable="false"
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
      :closable="false"
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
import { onBeforeMount, ref, watch } from "vue";
import { FilterMatchMode } from "primevue/api";
import { useToast } from "primevue/usetoast";
import { rolesStore } from "@js/stores/Roles";
import { tagsStore } from "@js/stores/Tags";
import { permissionsStore } from "@js/stores/Permissions";
import { DatumRole, PermissionRole, TagRole } from "@js/interfaces/Roles/Role";
import { DatumTag } from "@js/interfaces/Tags/Tag";
import { DatumPermission } from "@js/interfaces/Permissions/Permission";
import { RoleLastID } from "@js/interfaces/index";
import addTagDialog from "@src/components/addTagDialog.vue";
//
const toast = useToast();
const store = rolesStore();
const storeTags = tagsStore();
const storePermissions = permissionsStore();
const dt = ref<any>();
const role = ref<DatumRole>();
const roles = ref<DatumRole[]>([]);
const tags = ref<DatumTag[]>([]);
const selectedTags = ref<[]>([]);
const permissions = ref<PermissionRole[]>([]);
const catalogPermissions = ref<string[]>(["User", "Tag", "Role", "Permission"]);
const categorizedPermissions = ref<Record<string, string[]>>({});
const switchPermissions = ref<Record<string, boolean>>({});
catalogPermissions.value.forEach((catalogPermission) => {
  categorizedPermissions.value[catalogPermission + "sPermissions"] = [];
  switchPermissions.value[catalogPermission + "sSwitchPermissions"] = false;
});
const checkboxPermissions = ref<string[]>([]);
const loading = ref<boolean>();
const roleDialog = ref<boolean>(false);
const tagDialog = ref<boolean>(false);
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
  name: { value: null, matchMode: FilterMatchMode.CONTAINS },
  description: { value: null, matchMode: FilterMatchMode.CONTAINS },
  permissions: { value: null, matchMode: FilterMatchMode.CONTAINS },
  tags: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
//
const exportCSV = (event: any) => {
  dt.value.exportCSV();
};
const openNew = () => {
  role.value = {
    id: 0,
    name: "",
    description: "",
    permissions: [],
    tags: [],
  };
  selectedTags.value = [];
  checkboxPermissions.value = [];
  catalogPermissions.value.forEach((catalogPermission) => {
    switchPermissions.value[catalogPermission + "sSwitchPermissions"] = false;
  });
  submitted.value = false;
  roleDialog.value = true;
  errors.value = null;
};
const openNewTag = () => {
  roleDialog.value = false;
  tagDialog.value = true;
};
const handleDialogClose = (value) => {
  tagDialog.value = !value;
  roleDialog.value = value; // También actualizamos el estado de permissionDialog
};
const updateListTags = (value) => {
  if (value) getAllTags();
};
const hideDialog = () => {
  roleDialog.value = false;
  submitted.value = false;
  errors.value = null;
  role.value = {
    id: 0,
    name: "",
    description: "",
    permissions: [],
    tags: [],
  };
  selectedTags.value = [];
  checkboxPermissions.value = [];
  catalogPermissions.value.forEach((catalogPermission) => {
    switchPermissions.value[catalogPermission + "sSwitchPermissions"] = false;
  });
};
const hideErrors = (field: string) => {
  if (errors.value && (field in errors.value || field + "." in errors.value)) {
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
      roles.value = resp.data.map((role: DatumRole) => {
        return {
          ...role,
          tags: role.tags.map((tag) => ({ id: tag.id, name: tag.name })),
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
        detail: "Can't get roles list: " + error,
        life: 3000,
      });
    });
};
const getAllTags = () => {
  loading.value = true;
  storeTags
    .getAllTags()
    .then((resp: any) => {
      tags.value = resp.data.map((tag: TagRole) => ({
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
const getAllPermissions = () => {
  storePermissions
    .getAllPermissions()
    .then((resp: any) => {
      permissions.value = resp.data.map((permission: DatumPermission) => {
        return {
          ...permission,
          tags: permission.tags.map((tag) => ({ id: tag.id, name: tag.name })),
        };
      });
      loading.value = false;
      permissions.value.forEach((permission) => {
        permission.tags.forEach((tag) => {
          catalogPermissions.value.forEach((catalogPermission) => {
            if (tag.name === catalogPermission) {
              categorizedPermissions.value[catalogPermission + "sPermissions"].push(
                permission.name
              );
            }
          });
        });
      });
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
    if (checkboxPermissions.value.length > 0) {
      role.value.tags = selectedTags.value;
      role.value.permissions = checkboxPermissions.value.map((permissionName) => {
        return {
          name: permissionName,
        };
      });
    } else {
      role.value.permissions = [];
    }

    // Create Role
    if (createOrUpdate.value) {
      store
        .storeRole(role.value)
        .then((respStore: any) => {
          if (respStore && respStore.severity === "success") {
            store
              .getCurrentRoleId()
              .then((respGetId: any) => {
                lastID.value = respGetId;
                roleDialog.value = false;
                role.value.id = lastID.value?.nextId;
                roles.value.push(role.value as DatumRole);
                role.value = {
                  id: 0,
                  name: "",
                  description: "",
                  permissions: [],
                  tags: [],
                };
                selectedTags.value = [];
                checkboxPermissions.value = [];
                catalogPermissions.value.forEach((catalogPermission) => {
                  switchPermissions.value[
                    catalogPermission + "sSwitchPermissions"
                  ] = false;
                });
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
              detail: respStore.response.data.detail,
              life: 3000,
            });
            console.error(respStore.response);
            if (respStore.response.data) {
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
          if (resp && resp.severity === "info") {
            roles.value[findIndexById(role.value.id)] = role.value;
            roleDialog.value = false;
            role.value = {
              id: 0,
              name: "",
              description: "",
              permissions: [],
              tags: [],
            };
            selectedTags.value = [];
            checkboxPermissions.value = [];
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
const editRole = (prod: DatumRole) => {
  role.value = { ...prod };
  role.value.tags.forEach((tag) => {
    selectedTags.value.push(tag);
  });
  role.value.permissions.forEach((permission) => {
    checkboxPermissions.value.push(permission.name);
  });
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
        if (resp.severity === "warn") {
          roles.value = roles.value.filter((val) => val.id !== role.value?.id);
          role.value = {
            id: 0,
            name: "",
            description: "",
            permissions: [],
            tags: [],
          };
          selectedTags.value = [];
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
//
onBeforeMount(() => {
  getAllRoles();
  getAllTags();
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
