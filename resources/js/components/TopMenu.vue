<template>
    <div class="margin-bottom-app">
        <v-app-bar  dense fixed>
            <v-app-bar-nav-icon
                @click.stop="drawer = !drawer"
                class="d-flex d-sm-none"
            />
            <v-img
                src="/images/logo.png"
                aspect-ratio="1"
                class="mr-2"
                max-width="20"
                max-height="20"
            />

            <v-toolbar-title>BugTrack</v-toolbar-title>

            <v-toolbar-items
                class="d-none d-sm-flex">
                <v-menu
                    open-on-hover
                    bottom
                    offset-y
                    v-if="$auth.check(['Manage_Users']) && ($auth.user().role_id==1) ">
                        <template v-slot:activator="{ on, attrs}">
                            <v-btn
                                text
                                v-bind="attrs"
                                v-on="on">
                                <v-icon class="mr-2">mdi-settings</v-icon>
                                {{ $t('System') }}
                            </v-btn>
                        </template>
                        <v-list :dense="true">
                            <p-menu-list-item
                                :toPath="'admin.users'"
                                :itemName="$t('Users')"
                                :iconName="'mdi-account-group'"
                                v-if="$auth.check('Manage_Users') && ($auth.user().role_id==1)">
                            </p-menu-list-item>
                            <p-menu-list-item
                                :toPath="'admin.dashboard'"
                                :itemName="$t('Test')"
                                :iconName="'mdi-account-group'"
                                v-if="$auth.check('Manage_Users') && ($auth.user().role_id==1)">
                            </p-menu-list-item>
                        </v-list>
                </v-menu>
            </v-toolbar-items>

            <v-spacer></v-spacer>

            <p-icon-button
                :toPath="'login'"
                :txtTooltip="$t('Sign in')"
                :iconName="'mdi-lock-open-variant'"
                v-if="!$auth.check()" >
            </p-icon-button>
            <p-icon-button
                :toPath="'register'"
                :txtTooltip="$t('Create account in Jemap')"
                :iconName="'mdi-account-plus'"
                v-if="!$auth.check()" >
            </p-icon-button>

            <v-tooltip
                bottom
                v-if="$auth.check()">
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" text @click.prevent="logoff" >
                            <v-icon>mdi-logout-variant</v-icon>
                        </v-btn>
                    </template>
                    <span>{{ $t('Logout') }}</span>
            </v-tooltip>

        </v-app-bar>
        <p-snack-bar
            :txtSnackBar="snackbar.txtErrorMessage"
            :showBar="snackbar.showErrorMessage"
            :timeout="3000"
            :color="snackbar.color"
            >
        </p-snack-bar>
    </div>
</template>
<script>
export default {
    data() {
        return {
            snackbar:
            {
                showErrorMessage: false,
                txtErrorMessage: '',
                color: 'error',
            },
        }
    },
    mounted() {
        console.log('$auth.user',this.$auth.user());
    },
    methods: {
        goTo: function(path)
        {
            this.$router.push({name: path}).catch(err => {});
        },
        logoff: function()
        {
            this.$showError(this.$t("Closing session... wait a moment please"),'success',this);
            this.$auth.logout();
        }
    }
}

</script>
<style>
.margin-bottom-app {
  margin-bottom: 40px;
}
</style>
