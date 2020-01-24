<template>
    <v-tooltip bottom>
        <template v-slot:activator="{ on }">
            <v-btn v-on="on" text @click.prevent="btnFunction(toPath)" >
            <v-icon>{{iconName}}</v-icon>
            </v-btn>
        </template>
        <span>{{ txtTooltip }}</span>
    </v-tooltip>
</template>

<script>
    export default {
        props: {
            toPath: {
                type: String,
                required: true
            },
            txtTooltip: {
                type: String,
                required: true
            },
            iconName: {
                type: String,
                required: true
            }
        },
        methods: {
            btnFunction: function(path)
            {
                this.$router.push({name: path}).catch(err => {});
            }
        }
    }

    /**
     *  <p-icon-button :btnFunction="<FunctionInParent>" :txtTooltip="$t('<TEXT>')" :iconName="'mdi-<icons_name>'"></p-icon-button>
     *
     *  in parent methods define what the function will do
     *
     */
</script>
