<template>
    <div class="container">
        <div class="card card-default">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                User Dashboard
            </div>
        </div>
        <div class="card card-default">
            <div class="card-header">Users list</div>
            <div class="card-body">
                <user-list></user-list>
            </div>
        </div>
    </div>
</template>
<script>
  export default {
    data() {
      return {
        //
      }
    },
    components: {
      //
    },
    mounted() {
        console.log(this.$auth.user());
    }
  }
</script>
