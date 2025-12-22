<template>
    <nav class="navbar">
        <div class="navbar-brand">
            <h2>KLINIQ</h2>
        </div>
        <ul class="navbar-menu">
            <li><router-link to="/" class="nav-link">Dashboard</router-link></li>
            <li><router-link to="/patients" class="nav-link">Pasien</router-link></li>
            <li><router-link to="/queues" class="nav-link">Antrian</router-link></li>
            <li><router-link to="/poli" class="nav-link">Poli</router-link></li>
        </ul>
        <div class="navbar-user">
            <button @click="logout" class="logout-btn">Logout</button>
        </div>
    </nav>
</template>

<script>
export default {
    methods: {
        logout() {
            localStorage.removeItem('token');
            this.$router.push('/login');
        }
    }
};
</script>

<style scoped>
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100vh;
    background: #2c3e50;
    color: white;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

.navbar-brand h2 {
    margin-bottom: 30px;
    font-size: 1.5rem;
}

.navbar-menu {
    list-style: none;
    padding: 0;
}

.navbar-menu li {
    margin-bottom: 10px;
}

.nav-link {
    color: white;
    text-decoration: none;
    display: block;
    padding: 10px;
    border-radius: 5px;
    transition: background 0.3s;
}

.nav-link:hover,
.nav-link.router-link-active {
    background: #34495e;
}

.logout-btn {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
}

.logout-btn:hover {
    background: #c0392b;
}
</style>