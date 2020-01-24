<template>
    <v-breadcrumbs :items="items">
        <template v-slot:item="props">
            <v-breadcrumbs-item
                @click="redirectTo(props.item)"
                :class="[props.item.disabled ? 'disabled' : '',props.item.disabled ? '' : 'with-cursor']"
            >
            {{ props.item.text }}
            </v-breadcrumbs-item>
        </template>
    </v-breadcrumbs>

</template>

<style scoped>
    .disabled {
        color: grey;
        font-size: 1.20rem;
    }
    .with-cursor {
        cursor: pointer;
        font-size: 1.20rem;
    }
</style>

<script>
export default {
    props: {
        items: {
            type: Array,
            required: true
        }
    },
    methods: {
        redirectTo(item)
        {
            if(item.params)
            {
                this.$router.push({name:item.to, params: item.params}).catch(err => {});
                //window.location=item.to;
            }else{
                this.$router.push({name:item.to}).catch(err => {});
                //window.location=item.to;
            }
        },
    }
}
</script>
