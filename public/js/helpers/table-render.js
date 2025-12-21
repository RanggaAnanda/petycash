window.renderResponsiveTable = function ({
    tbodyId,
    data,
    columns,
    mobileDetail,
    perPage,
    currentPage
}) {
    const tbody = document.getElementById(tbodyId);
    tbody.innerHTML = '';

    const start = (currentPage - 1) * perPage;
    const pageData = data.slice(start, start + perPage);

    pageData.forEach((row, index) => {
        // ===== DESKTOP ROW =====
        let desktopCells = `
            <td class="p-3 text-center">${start + index + 1}</td>
        `;

        columns.forEach(col => {
            desktopCells += `
                <td class="p-3 ${col.align ?? ''}">
                    ${col.render(row)}
                </td>
            `;
        });

        // ===== MOBILE DETAIL =====
        let mobileRows = '';
        mobileDetail.forEach(item => {
            mobileRows += `
                <div class="flex justify-between text-sm py-1">
                    <span class="text-gray-500">${item.label}</span>
                    <span class="font-medium">${item.render(row)}</span>
                </div>
            `;
        });

        tbody.innerHTML += `
            <tr class="border-b hidden md:table-row">
                ${desktopCells}
            </tr>

            <tr class="md:hidden border-b">
                <td class="p-3">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">${start + index + 1}</span>
                        <button onclick="this.nextElementSibling.classList.toggle('hidden')">▼</button>
                    </div>

                    <div class="hidden mt-2 space-y-1">
                        ${mobileRows}
                    </div>
                </td>
            </tr>
        `;
    });
};
