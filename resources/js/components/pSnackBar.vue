<template>
    <v-snackbar
      v-model="snackbar"
      :timeout="timeout ? timeout : 3000"
      :color="color ? color : 'error'"
      :bottom="true"
      :multi-line="mode === 'multiline'"
    >
      {{ txtSnackBar }}
      <v-btn
        color="white"
        text
        @click="snackbar = false"
      >
        {{ $t('Close') }}
      </v-btn>
    </v-snackbar>
</template>

<script>
    export default {
        data: () => ({
            snackbar: false,
        }),
        watch: {
            showBar: function(val){
                if(this.txtSnackBar.length>0)
                {
                    if(val)
                    {
                        this.snackbar=val;
                    }
                }
            }
        },
        props: {
            mode: {
                type: String,
                required: false
            },
            color: {
                type: String,
                required: false
            },
            timeout: {
                type: Number,
                required: false
            },
            txtSnackBar: {
                type: String,
                required: true
            },
            showBar: {
                type: Boolean,
                required: true
            }
        }
    }

    /**
     *   <p-snack-bar :txtSnackBar="$t('<TEXT>')" :showBar="showErrorMessage" :timeout="<Integer>" :color="'<success,error,....>" :mode="multiline"></p-snack-bar>
     *
     *  in parent define a data element:   showErrorMessage: false;
     *
     *  and a method
     *
     *  showError() {
     *      this.showErrorMessage=true;
     *      setTimeout(()=>{
     *          this.showErrorMessage=false;
     *      },2000);
     *   }
     */
</script>
