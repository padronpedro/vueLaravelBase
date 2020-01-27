<template>
    <v-card
        class="mx-4"
        :elevation="4">

        <v-toolbar dense flat>
            <p-bread-crumbs :items="items"></p-bread-crumbs>
            <v-spacer></v-spacer>

            <v-tooltip bottom :color="$colors.orange" v-if="$auth.check(['Add_Users'])">
                <template v-slot:activator="{ on }">
                    <v-btn icon @click="addItem()" v-on="on">
                        <v-icon>mdi-account-plus</v-icon>
                    </v-btn>
                </template>
                <span>{{ $t('Add user') }}</span>
            </v-tooltip>
        </v-toolbar>

        <v-card-text>
            <v-data-table
                :headers="headers"
                :items="tableContent"
                :options.sync="options"
                :server-items-length="totalItems"
                :footer-props="footerProps"
                :loading="isLoadingData"
                class="elevation-1"
            >
                <template v-slot:item.actions="{ item }">
                    <v-tooltip bottom :color="$colors.orange" v-if="$auth.check(['Edit_Users'])">
                        <template v-slot:activator="{ on }">
                            <v-icon
                                v-on="on"
                                class="mr-2"
                                @click="editItem(item)">
                                mdi-pencil
                            </v-icon>
                        </template>
                        <span>{{ $t('Edit user') }}</span>
                    </v-tooltip>
                    <v-tooltip bottom :color="$colors.orange" v-if="$auth.check(['Edit_Users'])">
                        <template v-slot:activator="{ on }">
                            <v-icon
                                v-on="on"
                                class="mr-2"
                                @click="statusItem(item)">
                                mdi-sync
                            </v-icon>
                        </template>
                        <span>{{ $t('Change status') }}</span>
                    </v-tooltip>
                    <v-tooltip bottom :color="$colors.orange" v-if="$auth.check(['Delete_Users'])">
                        <template v-slot:activator="{ on }">
                            <v-icon
                                v-on="on"
                                @click="deleteItem(item)">
                                mdi-delete
                            </v-icon>
                        </template>
                        <span>{{ $t('Delete user') }}</span>
                    </v-tooltip>
                </template>
                <template v-slot:item.is_active="{ item }">
                    <v-chip :color="item.is_active=='1' ? 'green' : 'red'" dark>{{ item.is_active=='1' ? $t('Active') : $t('Disabled') }}</v-chip>
                </template>
                <template v-slot:item.name="{ item }">
                    {{ item.name | capitalize }}
                </template>
                <template v-slot:item.role="{ item }">
                    {{ item.role | capitalize }}
                </template>

            </v-data-table>
        </v-card-text>

        <p-dialog-yes-no ref="confirm"></p-dialog-yes-no>
        <p-snack-bar :txtSnackBar="snackbar.txtErrorMessage" :showBar="snackbar.showErrorMessage" :timeout="3000" :color="snackbar.color"></p-snack-bar>

    </v-card>
</template>

<script>
export default {
    data: function(){
        return {
            snackbar:
            {
                showErrorMessage: false,
                txtErrorMessage: '',
                color: 'error',
            },
            items: [
                {
                    text: this.$t('Dashboard'),
                    disabled: false,
                    to: 'dashboard',
                },
                {
                    text: this.$t('Users'),
                    disabled: true,
                    to: 'admin.users',
                },
            ],
            headers: [
                { text: this.$t('Name'), align: 'left', value: 'name' },
                { text: this.$t('Email'), value: 'email' },
                { text: this.$t('Level'), value: 'role' },
                { text: this.$t('Status'), align: 'center', value: 'is_active', width:'10%' },
                { text: this.$t('Actions'), align: 'center', sortable: false, value: 'actions', width:'14%'  },
            ],
            tableContent: [],
            isLoadingData: false,
            options: {},
            totalItems: 0,
            footerProps: {
                'items-per-page-options': [10, 25, 50, 100,500],
            },
        }
    },
    watch: {
      options: {
        handler () {
          this.getDataFromApi()
        },
        deep: true,
      },
    },
    methods: {
        //get data for datatable
        getDataFromApi() {
            let newOptions = {
                itemsPerPage: this.options.itemsPerPage,
                sortBy: this.options.sortBy[0] || 'name',
                sortDesc: this.options.sortDesc[0] ? 'desc' : 'asc',
                page: this.options.page,
                master_id: 0
            };
            this.isLoadingData = true;
            axios.get('/user/datatable', {params: newOptions})
                .then(response => {
                    this.tableContent = response.data.data;
                    this.totalItems = response.data.total;
                    this.isLoadingData = false;
                })
                .catch(error => {
                    console.log('AdmUser error: ',error);
                    this.isLoadingData = false;
                });
        },
        addItem: function()
        {
            this.$router.push({name: 'admin.addedituser'}).catch(err => {});
        },
        editItem: function(item)
        {
            this.$router.push({name: 'admin.addedituser', params: {userId: item.id}})
        },
        statusItem: function(item)
        {
            let formData = {
                id: item.id,
            }
            axios.post('/user/status', formData)
                .then(response => {
                    if(response.data.status=='ERROR')
                    {
                        this.$showError(this.$t('Error')+': '+response.data.message,'error',this);
                    }else{
                        this.$showError(this.$t('User status changed successfully'),'success',this);
                        this.getDataFromApi();
                    }
                })
                .catch(error => {
                    this.$showError(this.$t('Error')+': '+error.response.data.message,'error',this);
                });
        },
        deleteItem: function(item)
        {
            this.$refs.confirm.open(
                this.$t('Delete user'),
                this.$t('Do you want to delete the user: '+item.name+' ?'),
                { color: 'red', width: '500' })
                .then((confirm) => {
                    if(confirm)
                    {
                        let formData = {
                            id: item.id,
                        }
                        axios.delete('/user/'+item.id, formData)
                            .then(response => {
                                this.$showError(this.$t('User deleted successfully'),'success',this);
                                this.getDataFromApi();
                            })
                            .catch(error => {
                                this.$showError(this.$t('Error')+': '+error.response.data.message,'error',this);
                            });
                    }
            })
        }
    }
}
</script>
