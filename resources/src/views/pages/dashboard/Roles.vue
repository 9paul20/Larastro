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
          exportFilename="Users"
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
              <!-- <Button
                label="Export"
                icon="pi pi-upload"
                severity="help"
                @click="exportCSV($event)"
              /> -->
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
          <template #footer>
            In total there are {{ roles ? roles.length : 0 }} Users.
          </template>
        </DataTable>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onBeforeMount, ref } from "vue";
import { useToast } from "primevue/usetoast";
import { Datum } from "@js/interfaces/Roles/Role";
import { rolesStore } from "@js/stores/Roles";
import { FilterMatchMode } from "primevue/api";
//
const toast = useToast();
const store = rolesStore();
const dt = ref<any>();
const role = ref<Datum>();
const roles = ref<Datum[]>([]);
const loading = ref<boolean>();
const roleDialog = ref<boolean>(false);
const deleteRolesDialog = ref<boolean>(false);
const errors = ref(null);
const selectedRoles = ref<[]>([]);
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
  role.value = {
    id: 0,
    name: "",
  };
  submitted.value = false;
  roleDialog.value = true;
  errors.value = null;
};
const confirmDeleteSelected = () => {
  deleteRolesDialog.value = true;
};
//
const getAllRoles = () => {
  loading.value = true;
  store
    .getAllRoles()
    .then((resp: any) => {
      roles.value = resp.data;
      console.log(roles.value);
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
//
onBeforeMount(() => {
  getAllRoles();
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
