<template>
    <div>
        <Head title="Employee" />
        <AuthenticatedLayout>
            <template #header>
                <h2
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
                >
                    Employees
                </h2>
            </template>

            <div class="xl:p-12 pt-10 xl:mx-10 overflow-scroll">
                <div class="pl-5">
                    <a-button
                        class="editable-add-btn dark:text-white pb-5font-bold border"
                        @click="addEmployee"
                    >
                        Add Employee
                    </a-button>
                </div>
                <a-table
                    class="dark:bg-slate-300 dark:text-white p-5 rounded-lg overflow-hidden"
                    :dataSource="employees.data"
                    :columns="columns"
                    :pagination="paginationConfig"
                    :filterSearch="true"
                >
                    <template #bodyCell="{ column, record, index }">
                        <template v-if="column.key === 'action'">
                            <span>
                                <a
                                    @click.prevent="editEmployee(record)"
                                    class="mr-2 text-blue-600 hover:text-blue-900 hover:bg-blue-200 px-4 py-1 border border-blue-600 rounded-xl"
                                    >Edit</a
                                >
                                <a
                                    class="text-red-600 hover:text-red-900 px-4 py-1 hover:bg-red-200 border border-red-600 rounded-xl"
                                    @click.prevent="deleteEmployee(record.id)"
                                    >Delete</a
                                >
                            </span>
                        </template>
                        <template v-if="column.key === 'company'">
                            <span
                                class="px-2 py-1 text-xs text-green-700 rounded-xl cursor-pointer hover:bg-green-700 hover:text-white"
                                @click.prevent="
                                    handleShowCompany(record.company)
                                "
                            >
                                {{ record.company.name }}
                            </span>
                        </template>
                        <template v-else-if="column.key === 'index'">
                            {{
                                (meta.current_page - 1) * meta.per_page +
                                index +
                                1
                            }}
                        </template>
                    </template>
                </a-table>
            </div>
            <contextHolder />
            <a-modal
                v-model:open="openModal"
                :title="modalTitle"
                @ok="handleSubmit"
            >
                <template #footer>
                    <a-button key="back" @click="handleCancel">Cancel</a-button>
                    <a-button
                        class="bg-blue-800 text-white font-bold"
                        key="submit"
                        type="primary"
                        :loading="false"
                        @click="handleSubmit"
                        >Submit</a-button
                    >
                </template>
                <div class="mb-10">
                    <form @submit.prevent="handleSubmit">
                        <div>
                            <InputLabel for="first_name" value="First Name" />

                            <TextInput
                                id="first_name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.first_name"
                                required
                                autofocus
                                autocomplete="first_name"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.first_name"
                            />
                        </div>

                        <div class="my-6">
                            <InputLabel for="last_name" value="Last Name" />

                            <TextInput
                                id="last_name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.last_name"
                                required
                                autocomplete="last_name"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.last_name"
                            />
                        </div>

                        <div class="my-6">
                            <InputLabel for="company_id" value="Company ID" />

                            <SelectCompany
                                v-model="form.company_id"
                                name="company_id"
                                :companies="companies"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.company_id"
                            />
                        </div>

                        <div class="my-6">
                            <InputLabel for="email" value="Email" />

                            <TextInput
                                type="email"
                                v-model="form.email"
                                name="email"
                                id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            />

                            <div
                                v-if="form.errors.email"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <div class="my-6">
                            <InputLabel for="phone" value="Phone" />

                            <TextInput
                                type="text"
                                v-model="form.phone"
                                name="phone"
                                id="phone"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            />

                            <div
                                v-if="form.errors.phone"
                                class="text-sm text-red-600"
                            >
                                {{ form.errors.phone }}
                            </div>
                        </div>
                    </form>
                </div>
            </a-modal>
            <div>
                <a-modal
                    v-model:open="showCompany"
                    title="Company Detail"
                    @ok="handleSubmit"
                >
                    <template #footer>
                        <a-button
                            class="bg-blue-800 text-white font-bold"
                            key="submit"
                            type="primary"
                            :loading="false"
                            @click="showCompany = false"
                            >OK</a-button
                        >
                    </template>
                    <div class="my-5">
                        <div>
                            <InputLabel for="name" value="Name" />

                            <div>
                                {{ company?.name }}
                            </div>
                        </div>

                        <div class="my-6">
                            <label
                                for="content"
                                class="block mb-2 text-sm font-medium text-gray-900"
                                >Email</label
                            >
                            <div>
                                {{ company?.email }}
                            </div>
                        </div>
                        <div class="my-6">
                            <label
                                for="content"
                                class="block mb-2 text-sm font-medium text-gray-900"
                                >Website</label
                            >
                            <div>
                                {{ company?.website }}
                            </div>
                        </div>
                    </div>
                </a-modal>
            </div>
        </AuthenticatedLayout>
    </div>
