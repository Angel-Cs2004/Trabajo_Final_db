    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 px-6 py-3 text-center text-xs text-gray-500">
        Asociación de Vendedores de Comida San Miguel de Yahuarcocha <?= date('Y'); ?> ©
    </footer>

<script>

    function toggleSubmenu(submenuId, iconId) {
    const submenu = document.getElementById(submenuId);
    const icon = document.getElementById(iconId);
    if (submenu) submenu.classList.toggle("hidden");
    if (icon) icon.classList.toggle("rotate-180");
    }

    function initPaginationWithSearch({
    tableBodyId = "tableBody",
    recordsPerPageId = "recordsPerPage",
    searchInputId = "searchInput",
    columnIndex = 1,

    currentPageId = "currentPage",
    paginationInfoId = "paginationInfo",
    nextBtnId = "nextPage",
    prevBtnId = "prevPage",
    } = {}) {

    const tableBody = document.getElementById(tableBodyId);
    const recordsPerPage = document.getElementById(recordsPerPageId);

    if (!tableBody || !recordsPerPage) return;

    const searchInput = document.getElementById(searchInputId);

    const currentPageEl = document.getElementById(currentPageId);
    const paginationInfoEl = document.getElementById(paginationInfoId);
    const nextBtn = document.getElementById(nextBtnId);
    const prevBtn = document.getElementById(prevBtnId);


    const allRows = Array.from(tableBody.querySelectorAll("tr"));

    let filteredRows = [...allRows];
    let currentPage = 1;

    
    let pageBeforeSearch = 1;
    let lastFilter = "";

    function applyFilter(filterText) {
        const filter = (filterText || "").trim().toLowerCase();

        if (filter && !lastFilter) pageBeforeSearch = currentPage;

    
        if (!filter && lastFilter) currentPage = pageBeforeSearch;

        lastFilter = filter;

        if (!filter) {
        filteredRows = [...allRows];
        return;
        }

        filteredRows = allRows.filter((row) => {
        const cellText =
            row.querySelector(`td:nth-child(${columnIndex})`)?.textContent.toLowerCase() || "";
        return cellText.includes(filter);
        });

        currentPage = 1;
    }

    function renderTable() {
        const perPage = parseInt(recordsPerPage.value || "10", 10);
        const totalRows = filteredRows.length;
        const totalPages = Math.max(1, Math.ceil(totalRows / perPage));

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const start = (currentPage - 1) * perPage;
        const end = start + perPage;


        allRows.forEach((row) => (row.style.display = "none"));

    
        filteredRows.forEach((row, idx) => {
        row.style.display = (idx >= start && idx < end) ? "" : "none";
        });

        if (currentPageEl) currentPageEl.innerText = currentPage;

        if (paginationInfoEl) {
        paginationInfoEl.innerText =
            totalRows === 0
            ? "No hay registros"
            : `Mostrando ${start + 1} a ${Math.min(end, totalRows)} de ${totalRows} registros`;
        }

        if (prevBtn) prevBtn.disabled = currentPage === 1;
        if (nextBtn) nextBtn.disabled = currentPage === totalPages;
    }

    prevBtn?.addEventListener("click", () => {
        if (currentPage > 1) {
        currentPage--;
        renderTable();
        }
    });

    nextBtn?.addEventListener("click", () => {
        const perPage = parseInt(recordsPerPage.value || "10", 10);
        const totalPages = Math.max(1, Math.ceil(filteredRows.length / perPage));
        if (currentPage < totalPages) {
        currentPage++;
        renderTable();
        }
    });

    recordsPerPage.addEventListener("change", () => {
        currentPage = 1;
        renderTable();
    });

    // Buscador integrado
    if (searchInput) {
        searchInput.addEventListener("input", function () {
        applyFilter(this.value);
        renderTable();
        });
    }

    renderTable();
    }

    document.addEventListener("DOMContentLoaded", () => {
    initPaginationWithSearch({
        tableBodyId: "tableBody",
        recordsPerPageId: "recordsPerPage",
        searchInputId: "searchInput",
        columnIndex: 1
    });

    
    
    });
</script>


</body>
</html>
