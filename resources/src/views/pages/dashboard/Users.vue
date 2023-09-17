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
              <!-- <FileUpload
              mode="basic"
              accept="image/*"
              :maxFileSize="1000000"
              label="Import"
              chooseLabel="Import"
              class="mr-2 inline-block"
            /> -->
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
          <Column :exportable="false" style="min-width: 8rem">
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
      :style="{ width: '450px' }"
      header="User Details"
      :modal="true"
      class="p-fluid"
    >
      <!-- <img
        v-if="user.image"
        :src="`https://primefaces.org/cdn/primevue/images/product/${user.image}`"
        :alt="user.image"
        class="block m-auto pb-3"
      /> -->
      <InputNumber
        id="id"
        v-model.trim="user.id"
        required="true"
        type="number"
        class="hidden"
        :class="{ 'p-invalid': submitted && !user.name }"
      />
      <div class="field">
        <label for="name">Name</label>
        <InputText
          id="name"
          v-model.trim="user.name"
          required="true"
          autofocus
          type="text"
          placeholder="Write a name"
          :class="{ 'p-invalid': submitted && !user.name }"
        />
        <small class="p-error" v-if="submitted && !user.name">Name is required.</small>
      </div>
      <div class="field">
        <label for="email">Email</label>
        <InputText
          id="email"
          v-model.trim="user.email"
          required="true"
          type="email"
          placeholder="Write a email"
          :class="{ 'p-invalid': submitted && !user.email }"
        />
        <small class="p-error" v-if="submitted && !user.email">Email is required.</small>
      </div>
      <!-- <div class="field">
        <label for="description">Description</label>
        <Textarea
          id="description"
          v-model="user.description"
          required="true"
          rows="3"
          cols="20"
        />
      </div> -->

      <!-- <div class="field">
        <label for="inventoryStatus" class="mb-3">Inventory Status</label>
        <Dropdown
          id="inventoryStatus"
          v-model="product.inventoryStatus"
          :options="statuses"
          optionLabel="label"
          placeholder="Select a Status"
        >
          <template #value="slotProps">
            <div v-if="slotProps.value && slotProps.value.value">
              <Tag
                :value="slotProps.value.value"
                :severity="getStatusLabel(slotProps.value.label)"
              />
            </div>
            <div v-else-if="slotProps.value && !slotProps.value.value">
              <Tag :value="slotProps.value" :severity="getStatusLabel(slotProps.value)" />
            </div>
            <span v-else>
              {{ slotProps.placeholder }}
            </span>
          </template>
        </Dropdown>
      </div> -->

      <!-- <div class="field">
        <label class="mb-3">Category</label>
        <div class="formgrid grid">
          <div class="field-radiobutton col-6">
            <RadioButton
              id="category1"
              name="category"
              value="Accessories"
              v-model="user.category"
            />
            <label for="category1">Accessories</label>
          </div>
          <div class="field-radiobutton col-6">
            <RadioButton
              id="category2"
              name="category"
              value="Clothing"
              v-model="user.category"
            />
            <label for="category2">Clothing</label>
          </div>
          <div class="field-radiobutton col-6">
            <RadioButton
              id="category3"
              name="category"
              value="Electronics"
              v-model="user.category"
            />
            <label for="category3">Electronics</label>
          </div>
          <div class="field-radiobutton col-6">
            <RadioButton
              id="category4"
              name="category"
              value="Fitness"
              v-model="user.category"
            />
            <label for="category4">Fitness</label>
          </div>
        </div>
      </div> -->

      <!-- <div class="formgrid grid">
        <div class="field col">
          <label for="price">Price</label>
          <InputNumber
            id="price"
            v-model="user.price"
            mode="currency"
            currency="USD"
            locale="en-US"
          />
        </div>
        <div class="field col">
          <label for="quantity">Quantity</label>
          <InputNumber id="quantity" v-model="user.quantity" integeronly />
        </div>
      </div> -->
      <template #footer>
        <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
        <Button label="Save" icon="pi pi-check" text @click="saveUser" />
      </template>
    </Dialog>

    <!-- Dialog Delete User -->
    <Dialog
      v-model:visible="deleteUserDialog"
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
      :style="{ width: '450px' }"
      header="Confirm"
      :modal="true"
    >
      <div class="confirmation-content">
        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
        <span v-if="user">Are you sure you want to delete the selected users?</span>
      </div>
      <template #footer>
        <Button label="No" icon="pi pi-times" text @click="deleteUsersDialog = false" />
        <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedUsers" />
      </template>
    </Dialog>

    <Toast />
  </div>
