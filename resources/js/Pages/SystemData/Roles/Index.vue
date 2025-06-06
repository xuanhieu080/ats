<template>
	<AppLayout title="System Data">
		<div class="rules-wrapper">
			<div class="rules-tabs w-full max-w-[270px]">
				<a-card class="w-full">
					<div class="flex flex-col gap-2 w-full">
						<a-button
							:href="route('roles.index')"
							type="ghost"
							class="button-wrapper flex items-center w-full justify-center bg-[#F3F3F3] text-primary-color hover:text-[var(--primary-color)] h-[45px] font-semibold"
							:class="route().current('email-templates.index') ? 'bg-primary-color !text-white' : ''">
							Email Templates
						</a-button>
						<a-button
							:href="route('roles.index')"
							type="ghost"
							class="button-wrapper flex items-center w-full justify-center bg-[#F3F3F3] text-primary-color hover:text-[var(--primary-color)] h-[45px] font-semibold"
							:class="route().current('source-channel.index') ? 'bg-primary-color !text-white' : ''">
							Source Channel
						</a-button>
						<a-button
							:href="route('roles.index')"
							type="ghost"
							class="button-wrapper flex items-center w-full justify-center bg-[#F3F3F3] text-primary-color hover:text-[var(--primary-color)] h-[45px] font-semibold"
							:class="route().current('technical-skill.index') ? 'bg-primary-color !text-white' : ''">
							Technical Skills
						</a-button>
						<a-button
							:href="route('roles.index')"
							type="ghost"
							class="button-wrapper flex items-center w-full justify-center bg-[#F3F3F3] text-primary-color hover:text-[var(--primary-color)] h-[45px] font-semibold"
							:class="route().current('roles.index') ? 'bg-primary-color !text-white' : ''">
							Roles
						</a-button>
					</div>
				</a-card>
			</div>
			<div class="system-content flex-1">
				<a-card class="w-full">
					<div class="flex flex-col gap-4">
						<h2 class="text-2xl font-semibold">Roles</h2>
						<p class="text-gray-600">Manage user roles and permissions.</p>
						<a-button type="primary" class="w-fit" @click="handleOpenCreateModal"> Create New Role </a-button>
						<a-table
							:dataSource="dataSource?.data?.data"
							:columns="columns"
							rowKey="id"
							:loading="false"
							:scroll="{ x: 'max-content' }"
							:pagination="{ pageSize: pagination.pageSize, current: pagination.current, total: pagination.total }">
							<template #actions="{ record }">
								<a-button type="link" :href="`/roles/${record.id}/edit`">Edit</a-button>
								<a-button type="link" danger>Delete</a-button>
							</template>
						</a-table>
					</div>
				</a-card>
			</div>
		</div>

		<a-modal v-model:open="openCreateRoleModal" title="Create New Role" :footer="null" width="600px">
			<!-- Modal content for creating a new role goes here -->
			<a-style-provider hash-priority="high">
				<a-form layout="vertical" :model="createRoleForm" :rules="validateInfos">
					<a-form-item label="Role Name" v-bind="validateInfos.name">
						<a-input v-model:value="createRoleForm.name" placeholder="Enter role name" />
					</a-form-item>
					<a-form-item label="Description" v-bind="validateInfos.description">
						<a-textarea v-model:value="createRoleForm.description" placeholder="Enter role description" />
					</a-form-item>
					<a-form-item>
						<a-button type="primary" @click="handleCreateRoles" :loading="loadingCreate" html-type="submit">Create Role</a-button>
						<a-button class="ml-4" @click="openCreateRoleModal = false" :loading="loadingCreate">Cancel</a-button>
					</a-form-item>
				</a-form>
			</a-style-provider>
		</a-modal>
	</AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

import { reactive, ref, computed } from 'vue';
import { Form, message } from 'ant-design-vue';
import { usePagination } from 'vue-request';

const columns = ref([
	{ title: 'STT', dataIndex: 'id', key: 'id' },
	{ title: 'Role Name', dataIndex: 'name', key: 'name' },
	{ title: 'Description', dataIndex: 'description', key: 'description' },
	{ title: 'Created at ', dataIndex: 'created_at', key: 'created_at' },
	{ title: 'Created by ', dataIndex: 'created_by', key: 'created_by' },
	{ title: 'Actions', key: 'actions', scopedSlots: { customRender: 'actions' }, fixed: 'right' },
]);
const openCreateRoleModal = ref(false);
const handleOpenCreateModal = () => {
	openCreateRoleModal.value = true;
	// Logic to open the modal for creating a new role
	// This could be a modal component or a redirect to a create role page
	console.log('Open Create Role Modal');
};

const createRoleForm = reactive({
	name: null,
	description: null,
});

const useForm = Form.useForm;

const { validate, resetFields, validateInfos } = useForm(
	createRoleForm,
	reactive({
		name: [
			{ required: true, message: 'Please enter the role name' },
			{ min: 3, max: 50, message: 'Role name must be between 3 and 50 characters', trigger: 'blur' },
		],
		description: [{ required: false, message: 'Please enter the role description', trigger: 'blur' }],
	}),
);
const loadingCreate = ref(false);
const handleCreateRoles = async () => {
	validate()
		.then(async () => {
			// Logic to handle role creation
			// This could involve making an API call to create the role
			loadingCreate.value = true;
			try {
				// Simulate API call
				await new Promise((resolve) => setTimeout(resolve, 1000));
				// Make it got error if the role already exists
				if (createRoleForm.name === 'admin') {
					throw new Error('Role already exists');
				}
				// If successful, reset the form and close the modal
				message.success('Role created successfully!');
				resetFields();
				openCreateRoleModal.value = false;

			} catch (error) {
				console.error('Error creating role:', error);
				message.error('Failed to create role. Please try again.');
				return;
			} finally {
				loadingCreate.value = false;
			}
		})
		.catch((error) => {
			console.error('Validation failed:', error);
			message.error('Failed to create role. Please check the form.');
		});
};

const queryData = (params) => {
    return axios.get('/api/roles', {
        params,
    });
};

const {
    data: dataSource,
    run,
    loading,
    current,
    totalPage,
    pageSize,
} = usePagination(queryData, {
    defaultParams: [
        {
            limit: 10,
            sort: {
                id: 'desc',
            },
        },
    ],
    pagination: {
        currentKey: 'page',
        pageSizeKey: 'limit',
        totalKey: 'dataSource.data.meta.total',
    },
});

const pagination = computed(() => ({
    total: dataSource.value?.data?.meta.total,
    current: current.value,
    pageSize: pageSize.value,
}));
</script>

<style lang="scss" scoped>
.rules-wrapper {
	width: 100%;
	height: 100%;
	display: flex;
	gap: 32px;
	padding: 24px;
	.button-wrapper {
		cursor: pointer;
	}
}
</style>