</template>

<script setup lang="ts">
import { defineProps, ref, h } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { Modal } from "ant-design-vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";

import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SelectCompany from "@/Components/SelectCompany.vue";

const openModal = ref<boolean>(false);
const modalTitle = ref<string>("");

const { employees, companies, meta } = defineProps([
    "employees",
    "companies",
    "meta",
]);
const [modal, contextHolder] = Modal.useModal();

interface Employee {
    id: any | null;
    first_name: string;
    last_name: string;
    company_id: number | null;
    email: string | null;
    phone: string | null;
}

interface Company {
    id: number | null;
    name: string;
    email: string | null;
    website: string | null;
    logo?: string | null;
}
const company = ref<Company>();

const showCompany = ref<boolean>(false);

const columns = ref([
    { title: "#", key: "index" },
    { title: "ID", dataIndex: "id", key: "id" },
    { title: "Full Name", dataIndex: "name", key: "name" },
    // { title: "First Name", dataIndex: "first_name", key: "first_name" },
    // { title: "Last Name", dataIndex: "last_name", key: "last_name" },
    { title: "Company", dataIndex: "company", key: "company" },
    { title: "Email", dataIndex: "email", key: "email" },
    { title: "Phone", dataIndex: "phone", key: "phone" },
    { title: "Action", key: "action" },
]);

const paginationConfig = {
    current: meta.current_page,
    pageSize: meta.per_page,
    total: meta.total,
    showSizeChanger: true,
    pageSizeOptions: ["10", "20", "50", "100"],
    onChange: (page: number, pageSize: number) => {
        fetchDataForPage(page, pageSize);
    },
};

const fetchDataForPage = async (page: number, pageSize: number) => {
    router.visit(route("employees.index", { page }), {
        only: ["employees", "meta"],
        preserveScroll: true,
    });
};

const addEmployee = () => {
    form.clearErrors();
    form.reset();
    openModal.value = true;
    modalTitle.value = "Add Employee";
};

const handleSubmit = () => {
    const submitUrl = form.id
        ? route("employees.update", form.id)
        : route("employees.store");
    const submitMethod = form.id ? "put" : "post";
    form[submitMethod](submitUrl, {
        preserveScroll: true,
        onSuccess: () => {
            openModal.value = false;
            router.reload();
            form.reset();
        },
    });
};

const handleCancel = () => {
    openModal.value = false;
};

const editEmployee = (employee: Employee) => {
    modalTitle.value = "Edit Employee";
    form.id = employee.id;
    form.first_name = employee.first_name;
    form.last_name = employee.last_name;
    form.company_id = employee.company_id;
    form.email = employee.email;
    form.phone = employee.phone;
    openModal.value = true;
};

const deleteEmployee = (id: number) => {
    modal.confirm({
        title: "Are you sure you want to delete this Employee?",
        icon: h(ExclamationCircleOutlined),
        content: "This action cannot be undone.",
        okText: "Yes",
        okType: "danger",
        cancelText: "No",
        onOk() {
            router.delete(route("employees.destroy", { id }), {
                preserveScroll: true,
                onSuccess: () => {
                    router.reload();
                },
            });
        },
        onCancel() {},
    });
};

const handleShowCompany = (com: Company) => {
    company.value = {
        id: com.id,
        name: com.name,
        email: com.email,
        website: com.website,
    };
    showCompany.value = true;
};

const form = useForm<Employee>({
    id: null,
    first_name: "",
    last_name: "",
    company_id: null,
    email: null,
    phone: null,
});
</script>