</template>

<script setup lang="ts">
import { Datum } from "@js/interfaces/User";
import { getAllUsers } from "@js/stores/Users";
import { onBeforeMount, ref } from "vue";
import { FilterMatchMode } from "primevue/api";
import { useToast } from "primevue/usetoast";
//
const toast = useToast();
const dt = ref<any>();
const users = ref<Datum[]>([]);
const loading = ref<boolean>(true);
const userDialog = ref<boolean>(false);
const deleteUserDialog = ref<boolean>(false);
const deleteUsersDialog = ref<boolean>(false);
const lastID = ref<number>();
const user = ref<Datum>();
const selectedUsers = ref<[]>();
const submitted = ref<boolean>(false);
const filters = ref<{}>({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  id: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  email: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});
const exportCSV = () => {
  dt.value.exportCSV();
};
const openNew = () => {
  user.value = {
    id: 0,
    name: "",
    email: "",
  };
  submitted.value = false;
  userDialog.value = true;
};
const hideDialog = () => {
  userDialog.value = false;
  submitted.value = false;
};
const saveUser = () => {
  submitted.value = true;

  if (user.value?.name.trim()) {
    // Update User
    if (user.value.id) {
      // user.value.inventoryStatus = user.value.inventoryStatus.value
      //   ? user.value.inventoryStatus.value
      //   : user.value.inventoryStatus;
      users.value[findIndexById(user.value.id)] = user.value;
      toast.add({
        severity: "success",
        summary: "Successful",
        detail: "User Updated",
        life: 3000,
      });
    }
    // Add User
    else {
      lastID.value += 1;
      user.value.id = lastID.value;
      // user.value.id = createId();
      // user.value.code = createId();
      // user.value.image = "product-placeholder.svg";
      // user.value.inventoryStatus = user.value.inventoryStatus
      //   ? user.value.inventoryStatus.value
      //   : "INSTOCK";
      users.value.push(user.value);
      toast.add({
        severity: "success",
        summary: "Successful",
        detail: "User Created",
        life: 3000,
      });
    }

    userDialog.value = false;
    user.value = {
      id: 0,
      name: "",
      email: "",
    };
  }
};
const editUser = (prod: Datum) => {
  user.value = { ...prod };
  userDialog.value = true;
};
const confirmDeleteUser = (prod: Datum) => {
  user.value = prod;
  deleteUserDialog.value = true;
};
const deleteUser = () => {
  if (user.value && user.value.id) {
    users.value = users.value.filter((val) => val.id !== user.value?.id);
  }
  deleteUserDialog.value = false;
  user.value = {
    id: 0,
    name: "",
    email: "",
  };
  toast.add({
    severity: "success",
    summary: "Successful",
    detail: "User Deleted",
    life: 3000,
  });
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
const createId = () => {
  let id = "";
  var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  for (var i = 0; i < 5; i++) {
    id += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return id;
};
const confirmDeleteSelected = () => {
  deleteUsersDialog.value = true;
};
const deleteSelectedUsers = () => {
  users.value = users.value.filter((val) => !selectedUsers.value.includes(val));
  deleteUsersDialog.value = false;
  selectedUsers.value = [];
  toast.add({
    severity: "success",
    summary: "Successful",
    detail: "Users Deleted",
    life: 3000,
  });
};
//
onBeforeMount(() => {
  getAllUsers()
    .then((resp: any) => {
      users.value = resp.data;
      lastID.value = users.value[users.value.length - 1].id;
      loading.value = false;
    })
    .catch((error: string) => {
      console.log(error);
      loading.value = false;
    });
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
