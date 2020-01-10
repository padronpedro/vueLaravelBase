<template>
    <v-app>
        <v-card
            class="overflow-hidden"
            height="100%"
            width="100%"
        >
            <top-menu></top-menu>

            <v-card-text>
                <router-view></router-view>
            </v-card-text>
        </v-card>
    </v-app>
</template>


<script>
    export default {
        mounted() {
            console.log('--primero--');
        }
    }
</script>
