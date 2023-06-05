<template>
<div v-if="errors">
    <div class="alert alert-danger" v-for="error in errors">{{ error }}</div>
</div>
</template>

<script>
    export default {
        props: ['errors']
    }
</script>

<style>
</style>