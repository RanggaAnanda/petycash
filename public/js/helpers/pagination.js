window.renderPagination = function({ containerId, currentPage, perPage, totalData, onChange }) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';

    const totalPages = Math.ceil(totalData / perPage);
    if (totalPages <= 1) return; // tidak perlu pagination jika hanya 1 halaman

    // Base style tombol
    const baseBtn = 'px-3 py-1 text-sm rounded-md transition ';
    const lightBtn = 'bg-white text-gray-700 hover:bg-gray-200 border border-gray-300';
    const darkBtn = 'dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:border-gray-700';
    const activeBtn = 'bg-indigo-500 text-white shadow dark:bg-indigo-500';
    const disabledBtn = 'text-gray-400 cursor-not-allowed dark:text-gray-600';

    // Fungsi buat tombol
    function createBtn(label, page, isActive=false, isDisabled=false) {
        const btn = document.createElement('button');
        btn.textContent = label;
        btn.className = baseBtn + ' ' + lightBtn + ' ' + darkBtn;
        if (isActive) btn.className += ' ' + activeBtn;
        if (isDisabled) btn.className += ' ' + disabledBtn;
        btn.disabled = isDisabled;
        if (!isDisabled && !isActive) {
            btn.addEventListener('click', () => onChange(page));
        }
        return btn;
    }

    // Prev
    const prevBtn = createBtn('Prev', currentPage - 1, false, currentPage === 1);
    container.appendChild(prevBtn);

    // Pages (maksimal 3 tombol ditampilkan)
    let start = Math.max(1, currentPage - 1);
    let end = Math.min(totalPages, start + 2);
    if (end - start < 2) start = Math.max(1, end - 2);

    for (let i = start; i <= end; i++) {
        const pageBtn = createBtn(i, i, i === currentPage, false);
        container.appendChild(pageBtn);
    }

    // Next
    const nextBtn = createBtn('Next', currentPage + 1, false, currentPage === totalPages);
    container.appendChild(nextBtn);
};
