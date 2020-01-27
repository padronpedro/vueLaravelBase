<template>
    <v-card
        class="mx-4"
        :elevation="4">

        <v-toolbar dense flat>
            <p-bread-crumbs :items="items"></p-bread-crumbs>

            <v-spacer></v-spacer>
        </v-toolbar>

        <v-card-text>
            <v-form ref="form"  lazy-validation>
                <p-divider :txtDivider="$t('Account information')"></p-divider>
                <v-row>
                    <p-column>
                        <v-text-field
                            v-model="email"
                            :label="$t('Email')"
                            required
                            :rules="[v => !!v || $t('This field is required'),v => /.+@.+\..+/.test(v) || $t('E-mail must be valid')]"
                            name="email"></v-text-field>
                    </p-column>
                    <p-column>
                        <v-text-field
                            v-model="password"
                            :label="$t('Password')"
                            name="password"
                            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                            :rules="[v => !!v || $t('This field is required'), v => v.length >= 8 || $t('Min 8 characters')]"
                            required
                            @click:append="showPassword = !showPassword"
                            :type="showPassword ? 'text' : 'password'"></v-text-field>
                    </p-column>
                    <p-column>
                        <v-text-field
                            v-model="name"
                            :label="$t('Name')"
                            required
                            :rules="[v => !!v || $t('This field is required')]"
                            name="name"></v-text-field>
                    </p-column>
                </v-row>
                <v-row>
                    <p-column>
                        <v-select
                            v-model="roles_id"
                            :items="rolesItems"
                            required
                            item-text="name"
                            item-value="id"
                            :rules="[v => !!v || $t('This field is required')]"
                            :label="$t('Role')"></v-select>
                    </p-column>
                    <p-column>
                        <v-select
                            v-model="permissions"
                            :items="permissionsItems"
                            :label="$t('Permissions')"
                            multiple
                            :height="32"
                            chips
                            :item-text="item => item.name.replace('_',' ')"
                            item-value="id"
                            :hint="$t('Select the permissions for this user')"
                            persistent-hint
                            >
                            <template v-slot:selection="{ item, index }">
                                <v-chip small v-if="(index === 0) || (index === 1) || (index === 2)">
                                    <span>{{ item.name }}</span>
                                </v-chip>
                                <span
                                    v-if="index === 3"
                                    class="grey--text caption"
                                >(+{{ permissions.length - 3 }} {{$t('others')}})</span>
                            </template>
                        </v-select>
                    </p-column>
                </v-row>
            </v-form>
        </v-card-text>

        <v-card-actions>
            <v-btn color="warning" @click="goBack">{{ $t('Back')}}</v-btn>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="validate">{{ editMode ? $t('Update') : $t('Save')}}</v-btn>
        </v-card-actions>

        <p-snack-bar :txtSnackBar="snackbar.txtErrorMessage" :showBar="snackbar.showErrorMessage" :timeout="3000" :color="snackbar.color"></p-snack-bar>

    </v-card>
</template>

<script>
export default {
    data: function() {
        return {
            items: [
                {
                    text: this.$t('Dashboard'),
                    disabled: false,
                    to: 'dashboard',
                },
                {
                    text: this.$t('Users'),
                    disabled: false,
                    to: 'admin.users',
                },
                {
                    text: this.$t('Add user'),
                    disabled: true,
                    to: 'admin.addedituser',
                },
            ],
            showPassword: false,
            email: '',
            password: '',
            name: '',
            roles_id:'',
            rolesItems: [],
            snackbar:
            {
                showErrorMessage: false,
                txtErrorMessage: '',
                color: 'error',
            },
            permissions:'',
            permissionsItems: [],
            editMode: false,
        }
    },
    watch: {
    },
    created() {
        axios.get('/roles')
                .then(response => {
                this.rolesItems = response.data.data;
            })
            .catch(error => {
                console.log('Get Roles error: ',error);
            });
        axios.get('/permissions')
            .then(response => {
                this.permissionsItems = response.data.data;
            })
            .catch(error => {
                console.log('Get Permisions error: ',error);
            });
    },
    props: ['userId'],
    mounted() {
        //check for edit mode
        this.$nextTick(function () {
            if(this.userId)
            {
                this.items[2].text = this.$t('Modify user');
                this.editMode = true;
                //get user data
                axios.get('/user/'+this.userId)
                .then(response => {
                    let data = response.data.data;
                    let l_permission = data.permissions;
                    this.password = '12345678';
                    this.email = data.email;
                    this.name = data.name;
                    this.roles_id = data.role_id
                    this.permissions = l_permission.map((item) => {
                            return item.id;
                        }
                    )
                })
                .catch(error => {
                    console.log('Get User error: ',error);
                });
            }
        });
    },
    methods: {
        goBack()
        {
            this.$router.push({name: 'admin.users'}).catch(err => {});
        },
        validate()
        {
            if (this.$refs.form.validate()) {
                let formData = {
                    email: this.email,
                    password: this.password,
                    name: this.name,
                    role_id: this.roles_id,
                    permissions: this.permissions,
                    userId: this.userId,
                };
                let url='/user';
                let message = this.$t('User added successfully');
                var appData = this;

                if(this.editMode)
                {
                    this.$showError(this.$t('Updating information, wait a moment please'),'success',appData);
                    url='/user/'+this.userId;
                    message = this.$t('User modified successfully');
                    axios.put(url, formData)
                        .then(response => {
                            var result = response.data;
                            if(result.status=='SUCCESS')
                            {
                                this.$showError(message,'success',appData);
                                setTimeout(()=>{
                                    this.$router.push({name: 'admin.users'}).catch(err => {});
                                },1000);
                            }else{
                                this.$showError(this.$t('Error')+': '+result.message,'error',appData);
                            }
                        })
                        .catch(error => {
                            this.$showError(error,'error',appData);
                        });
                }else{
                    this.$showError(this.$t('Saving information, wait a moment please'),'success',appData);

                    axios.post(url, formData)
                        .then(response => {
                            var result = response.data;
                            if(result.status=='SUCCESS')
                            {
                                this.$showError(message,'success',appData);
                                setTimeout(()=>{
                                    this.$router.push({name: 'admin.users'}).catch(err => {});
                                },1000);
                            }else{
                                this.$showError(this.$t('Error')+': '+result.message,'error',appData);
                            }
                        })
                        .catch(error => {
                            this.$showError(error,'error',appData);
                        });
                }
            }else{
                this.$showError(this.$t('Please complete the required fields'),'error',this);
            }
        }
    }
}
</script>
