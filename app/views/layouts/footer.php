            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-3 text-center text-xs text-gray-500">
                Asociación de Vendedores de Comida San Miguel de Yahuarcocha <?= date('Y'); ?> ©
            </footer>
        </div>
    </div>

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
    </script>
</body>
</html>
