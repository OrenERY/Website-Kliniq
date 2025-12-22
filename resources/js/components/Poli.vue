<template>
    <div class="poli-container">
        <div class="page-header">
            <h1>Manajemen Poli</h1>
            <button class="btn-primary" @click="showModal = true">
                <i class="fas fa-plus"></i> Tambah Poli
            </button>
        </div>

        <div class="poli-grid">
            <div v-for="poli in polis" :key="poli.id" class="poli-card">
                <div class="poli-header">
                    <h3>{{ poli.nama_poli }}</h3>
                    <div class="poli-actions">
                        <button @click="editPoli(poli)" class="btn-edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button @click="deletePoli(poli.id)" class="btn-delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="poli-info">
                    <p><strong>Kode:</strong> {{ poli.kode_poli }}</p>
                    <p><strong>Deskripsi:</strong> {{ poli.deskripsi || 'Tidak ada deskripsi' }}</p>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ isEditing ? 'Edit Poli' : 'Tambah Poli' }}</h3>
                    <button @click="closeModal" class="close-btn">&times;</button>
                </div>
                <form @submit.prevent="savePoli">
                    <div class="form-group">
                        <label>Nama Poli</label>
                        <input v-model="poliForm.nama_poli" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Poli</label>
                        <input v-model="poliForm.kode_poli" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea v-model="poliForm.deskripsi"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">
                        {{ isEditing ? 'Update' : 'Simpan' }}
                    </button>
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
            polis: [],
            showModal: false,
            isEditing: false,
            poliForm: {
                nama_poli: '',
                kode_poli: '',
                deskripsi: ''
            },
            editingId: null
        };
    },
    methods: {
        async fetchPolis() {
            try {
                const response = await axios.get('/api/poli');
                this.polis = response.data;
            } catch (error) {
                console.error('Error fetching polis:', error);
            }
        },
        editPoli(poli) {
            this.isEditing = true;
            this.editingId = poli.id;
            this.poliForm = { ...poli };
            this.showModal = true;
        },
        async savePoli() {
            try {
                if (this.isEditing) {
                    await axios.put(`/api/poli/${this.editingId}`, this.poliForm);
                } else {
                    await axios.post('/api/poli', this.poliForm);
                }
                this.closeModal();
                this.fetchPolis();
            } catch (error) {
                console.error('Error saving poli:', error);
            }
        },
        async deletePoli(id) {
            if (confirm('Apakah Anda yakin ingin menghapus poli ini?')) {
                try {
                    await axios.delete(`/api/poli/${id}`);
                    this.fetchPolis();
                } catch (error) {
                    console.error('Error deleting poli:', error);
                }
            }
        },
        closeModal() {
            this.showModal = false;
            this.isEditing = false;
            this.poliForm = {
                nama_poli: '',
                kode_poli: '',
                deskripsi: ''
            };
            this.editingId = null;
        }
    },
    mounted() {
        this.fetchPolis();
    }
};
</script>

<style scoped>
.poli-container {
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

.poli-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.poli-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.poli-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.poli-header h3 {
    margin: 0;
    color: #333;
}

.poli-actions {
    display: flex;
    gap: 10px;
}

.btn-edit, .btn-delete {
    background: none;
    border: none;
    padding: 5px;
    cursor: pointer;
    border-radius: 3px;
}

.btn-edit {
    color: #3498db;
}

.btn-delete {
    color: #e74c3c;
}

.poli-info p {
    margin: 5px 0;
    color: #666;
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

input, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
</style>