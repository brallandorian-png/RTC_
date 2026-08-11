<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario RTC</title>
    <style>
        :root {
            --primary: #2563eb;
            --dark: #1e293b;
            --light: #f8fafc;
            --accent: #16a34a;
            --danger: #dc2626;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: system-ui, -apple-system, sans-serif; }
        body { background: #f1f5f9; color: var(--dark); padding-bottom: 50px; }
        
        header { background: var(--dark); color: white; padding: 1.5rem; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); position: relative; }
        header h1 { font-size: 2rem; margin-bottom: 0.5rem; letter-spacing: 0.5px; }

        .auth-container { position: absolute; top: 1.5rem; right: 1.5rem; display: flex; align-items: center; gap: 0.8rem; }
        .user-status { font-size: 0.9rem; color: #cbd5e1; }

        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        
        /* Controles y Búsqueda */
        .controls { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
        .search-bar { flex: 1; padding: 0.8rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; }
        .btn { background: var(--primary); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.2s; }
        .btn:hover { opacity: 0.9; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-admin { background: #475569; display: none; }
        .btn-login { background: #0284c7; }
        .btn-logout { background: #64748b; font-size: 0.85rem; padding: 0.5rem 1rem; display: none; }
        .btn-delete { background: var(--danger); padding: 0.4rem 0.8rem; font-size: 0.8rem; margin-top: 0.5rem; width: 100%; }

        /* Grid de Productos */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem; }
        .card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: transform 0.2s; display: flex; flex-direction: column; justify-content: space-between; }
        .card:hover { transform: translateY(-4px); }
        .card img { width: 100%; height: 220px; object-fit: contain; background: #fff; padding: 10px; border-bottom: 1px solid #f1f5f9; }
        .card-body { padding: 1.2rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .badge { background: #e2e8f0; color: #475569; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: bold; text-transform: uppercase; align-self: flex-start; }
        .card-title { font-size: 1.1rem; margin: 0.5rem 0; color: var(--dark); }
        .card-desc { font-size: 0.85rem; color: #64748b; margin-bottom: 1rem; line-height: 1.3; }
        .price { font-size: 1.4rem; font-weight: bold; color: var(--accent); margin-bottom: 0.5rem; }

        /* Modales */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: white; padding: 2rem; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .modal-content h2 { margin-bottom: 1rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.3rem; font-size: 0.9rem; font-weight: 600; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 6px; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem; }
        .btn-close { background: #e2e8f0; color: #334155; }
        .error-msg { color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem; display: none; }
    </style>
</head>
<body>

    <header>
        <h1>📦 Inventario RTC</h1>
        <p>Precios y disponibilidad en tiempo real</p>
        <div class="auth-container">
            <span class="user-status" id="userStatusText">Modo Visitante</span>
            <button class="btn btn-login" id="loginBtn" onclick="abrirModalLogin()">🔑 Iniciar Sesión</button>
            <button class="btn btn-logout" id="logoutBtn" onclick="cerrarSesion()">Cerrar Sesión</button>
        </div>
    </header>

    <div class="container">
        <div class="controls">
            <input type="text" id="searchInput" class="search-bar" placeholder="🔍 Buscar producto por nombre o categoría..." onkeyup="filtrarProductos()">
            <button class="btn btn-admin" id="btnAddProduct" onclick="abrirModalAdmin()">+ Añadir Producto</button>
        </div>

        <div class="grid" id="productGrid">
            <p style="grid-column: 1/-1; text-align: center; color: #64748b;">⏳ Cargando inventario desde la base de datos...</p>
        </div>
    </div>

    <!-- Modal Login -->
    <div class="modal" id="loginModal">
        <div class="modal-content">
            <h2>Acceso Administrador</h2>
            <form id="loginForm" onsubmit="ejecutarLogin(event)">
                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" id="authUser" required placeholder="RTC">
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" id="authPass" required placeholder="••••••••">
                </div>
                <div class="error-msg" id="loginError">Usuario o contraseña incorrectos.</div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-close" onclick="cerrarModalLogin()">Cancelar</button>
                    <button type="submit" class="btn">Entrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Añadir Producto -->
    <div class="modal" id="adminModal">
        <div class="modal-content" style="max-width: 500px;">
            <h2>Agregar Nuevo Producto</h2>
            <form id="productForm" onsubmit="guardarProducto(event)">
                <div class="form-group">
                    <label>Nombre del Producto</label>
                    <input type="text" id="nombre" required placeholder="Ej. Bocina Bluetooth">
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <select id="categoria">
                        <option value="Audio">Audio / Bocinas</option>
                        <option value="Telefonía">Telefonía / Celulares</option>
                        <option value="Accesorios">Accesorios / Cables</option>
                        <option value="Cómputo">Cómputo / Periféricos</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Precio ($ MXN)</label>
                    <input type="number" id="precio" step="0.01" required placeholder="350">
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea id="descripcion" rows="2" placeholder="Detalles clave..."></textarea>
                </div>
                <div class="form-group">
                    <label>Imagen del Producto</label>
                    <input type="file" id="imagenInput" accept="image/*" onchange="convertirImagen(event)">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-close" onclick="cerrarModalAdmin()">Cancelar</button>
                    <button type="submit" class="btn" id="btnSubmit">Guardar en Inventario</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const ADMIN_USER = "RTC";
        const ADMIN_PASS = "RTC1234";

        let baseDeDatos = [];
        let esAdmin = localStorage.getItem("inventario_auth") === "true";
        let imagenTemporal = "";

        function getAuthHeaders() {
            return {
                'X-Admin-User': localStorage.getItem('adminUser') || '',
                'X-Admin-Password': localStorage.getItem('adminPass') || ''
            };
        }

        function actualizarInterfazAuth() {
            const btnAdd = document.getElementById("btnAddProduct");
            const loginBtn = document.getElementById("loginBtn");
            const logoutBtn = document.getElementById("logoutBtn");
            const statusText = document.getElementById("userStatusText");

            if (esAdmin) {
                btnAdd.style.display = "block";
                loginBtn.style.display = "none";
                logoutBtn.style.display = "block";
                statusText.textContent = "Modo: Administrador";
            } else {
                btnAdd.style.display = "none";
                loginBtn.style.display = "block";
                logoutBtn.style.display = "none";
                statusText.textContent = "Modo Visitante";
            }
            renderizarProductos(baseDeDatos);
        }

        async function cargarProductos() {
            const grid = document.getElementById("productGrid");
            grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #64748b;">⏳ Cargando datos del servidor...</p>';

            try {
                const res = await fetch('./api.php?t=' + Date.now());
                const rawText = await res.text();

                let json;
                try {
                    json = JSON.parse(rawText);
                } catch (e) {
                    throw new Error("El servidor devolvió una respuesta no válida (HTML/Error PHP): " + rawText.substring(0, 100));
                }

                if (json.success && Array.isArray(json.data)) {
                    baseDeDatos = json.data;
                    renderizarProductos(baseDeDatos);
                } else {
                    grid.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: var(--danger);">❌ ${json.message || 'Error al obtener datos'}</p>`;
                }
            } catch (error) {
                console.error("Error al cargar productos:", error);
                grid.innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; color: var(--danger); background: #fee2e2; padding: 1rem; border-radius: 8px;">
                        ⚠️ <b>Fallo de conexión con la API:</b><br>${error.message}
                    </div>`;
            }
        }

        function renderizarProductos(lista) {
            const grid = document.getElementById("productGrid");
            grid.innerHTML = "";

            if (!lista || lista.length === 0) {
                grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #64748b;">No hay productos registrados en el inventario.</p>';
                return;
            }

            lista.forEach(p => {
                const btnEliminarHtml = esAdmin ? `<button class="btn btn-delete" onclick="eliminarProducto(${p.id})">🗑️ Eliminar Producto</button>` : '';
                const imgSrc = p.imagen_url && p.imagen_url.trim() !== "" ? p.imagen_url : "https://via.placeholder.com/300x300?text=Sin+Imagen";

                grid.innerHTML += `
                    <div class="card">
                        <div>
                            <img src="${imgSrc}" alt="${p.nombre}">
                            <div class="card-body">
                                <span class="badge">${p.categoria}</span>
                                <h3 class="card-title">${p.nombre}</h3>
                                <p class="card-desc">${p.descripcion || p.desc || 'Sin descripción disponible.'}</p>
                            </div>
                        </div>
                        <div class="card-body" style="padding-top: 0;">
                            <div class="price">$${parseFloat(p.precio).toFixed(2)} MXN</div>
                            ${btnEliminarHtml}
                        </div>
                    </div>
                `;
            });
        }

        function filtrarProductos() {
            const query = document.getElementById("searchInput").value.toLowerCase();
            const filtrados = baseDeDatos.filter(p => 
                p.nombre.toLowerCase().includes(query) || 
                p.categoria.toLowerCase().includes(query)
            );
            renderizarProductos(filtrados);
        }

        function abrirModalLogin() { document.getElementById("loginModal").style.display = "flex"; }
        function cerrarModalLogin() { 
            document.getElementById("loginModal").style.display = "none"; 
            document.getElementById("loginError").style.display = "none";
            document.getElementById("loginForm").reset();
        }

        function ejecutarLogin(e) {
            e.preventDefault();
            const u = document.getElementById("authUser").value.trim();
            const p = document.getElementById("authPass").value.trim();

            if (u === ADMIN_USER && p === ADMIN_PASS) {
                esAdmin = true;
                localStorage.setItem("inventario_auth", "true");
                localStorage.setItem("adminUser", u);
                localStorage.setItem("adminPass", p);
                cerrarModalLogin();
                actualizarInterfazAuth();
                cargarProductos();
            } else {
                document.getElementById("loginError").style.display = "block";
            }
        }

        function cerrarSesion() {
            esAdmin = false;
            localStorage.removeItem("inventario_auth");
            localStorage.removeItem("adminUser");
            localStorage.removeItem("adminPass");
            actualizarInterfazAuth();
            cargarProductos();
        }

        function abrirModalAdmin() { document.getElementById("adminModal").style.display = "flex"; }
        function cerrarModalAdmin() { 
            document.getElementById("adminModal").style.display = "none";
            document.getElementById("productForm").reset();
            imagenTemporal = "";
        }

        function convertirImagen(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const MAX_WIDTH = 500;
                    const MAX_HEIGHT = 500;
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    imagenTemporal = canvas.toDataURL('image/jpeg', 0.6);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        async function guardarProducto(e) {
            e.preventDefault();
            if (!esAdmin) {
                alert("🔒 Debes autenticarte como Administrador para guardar productos.");
                return;
            }

            const btnSubmit = document.getElementById("btnSubmit");
            const textoOriginal = btnSubmit.textContent;
            btnSubmit.disabled = true;
            btnSubmit.textContent = "Guardando...";

            const payload = {
                nombre: document.getElementById("nombre").value.trim(),
                categoria: document.getElementById("categoria").value,
                precio: parseFloat(document.getElementById("precio").value),
                descripcion: document.getElementById("descripcion").value.trim(),
                imagen_url: imagenTemporal || ""
            };

            try {
                const res = await fetch('./api.php', {
                    method: 'POST',
                    headers: {
                        ...getAuthHeaders(),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const rawText = await res.text();
                let result;
                try {
                    result = JSON.parse(rawText);
                } catch (err) {
                    alert("❌ El servidor no devolvió una respuesta JSON válida:\n" + rawText.substring(0, 150));
                    throw new Error("Respuesta no válida del backend");
                }

                if (result.success) {
                    alert("✅ Producto guardado correctamente en la base de datos.");
                    cerrarModalAdmin();
                    await cargarProductos();
                } else {
                    alert("❌ Error: " + (result.message || "No se pudo guardar el producto."));
                }
            } catch (error) {
                console.error("Error al guardar producto:", error);
                alert("❌ Fallo de conexión: " + error.message);
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.textContent = textoOriginal;
            }
        }

        async function eliminarProducto(id) {
            if (!esAdmin) return;
            if (!confirm("¿Estás seguro de que deseas eliminar este producto del inventario?")) return;

            try {
                const res = await fetch('./api.php?id=' + id, {
                    method: 'DELETE',
                    headers: getAuthHeaders()
                });

                const rawText = await res.text();
                let result = JSON.parse(rawText);

                if (result.success) {
                    await cargarProductos();
                } else {
                    alert("❌ Error al eliminar: " + result.message);
                }
            } catch (error) {
                console.error("Error al eliminar producto:", error);
                alert("❌ Error de comunicación al intentar eliminar.");
            }
        }

        // Inicialización de la app
        actualizarInterfazAuth();
        cargarProductos();
    </script>
</body>
</html>
