<template>
    <div id="app">
        <Navbar v-if="$route.path !== '/login'" />
        <main :class="{ 'with-navbar': $route.path !== '/login' }">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
import Navbar from './components/Navbar.vue';

export default {
    components: {
        Navbar
    }
};
</script>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #f5f7fa;
    color: #333;
}

main {
    min-height: 100vh;
}

main.with-navbar {
    margin-left: 250px;
    padding-top: 60px;
}
</style>