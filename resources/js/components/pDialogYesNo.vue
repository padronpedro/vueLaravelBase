<template>
    <v-dialog
        v-model="dialog"
        :max-width="options.width"
        :style="{ zIndex: options.zIndex }"
        @keydown.esc="cancel">
        <v-card>
            <v-toolbar
                dark
                :color="options.color"
                dense
                flat>
                <v-toolbar-title
                    class="white--text">
                    {{ title }}
                </v-toolbar-title>
            </v-toolbar>
            <v-card-text
                v-show="!!message"
                class="pa-4">
                {{ message }}
            </v-card-text>
            <v-card-actions
                class="pt-0">
                <v-spacer></v-spacer>
                <v-btn
                    color="grey"
                    text
                    @click.native="cancel">
                    {{ $t('Cancel') }}
                </v-btn>
                <v-btn
                    color="primary darken-1"
                    text
                    @click.native="agree">
                    {{ $t('Continue') }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    resolve: null,
    reject: null,
    message: null,
    title: null,
    options: {
        color: 'primary',
        width: '',
        zIndex: 200
    }
  }),
  methods: {
    open(title, message, options) {
        this.dialog = true
        this.title = title
        this.message = message
        this.options = Object.assign(this.options, options)
        return new Promise((resolve, reject) => {
            this.resolve = resolve
            this.reject = reject
        })
    },
    agree() {
        this.resolve(true)
        this.dialog = false
    },
    cancel() {
        this.resolve(false)
        this.dialog = false
    }
  }
}

    /**
     *   <p-dialog-yes-no :txtSnackBar="$t('<TEXT>')" :showBar="showErrorMessage" :timeout="<Integer>" :color="'<success,error,....>" :mode="multiline"></p-dialog-yes-no>
     *
     *      this.$refs.confirm.open(
     *          this.$t('Delete user'),
     *               this.$t('Do you want to delete the user: '+item.name+' and his sub-users ?'),
     *               { color: 'red', width: '500' })
     *               .then((confirm) => {
     *                   if(confirm)
     *                   {
     *                       let formData = {
     *                           id: item.id,
     *                       }
     *                       axios.delete('/user/'+item.id, formData)
     *                           .then(response => {
     *                               this.$showError(this.$t('User deleted successfully'),'success',this);
     *                               this.getDataFromApi();
     *                           })
     *                           .catch(error => {
     *                               this.$showError(this.$t('Error')+': '+error.response.data.message,'error',this);
     *                           });
     *                   }
     *           })
     *
     */
</script>

