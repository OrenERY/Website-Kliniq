<template>
    <div class="patients-container">
        <div class="page-header">
            <h1>Manajemen Pasien</h1>
            <button class="btn-primary" @click="showModal = true">
                <i class="fas fa-plus"></i> Tambah Pasien
            </button>
        </div>
        
        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" v-model="searchQuery" placeholder="Cari pasien berdasarkan NIK, nama, atau no. RM..."
                   @keyup.enter="searchPatients">
            <button @click="searchPatients">
                <i class="fas fa-search"></i>
            </button>
        </div>
        
        <!-- Patients Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No. RM</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>No. Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="patient in patients" :key="patient.id">
                        <td>{{ patient.no_rm }}</td>
                        <td>{{ patient.nik }}</td>
                        <td>{{ patient.nama }}</td>
                        <td>{{ patient.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ formatDate(patient.tanggal_lahir) }}</td>
                        <td>{{ patient.no_telepon }}</td>
                        <td>
                            <button class="btn-edit" @click="editPatient(patient)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete" @click="deletePatient(patient.id)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Modal Tambah/Edit Pasien -->
        <div v-if="showModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>{{ editingPatient ? 'Edit Pasien' : 'Tambah Pasien Baru' }}</h2>
                    <button class="close-btn" @click="closeModal">&times;</button>
                </div>
                <form @submit.prevent="savePatient">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>NIK *</label>
                            <input type="text" v-model="form.nik" required maxlength="16">
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" v-model="form.nama" required>
                        </div>
                        <div class="form-group">
                            <label>Tempat Lahir *</label>
                            <input type="text" v-model="form.tempat_lahir" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir *</label>
                            <input type="date" v-model="form.tanggal_lahir" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin *</label>
                            <select v-model="form.jenis_kelamin" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Alamat *</label>
                            <textarea v-model="form.alamat" required rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>No. Telepon *</label>
                            <input type="text" v-model="form.no_telepon" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Kepala Keluarga</label>
                            <input type="text" v-model="form.nama_kk">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" @click="closeModal">Batal</button>
                        <button type="submit" class="btn-primary">
                            {{ editingPatient ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            patients: [],
            searchQuery: '',
            showModal: false,
            editingPatient: null,
            form: {
                nik: '',
                nama: '',
                tempat_lahir: '',
                tanggal_lahir: '',
                jenis_kelamin: 'L',
                alamat: '',
                no_telepon: '',
                nama_kk: ''
            }
        };
    },
    methods: {
        async fetchPatients() {
            try {
                const response = await this.$axios.get('/api/patients');
                this.patients = response.data;
            } catch (error) {
                console.error('Error fetching patients:', error);
                alert('Gagal mengambil data pasien');
            }
        },
        async searchPatients() {
            try {
                if (!this.searchQuery.trim()) {
                    this.fetchPatients();
                    return;
                }
                const response = await this.$axios.get(`/api/patients/search/${this.searchQuery}`);
                this.patients = response.data;
            } catch (error) {
                console.error('Error searching patients:', error);
            }
        },
        editPatient(patient) {
            this.editingPatient = patient;
            this.form = { ...patient };
            this.showModal = true;
        },
        async savePatient() {
            try {
                if (this.editingPatient) {
                    await this.$axios.put(`/api/patients/${this.editingPatient.id}`, this.form);
                } else {
                    await this.$axios.post('/api/patients', this.form);
                }
                this.closeModal();
                this.fetchPatients();
                alert('Data pasien berhasil disimpan');
            } catch (error) {
                console.error('Error saving patient:', error);
                alert('Gagal menyimpan data pasien');
            }
        },
        async deletePatient(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus pasien ini?')) return;
            
            try {
                await this.$axios.delete(`/api/patients/${id}`);
                this.fetchPatients();
                alert('Pasien berhasil dihapus');
            } catch (error) {
                console.error('Error deleting patient:', error);
                alert('Gagal menghapus pasien');
            }
        },
        closeModal() {
            this.showModal = false;
            this.editingPatient = null;
            this.resetForm();
        },
        resetForm() {
            this.form = {
                nik: '',
                nama: '',
                tempat_lahir: '',
                tanggal_lahir: '',
                jenis_kelamin: 'L',
                alamat: '',
                no_telepon: '',
                nama_kk: ''
            };
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('id-ID');
        }
    },
    mounted() {
        this.fetchPatients();
    }
};
</script>

<style scoped>
.patients-container {
    padding: 20px;
}
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.page-header h1 {
    margin: 0;
    color: #333;
}
.btn-primary {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.btn-primary:hover {
    background: #45a049;
}
.search-bar {
    display: flex;
    margin-bottom: 20px;
    max-width: 600px;
}
.search-bar input {
    flex: 1;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px 0 0 6px;
    font-size: 16px;
}
.search-bar button {
    padding: 12px 20px;
    background: #2196F3;
    color: white;
    border: none;
    border-radius: 0 6px 6px 0;
    cursor: pointer;
}
.table-container {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
table {
    width: 100%;
    border-collapse: collapse;
}
th {
    background: #f8f9fa;
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #eee;
}
td {
    padding: 16px;
    border-bottom: 1px solid #eee;
}
.btn-edit {
    background: #FFC107;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 8px;
}
.btn-delete {
    background: #F44336;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
}
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: white;
    border-radius: 10px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}
.modal-header h2 {
    margin: 0;
    color: #333;
}
.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}
form {
    padding: 20px;
}
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}
</style>