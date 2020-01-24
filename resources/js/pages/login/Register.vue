<template>
    <v-content>
        <v-row>
            <v-col cols="12">
                <v-card class="mx-auto" max-width="500" outlined>
                    <v-list-item three-line>
                        <v-list-item-content>
                            <div class="overline mb-4">{{ $t("Sign up") }}</div>
                            <v-list-item-title class="headline mb-1">{{ $t("Jemap") }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-progress-linear
                        :active="loading"
                        :indeterminate="loading"
                        bottom
                        color="red accent-4"
                    ></v-progress-linear>

                    <v-card-text>
                        <v-form ref="form" lazy-validation>
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        v-model="name"
                                        :label="$t('Name')"
                                        required
                                        :dense="true"
                                        :rules="[ v => !!v || $t('This field is required'), v => v.length >= 5 || $t('Min 5 characters') ]"
                                        name="name"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        v-model="email"
                                        :label="$t('Email')"
                                        required
                                        :dense="true"
                                        :rules="
                                        [
                                            v => !!v || $t('This field is required'),
                                            v =>/.+@.+\..+/.test(v) || $t('E-mail must be valid')
                                        ]"
                                        name="email"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        v-model="password"
                                        :label="$t('Password')"
                                        name="password"
                                        :dense="true"
                                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                        :rules="
                                        [
                                            v => !!v || $t('This field is required'),
                                            v => v.length >= 8 || $t('Min 8 characters')
                                        ]"
                                        required
                                        @click:append="showPassword = !showPassword"
                                        :type="showPassword ? 'text' : 'password'"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        v-model="password_confirmation"
                                        :label="$t('Password confirm')"
                                        name="password_confirmation"
                                        :append-icon="showPassword2 ? 'mdi-eye' : 'mdi-eye-off'"
                                        :rules="
                                        [
                                            v => !!v || $t('This field is required'),
                                            v => v.length >= 8 || $t('Min 8 characters')
                                        ]"
                                        required
                                        :dense="true"
                                        @click:append="showPassword2 = !showPassword2"
                                        :type="showPassword2 ? 'text' : 'password'"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-row class="flex-wrap-reverse">
                            <v-col cols="12" sm="4">
                                <v-btn color="warning" @click="btnBack">{{ $t("Back") }}</v-btn>
                            </v-col>
                            <v-col cols="12" sm="4"> </v-col>
                            <v-col cols="12" sm="4" class="text-right">
                                <v-btn color="primary" @click="register">{{ $t("Sign up") }}</v-btn>
                            </v-col>
                        </v-row>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <p-snack-bar
            :txtSnackBar="snackbar.txtErrorMessage"
            :showBar="snackbar.showErrorMessage"
            :timeout="3000"
            :color="snackbar.color"
        ></p-snack-bar>
    </v-content>
</template>
<script>
  export default {
    data() {
      return {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        error: '',
        errors: {},
        loading: false,
        showPassword: false,
        showPassword2: false,
        snackbar: {
            showErrorMessage: false,
            txtErrorMessage: '',
            color: 'error'
        },
      }
    },
    methods: {
        register() {
            this.loading = true;
            var app = this;
            this.$auth.register({
                data: {
                    name: app.name,
                    email: app.email,
                    password: app.password,
                    password_confirmation: app.password_confirmation
                },
                success: function () {
                    this.$showError(this.$t("Registration successful, wait a moment please"),'success',app);
                    this.$router.push({name: 'login', params: {successRegistrationRedirect: true}})
                },
                error: function (res) {
                    app.error = res.response.data.error;
                    app.errors = res.response.data.errors || {};
                    this.loading = false;
                    var messageError = this.$t('Error creating your user, try again later');
                    if(app.error)
                    {
                        messageError = app.error + '';
                    }else{
                        if(app.errors.email)
                        {
                            messageError = '';
                            app.errors.email.forEach(element => {
                                if(element)
                                {
                                    messageError += element + '. ';
                                }
                            });
                        }
                    }
                    this.$showError(messageError,'error',app);
                }
            })
        },
        btnBack: function()
        {
            this.$router.push({name: 'login'}).catch(err => {});
        }
    }
  }
</script>
