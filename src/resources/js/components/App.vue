<template>

    <v-app>

        <v-app-bar app color="primary" dark>

            <v-app-bar-title>Restaurante</v-app-bar-title>

            <v-spacer></v-spacer>

            <v-btn to="/" text>Inicio</v-btn>
            <v-btn to="/comensales" text>Comensales</v-btn>
            <v-btn to="/mesas" text>Mesas</v-btn>
            <v-btn to="/reservas" text>Reservas</v-btn>
            
        </v-app-bar>

        <v-main>
            <router-view />
        </v-main>

    </v-app>
    
</template>

<style>

    html, body, #app {
        height: 100%;
        margin: 0;
    }

</style>