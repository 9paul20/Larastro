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
          class="p-datatable-gridlines"
          dataKey="id"
          filterDisplay="row"
          ref="dt"
          removableSort
          responsiveLayout="scroll"
          :selectionOnly="true"
          showGridlines
        >
          <template #header>
            <div style="text-align: left">
              <Button
                icon="pi pi-external-link"
                label="Export"
                @click="exportCSV($event)"
              />
            </div>
          </template>
          <template #empty> No users found. </template>
          <template #loading> Loading users data. Please wait. </template>
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
          <Column field="email" header="Email" style="min-width: 12rem" sortable>
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
        </DataTable>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Datum } from "@js/interfaces/User";
import { getAllUsers } from "@js/stores/Users";
import { onBeforeMount, onMounted, ref } from "vue";
import { FilterMatchMode } from "primevue/api";
//
const dt = ref<any>();
const users = ref<Datum[]>([]);
const loading = ref<boolean>(true);
const filters = ref<{}>({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  email: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});
const exportCSV = () => {
  dt.value.exportCSV();
};
//
onBeforeMount(() => {
  getAllUsers()
    .then((resp: any) => {
      users.value = resp.data;
      loading.value = false;
    })
    .catch((error: string) => {
      console.log(error);
      loading.value = false;
    });
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
