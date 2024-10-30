<script>
let currentPage = 1;
const itemsPerPage = 5; // Jumlah komentar per halaman
const comments = [...document.querySelectorAll('.comment')]; // Mengambil semua elemen komentar

function showPage(page) {
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    // Menampilkan komentar untuk halaman tertentu
    comments.forEach((comment, index) => {
        comment.style.display = (index >= start && index < end) ? 'block' : 'none';
    });

    // Update halaman saat ini
    document.getElementById('current-page').innerText = page;
    document.getElementById('prev-button').disabled = (page === 1);
    document.getElementById('next-button').disabled = (page === Math.ceil(comments.length / itemsPerPage));
}

function nextPage() {
    if (currentPage < Math.ceil(comments.length / itemsPerPage)) {
        currentPage++;
        showPage(currentPage);
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
    }
}

// Tampilkan halaman pertama saat dimuat
document.addEventListener('DOMContentLoaded', () => {
    showPage(currentPage);
});
</script>
