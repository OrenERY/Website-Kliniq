<template>
    <div class="queues-container">
        <div class="page-header">
            <h1>Manajemen Antrian</h1>
            <button class="btn-primary" @click="showCreateModal = true">
                <i class="fas fa-plus"></i> Buat Antrian Baru
            </button>
        </div>

        <!-- Current Queue Display -->
        <div class="current-queue" v-if="currentQueue">
            <div class="current-display">
                <h2>Antrian Saat Ini</h2>
                <div class="queue-number">{{ currentQueue.queue_number }}</div>
                <div class="patient-info">
                    <h3>{{ currentQueue.patient.nama }}</h3>
                    <p>Poli: {{ currentQueue.poli.nama_poli }}</p>
                </div>
                <button class="btn-call" @click="callNext" :disabled="calling">
                    <i class="fas fa-bullhorn"></i> Panggil Berikutnya
                </button>
            </div>
        </div>

        <!-- Queue List -->
        <div class="queue-list">
            <div class="filter-section">
                <select v-model="selectedPoli" @change="fetchQueues">
                    <option value="">Semua Poli</option>
                    <option v-for="poli in polis" :key="poli.id" :value="poli.id">{{ poli.nama_poli }}</option>
                </select>
                <input type="date" v-model="selectedDate" @change="fetchQueues">
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No. Antrian</th>
                        <th>Nama Pasien</th>
                        <th>Poli</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="queue in queues" :key="queue.id" :class="{ 'current-row': queue.status === 'dipanggil' }">
                        <td>{{ queue.queue_number }}</td>
                        <td>{{ queue.patient.nama }}</td>
                        <td>{{ queue.poli.nama_poli }}</td>
                        <td>
                            <span :class="`status-badge status-${queue.status}`">
                                {{ getStatusText(queue.status) }}
                            </span>
                        </td>
                        <td>{{ formatTime(queue.created_at) }}</td>
                        <td>
                            <button v-if="queue.status === 'menunggu'" class="btn-call" @click="callQueue(queue.id)">
                                Panggil
                            </button>
                            <button v-if="queue.status === 'dipanggil'" class="btn-start" @click="startExamination(queue.id)">
                                Mulai Periksa
                            </button>
                            <button v-if="queue.status === 'dalam_pemeriksaan'" class="btn-complete" @click="completeQueue(queue.id)">
                                Selesai
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Create Queue Modal -->
        <div v-if="showCreateModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Buat Antrian Baru</h3>
                    <button @click="showCreateModal = false" class="close-btn">&times;</button>
                </div>
                <form @submit.prevent="createQueue">
                    <div class="form-group">
                        <label>Cari Pasien</label>
                        <input type="text" v-model="patientSearch" @input="searchPatients" placeholder="NIK atau Nama">
                        <ul v-if="patientResults.length" class="search-results">
                            <li v-for="patient in patientResults" :key="patient.id" @click="selectPatient(patient)">
                                {{ patient.nama }} ({{ patient.nik }})
                            </li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <label>Poli</label>
                        <select v-model="newQueue.poli_id" required>
                            <option value="">Pilih Poli</option>
                            <option v-for="poli in polis" :key="poli.id" :value="poli.id">{{ poli.nama_poli }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keluhan</label>
                        <textarea v-model="newQueue.complaint" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary" :disabled="!selectedPatient">Buat Antrian</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            queues: [],
            polis: [],
            currentQueue: null,
            selectedPoli: '',
            selectedDate: new Date().toISOString().split('T')[0],
            showCreateModal: false,
            newQueue: {
                poli_id: '',
                complaint: ''
            },
            selectedPatient: null,
            patientSearch: '',
            patientResults: [],
            calling: false
        };
    },
    methods: {
        async fetchQueues() {
            try {
                const params = {};
                if (this.selectedPoli) params.poli_id = this.selectedPoli;
                if (this.selectedDate) params.date = this.selectedDate;

                const response = await axios.get('/api/queues', { params });
                this.queues = response.data;
            } catch (error) {
                console.error('Error fetching queues:', error);
            }
        },
        async fetchPolis() {
            try {
                const response = await axios.get('/api/poli');
                this.polis = response.data;
            } catch (error) {
                console.error('Error fetching polis:', error);
            }
        },
        async fetchCurrentQueue() {
            try {
                const response = await axios.get('/api/queues/current', {
                    params: { poli_id: this.selectedPoli || undefined }
                });
                this.currentQueue = response.data;
            } catch (error) {
                console.error('Error fetching current queue:', error);
            }
        },
        async createQueue() {
            try {
                const response = await axios.post('/api/queues', {
                    patient_id: this.selectedPatient.id,
                    poli_id: this.newQueue.poli_id,
                    complaint: this.newQueue.complaint
                });

                this.showCreateModal = false;
                this.newQueue = { poli_id: '', complaint: '' };
                this.selectedPatient = null;
                this.patientSearch = '';
                this.fetchQueues();
                this.fetchCurrentQueue();
            } catch (error) {
                console.error('Error creating queue:', error);
            }
        },
        async callNext() {
            this.calling = true;
            try {
                await axios.post('/api/queues/call-next', {
                    poli_id: this.selectedPoli || undefined
                });
                this.fetchQueues();
                this.fetchCurrentQueue();
            } catch (error) {
                console.error('Error calling next queue:', error);
            } finally {
                this.calling = false;
            }
        },
        async callQueue(queueId) {
            try {
                await axios.put(`/api/queues/${queueId}`, { status: 'dipanggil' });
                this.fetchQueues();
                this.fetchCurrentQueue();
            } catch (error) {
                console.error('Error calling queue:', error);
            }
        },
        async startExamination(queueId) {
            try {
                await axios.put(`/api/queues/${queueId}`, { status: 'dalam_pemeriksaan' });
                this.fetchQueues();
                this.fetchCurrentQueue();
            } catch (error) {
                console.error('Error starting examination:', error);
            }
        },
        async completeQueue(queueId) {
            try {
                await axios.put(`/api/queues/${queueId}`, { status: 'selesai' });
                this.fetchQueues();
                this.fetchCurrentQueue();
            } catch (error) {
                console.error('Error completing queue:', error);
            }
        },
        async searchPatients() {
            if (this.patientSearch.length < 3) {
                this.patientResults = [];
                return;
            }
            try {
                const response = await axios.get(`/api/patients?search=${this.patientSearch}`);
                this.patientResults = response.data.data || response.data;
            } catch (error) {
                console.error('Error searching patients:', error);
            }
        },
        selectPatient(patient) {
            this.selectedPatient = patient;
            this.patientSearch = patient.nama;
            this.patientResults = [];
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
        formatTime(dateString) {
            return new Date(dateString).toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    },
    mounted() {
        this.fetchQueues();
        this.fetchPolis();
        this.fetchCurrentQueue();
    }
};
</script>

<style scoped>
.queues-container {
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.btn-primary {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.current-queue {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.current-display {
    text-align: center;
}

.queue-number {
    font-size: 4rem;
    font-weight: bold;
    color: #e74c3c;
    margin: 20px 0;
}

.patient-info h3 {
    margin: 10px 0;
    color: #333;
}

.btn-call {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 5px;
    font-size: 1.2rem;
    cursor: pointer;
    margin-top: 20px;
}

.queue-list {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.filter-section {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
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

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-menunggu { background: #fff3cd; color: #856404; }
.status-dipanggil { background: #cce5ff; color: #004085; }
.status-dalam_pemeriksaan { background: #d4edda; color: #155724; }
.status-selesai { background: #d1ecf1; color: #0c5460; }

.current-row {
    background: #fff3cd;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

.form-group {
    margin-bottom: 15px;
    padding: 0 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

input, select, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.search-results {
    list-style: none;
    border: 1px solid #ddd;
    max-height: 200px;
    overflow-y: auto;
    margin-top: 5px;
}

.search-results li {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.search-results li:hover {
    background: #f8f9fa;
}
</style>