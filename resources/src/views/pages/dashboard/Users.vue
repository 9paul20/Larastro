<template>
    <div class="grid">
        <div class="col-12">
            <div class="card p-fluid">
                <h1>Users</h1>
                <DataTable
                    :value="users"
                    :paginator="true"
                    showGridlines
                    class="p-datatable-gridlines"
                    :rows="10"
                    :rowsPerPageOptions="[5, 10, 20, 50, 100]"
                    removableSort
                    dataKey="id"
                    :rowHover="true"
                    v-model:filters="filters"
                    filterDisplay="row"
                    :loading="loading"
                    responsiveLayout="scroll"
                >
                    <template #empty> No users found. </template>
                    <template #loading>
                        Loading users data. Please wait.
                    </template>
                    <Column
                        field="id"
                        header="ID"
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
                                @input="filterCallback()"
                                class="p-column-filter"
                                placeholder="Search by ID"
                            />
                        </template>
                    </Column>
                    <Column
                        field="name"
                        header="Name"
                        sortable
                        style="min-width: 12rem"
                    >
                        <template #body="{ data }">
                            {{ data.name }}
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <InputText
                                v-model="filterModel.value"
                                type="text"
                                @input="filterCallback()"
                                class="p-column-filter"
                                placeholder="Search by Name"
                            />
                        </template>
                    </Column>
                    <Column
                        field="email"
                        header="Email"
                        style="min-width: 12rem"
                        sortable
                    >
                        <template #body="{ data }">
                            {{ data.email }}
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <InputText
                                type="text"
                                v-model="filterModel.value"
                                @input="filterCallback()"
                                class="p-column-filter"
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
import { FilterMatchMode } from "primevue/api";
import { Datum } from "@js/interfaces/User";
import { getAllUsers } from "@js/stores/Users";
import { onBeforeMount, ref } from "vue";
//
const users = ref<Datum[]>([]);
const filters = ref({
    global: {
        value: null,
        matchMode: FilterMatchMode.CONTAINS,
    },
    id: {
        value: null,
        matchMode: FilterMatchMode.STARTS_WITH,
    },
    name: {
        value: null,
        matchMode: FilterMatchMode.STARTS_WITH,
    },
    email: {
        value: null,
        matchMode: FilterMatchMode.STARTS_WITH,
    },
});
const loading = ref<boolean>(true);
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
