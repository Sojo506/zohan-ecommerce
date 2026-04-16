<div class="admin-panel-card">
    <div class="admin-panel-card-header">
        <div>
            <h4 class="mb-0">Auditoría del sistema</h4>
            <small class="text-muted">La bitácora global fue reemplazada por auditoría por tupla en cada tabla.</small>
        </div>
    </div>

    <div class="admin-panel-card-body">
        <div class="alert alert-warning mb-0">
            Esta pantalla quedó obsoleta. La auditoría ahora se almacena directamente en cada registro mediante
            <code>FECHA_CREACION</code>, <code>FECHA_MODIFICACION</code>, <code>CREADO_POR</code>,
            <code>MODIFICADO_POR</code> y <code>ACCION</code>. Si luego quieres un histórico centralizado,
            habría que diseñar una tabla de historial adicional.
        </div>
    </div>
</div>
