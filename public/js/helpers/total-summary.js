window.renderTotal = function ({
    data,
    field,
    targetId,
    prefix = 'Rp'
}) {
    const total = data.reduce((sum, row) => sum + (row[field] ?? 0), 0);
    document.getElementById(targetId).textContent =
        `${prefix} ${total.toLocaleString('id-ID')}`;
};
