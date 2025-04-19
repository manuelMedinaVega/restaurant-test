<template>

    <v-app>

        <AppHeader />

        <v-main>
            <router-view />
        </v-main>

    </v-app>
    
</template>

<script setup>
    import AppHeader from './AppHeader.vue'
</script>

<style>

    html, body, #app {
        height: 100%;
        margin: 0;
    }

</style>