<template>
    <div>
      <h1>Unggah File</h1>
      <form @submit.prevent="handleUpload">
        <input type="file" @change="handleFileChange" />
        <button type="submit">Unggah</button>
      </form>
      <div v-if="fileUrl">
        <p>File berhasil diunggah! Lihat di <a :href="fileUrl" target="_blank">sini</a></p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        file: null,
        fileUrl: ''
      };
    },
    methods: {
      handleFileChange(event) {
        this.file = event.target.files[0];
      },
  
      async handleUpload() {
        if (!this.file) {
          alert('Silakan pilih file terlebih dahulu.');
          return;
        }
  
        let formData = new FormData();
        formData.append('file', this.file);
  
        // Ambil CSRF token dari meta tag di halaman
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
        try {
          const response = await fetch('/upload', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken // Tambahkan header CSRF
            },
            body: formData
          });
  
          if (response.ok) {
            const data = await response.json();
            this.fileUrl = data.url;
          } else {
            alert('Gagal mengunggah file.');
          }
        } catch (error) {
          console.error('Terjadi kesalahan:', error);
          alert('Terjadi kesalahan saat mengunggah file.');
        }
      }
    }
  };
  </script>