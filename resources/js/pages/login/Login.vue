<template>

    <v-content>
        <v-row>
            <v-col cols="12">
                <v-card class="mx-auto" max-width="500" outlined>
                    <v-img max-height="100px" src="images/logo.png"></v-img>

                    <v-progress-linear
                        :active="loading"
                        :indeterminate="loading"
                        bottom
                        color="red accent-4"
                    ></v-progress-linear>

                    <v-list-item three-line>
                        <v-list-item-content>
                            <div class="overline mb-4">{{ $t("Welcome to") }}</div>
                            <v-list-item-title class="headline mb-1">Jemap {{$t("Marketing")}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-card-text>
                        <v-form ref="form" lazy-validation>
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        v-model="email"
                                        :label="$t('Email')"
                                        required
                                        :rules="[ v =>!!v || $t('This field is required'), v => /.+@.+\..+/.test(v) || $t('E-mail must be valid') ]"
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
                                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                                        :rules="[ v => !!v || $t('This field is required') ]"
                                        required
                                        @click:append="showPassword = !showPassword"
                                        :type="showPassword ? 'text' : 'password'"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>

                    <v-card-actions>
                        <v-row class="flex-wrap-reverse">
                            <v-col cols="12" sm="4">
                                <v-btn
                                    color="warning"
                                    @click="btnAction('register')"
                                    >{{ $t("Sign up") }}</v-btn
                                >
                            </v-col>
                            <v-col cols="12" sm="4">
                                <a @click="btnAction()">{{ $t("Recover password") }}</a>
                            </v-col>
                            <v-col cols="12" sm="4" class="text-right">
                                <v-btn color="primary" @click.prevent="login">{{ $t("Login") }}</v-btn>
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
            >
        </p-snack-bar>

    </v-content>

</template>
<script>
export default {
    data() {
        return {
            email: null,
            password: null,
            loading: false,
            showPassword: false,
            snackbar:
            {
                showErrorMessage: false,
                txtErrorMessage: '',
                color: 'error',
            },
        };
    },
    methods: {
        login() {
            if (this.$refs.form.validate()) {
                this.loading = true;
                var redirect = this.$auth.redirect();
                this.$auth.login({
                    data: {
                        email: this.email,
                        password: this.password
                    },
                    success: function(response) {
                        // handle redirection
                        this.$showError(this.$t("Welcome back")+' '+this.$auth.user().name,'error',this);

                        this.$router.push({ name: 'dashboard' });
                    },
                    error: function(err) {
                        this.$showError(err.response.data.error,'error',this);
                        this.loading = false;
                    },
                    rememberMe: true,
                    fetchUser: true
                });
            } else {
                this.$showError(this.$t("Please complete the required fields"),'error',this);
            }
        },
        btnAction: function(pathToGo)
        {
            this.$router.push({name: pathToGo}).catch(err => {});
        },
    }
};
</script>
