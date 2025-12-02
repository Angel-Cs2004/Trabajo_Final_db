    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 px-6 py-3 text-center text-xs text-gray-500">
        Asociación de Vendedores de Comida San Miguel de Yahuarcocha <?= date('Y'); ?> ©
    </footer>

    <script>
        // Función toggle de submenús
        function toggleSubmenu(submenuId, iconId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(iconId);
            if (submenu) {
                submenu.classList.toggle('hidden');
            }
            if (icon) {
                icon.classList.toggle('rotate-180');
            }
        }

        //  BUSCADOR PARA USUARIOS 
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener("input", function () {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll("#tableBody tr");

                rows.forEach(function(row) {
                    let nombre = row.querySelector("td:nth-child(1)").textContent.toLowerCase();
                    row.style.display = nombre.includes(filter) ? "" : "none";
                });
            });
        }

        //  BUSCADOR PARA ROLES 
        const searchRoles = document.getElementById("searchRoles");
        if (searchRoles) {
            searchRoles.addEventListener("input", function () {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll("#rolesTableBody tr");

                rows.forEach(function(row) {
                    let nombre = row.querySelector("td:nth-child(1)").textContent.toLowerCase();
                    row.style.display = nombre.includes(filter) ? "" : "none";
                });
            });
        }

        //  BUSCADOR PARA NEGOCIOS 
        const searchNegocios = document.getElementById("searchInput");
        if (searchNegocios && document.getElementById("negociosTableBody")) {
            searchNegocios.addEventListener("input", function () {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll("#negociosTableBody tr");

                rows.forEach(function(row) {
                    let nombre = row.querySelector("td:nth-child(1)").textContent.toLowerCase();
                    row.style.display = nombre.includes(filter) ? "" : "none";
                });
            });
        }

            //  BUSCADOR PARA PARÁMETROS DE IMAGEN 
        const searchParametros = document.getElementById("searchInput");
        if (searchParametros && document.getElementById("tableBody")) {
            searchParametros.addEventListener("input", function () {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll("#tableBody tr");

                rows.forEach(function(row) {
                    let nombre = row.querySelector("td:nth-child(1)").textContent.toLowerCase();
                    row.style.display = nombre.includes(filter) ? "" : "none";
                });
            });
        }


    </script>
</body>
</html>
