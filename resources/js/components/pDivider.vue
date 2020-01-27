<template>
    <div>
        <v-subheader class="font-weight-bold mb-n4">
            <img
               v-if="imageDivider"
              :src="'/images/'+imageDivider"
              :width="imageWidth"
              class="mr-4"
            />
            {{ txtDivider }}
        </v-subheader>
        <v-divider></v-divider>
    </div>
</template>

<script>
export default {
    props: {
        txtDivider: {
            type: String,
            required: true
        },
        imageDivider: {
            type: String,
            required: false
        },
        imageWidth: {
            type: String,
            required: false
        },
    }
}
</script>
