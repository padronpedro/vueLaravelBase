<template>
    <v-col
        :cols="cols"
        :lg="lg"
        :sm="sm"
    >
         <slot></slot>
    </v-col>
</template>

<script>
export default {
    props: ['size'],
    mounted() {
        this.$nextTick(function() {
            if(this.size=='xs')
            {
                this.lg=1;
            }
            if(this.size=='sm')
            {
                this.lg=2;
            }
            if(this.size=='md')
            {
                this.lg=3;
            }
            if(this.size=='xl')
            {
                this.lg=6;
            }
        })
    },
    data: function() {
        return {
            cols: 12,
            lg: 4,
            sm: 6
        }
    }
}
</script>
