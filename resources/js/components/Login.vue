<template>
    <div class="login">
        <div class="login-container">
            <h1>Login KLINIQ</h1>
            <form @submit.prevent="login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" v-model="email" type="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" v-model="password" type="password" placeholder="Password" required>
                </div>
                <button type="submit" :disabled="loading">
                    {{ loading ? 'Logging in...' : 'Login' }}
                </button>
            </form>
            <p v-if="error" class="error">{{ error }}</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            email: '',
            password: '',
            loading: false,
            error: ''
        };
    },
    methods: {
        async login() {
            this.loading = true;
            this.error = '';
            try {
                const response = await axios.post('/api/login', {
                    email: this.email,
                    password: this.password
                });

                localStorage.setItem('token', response.data.access_token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;

                this.$router.push('/');
            } catch (error) {
                this.error = error.response?.data?.message || 'Login failed';
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

<style scoped>
.login {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #f5f7fa;
}

.login-container {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

h1 {
    text-align: center;
    margin-bottom: 2rem;
    color: #2c3e50;
}

.form-group {
    margin-bottom: 1rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

button {
    width: 100%;
    padding: 0.75rem;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.3s;
}

button:hover:not(:disabled) {
    background: #2980b9;
}

button:disabled {
    background: #bdc3c7;
    cursor: not-allowed;
}

.error {
    color: #e74c3c;
    text-align: center;
    margin-top: 1rem;
}
</style>