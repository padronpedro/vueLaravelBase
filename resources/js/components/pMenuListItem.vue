<template>
    <v-list-item @click.prevent="btnFunction(toPath)">
        <v-list-item-icon>
            <v-icon>{{ iconName }}</v-icon>
        </v-list-item-icon>
        <v-list-item-title>{{ itemName }}</v-list-item-title>
    </v-list-item>
</template>

<script>
export default {
    props: {
        itemName: {
            type: String,
            required: true
        },
        iconName: {
            type: String,
            required: true
        },
        toPath: {
            type: String,
            required: true
        },
    },
    methods: {
        btnFunction: function(path)
        {
            this.$router.push({name: path}).catch(err => {});
        }
    }
};

/**
 *  <p-menu-list-item :toPath="<VueRouterPath>" :itemName="$t('<TEXT>')" :iconName="'mdi-<icons_name>'"></p-menu-list-item>
 *
 */
</script>
