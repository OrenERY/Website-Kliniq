<template>
    <div class="dashboard">
        <div class="header">
            <h1>Dashboard KLINIQ</h1>
            <p>Selamat datang, {{ user.name }}</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #4CAF50;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ stats.total_patients }}</h3>
                    <p>Total Pasien</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: #2196F3;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ stats.today_queues }}</h3>
                    <p>Antrian Hari Ini</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: #FF9800;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ stats.completed_today }}</h3>
                    <p>Selesai Hari Ini</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: #F44336;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ stats.waiting_today }}</h3>
                    <p>Dalam Antrian</p>
                </div>
            </div>
        </div>
        
        <div class="content-grid">
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <button @click="goTo('patients')" class="action-btn">
                    <i class="fas fa-user-plus"></i>
                    Pendaftaran Pasien
                </button>
                <button @click="goTo('queues')" class="action-btn">
                    <i class="fas fa-ticket-alt"></i>
                    Buat Antrian
                </button>
                <button @click="goTo('queues')" class="action-btn">
                    <i class="fas fa-bullhorn"></i>
                    Panggil Antrian
                </button>
            </div>
            
            <div class="today-queues">
                <h3>Antrian Hari Ini</h3>
                <table>
                    <thead>
                        <tr>
                            <th>No. Antrian</th>
                            <th>Nama Pasien</th>
                            <th>Poli</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="queue in todayQueues" :key="queue.id">
                            <td>{{ queue.queue_number }}</td>
                            <td>{{ queue.patient.nama }}</td>
                            <td>{{ queue.poli.nama_poli }}</td>
                            <td>
                                <span :class="`status-badge status-${queue.status}`">
                                    {{ getStatusText(queue.status) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            stats: {
                total_patients: 0,
                today_queues: 0,
                completed_today: 0,
                waiting_today: 0,
                total_poli: 0
            },
            todayQueues: [],
            user: JSON.parse(localStorage.getItem('user') || '{}')
        };
    },
    methods: {
        goTo(route) {
            this.$router.push(`/${route}`);
        },
        getStatusText(status) {
            const statusMap = {
                'menunggu': 'Menunggu',
                'dipanggil': 'Dipanggil',
                'dalam_pemeriksaan': 'Diperiksa',
                'selesai': 'Selesai',
                'batal': 'Batal'
            };
            return statusMap[status] || status;
        },
        async fetchStats() {
            try {
                const response = await this.$axios.get('/api/dashboard/stats');
                this.stats = response.data;
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        },
        async fetchTodayQueues() {
            try {
                const response = await this.$axios.get('/api/dashboard/today-queues');
                this.todayQueues = response.data;
            } catch (error) {
                console.error('Error fetching today queues:', error);
            }
        }
    },
    mounted() {
        this.fetchStats();
        this.fetchTodayQueues();
    }
};
</script>

<style scoped>
.dashboard {
    padding: 20px;
}
.header {
    margin-bottom: 30px;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    margin-right: 15px;
}
.stat-info h3 {
    margin: 0;
    font-size: 28px;
    color: #333;
}
.stat-info p {
    margin: 5px 0 0;
    color: #666;
}
.content-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 30px;
}
.quick-actions {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.quick-actions h3 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
}
.action-btn {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 100%;
    padding: 15px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    color: #333;
    transition: background 0.3s;
}
.action-btn:hover {
    background: #e9ecef;
}
.action-btn i {
    margin-right: 10px;
    font-size: 18px;
}
.today-queues {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.today-queues h3 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}
.status-menunggu {
    background: #fff3cd;
    color: #856404;
}
.status-dipanggil {
    background: #cce5ff;
    color: #004085;
}
.status-dalam_pemeriksaan {
    background: #d4edda;
    color: #155724;
}
.status-selesai {
    background: #d1ecf1;
    color: #0c5460;
}
.status-batal {
    background: #f8d7da;
    color: #721c24;
}
</style>