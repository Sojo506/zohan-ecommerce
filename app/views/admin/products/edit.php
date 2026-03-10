<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between">
        <h4>Editar producto</h4>

        <a href="<?= App::url('/admin/products') ?>" class="btn btn-dark">
            Volver a la lista
        </a>
    </div>

    <div class="admin-panel-card-body">

        <form method="POST" action="<?= App::url('/admin/products/update') ?>">

            <input type="hidden" name="id"
                value="<?= $product['ID_PRODUCTO'] ?>">

            <div class="mb-3">
                <label>SKU</label>
                <input type="text"
                    name="sku"
                    class="form-control"
                    value="<?= htmlspecialchars($product['SKU']) ?>">
            </div>

            <div class="mb-3">
                <label>Nombre</label>
                <input type="text"
                    name="nombre"
                    class="form-control"
                    value="<?= htmlspecialchars($product['NOMBRE']) ?>">
            </div>

            <div class="mb-3">
                <label>Descripción</label>
                <textarea name="descripcion"
                    class="form-control"><?= htmlspecialchars($product['DESCRIPCION']) ?></textarea>
            </div>

            <div class="mb-3">
                <label>Precio</label>
                <input type="number"
                    step="0.01"
                    name="precio"
                    class="form-control"
                    value="<?= $product['PRECIO'] ?>">
            </div>

            <button class="btn btn-dark">
                Actualizar producto
            </button>

        </form>

        <hr>

        <h5>Imágenes del producto</h5>

        <div class="row mb-3">

            <?php if (!empty($images)): ?>

                <?php foreach ($images as $img): ?>

                    <div class="col-md-3 mb-3">

                        <div class="card">

                            <img src="<?= $img['URL_IMAGE'] ?>"
                                class="card-img-top"
                                style="height:150px;object-fit:cover;">

                            <div class="card-body text-center">

                                <a href="<?= App::url('/admin/products/delete-image/' . $img['ID_IMAGEN']) ?>"
                                    class="btn btn-sm btn-danger btn-delete-image">
                                    Eliminar
                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p>No hay imágenes para este producto.</p>

            <?php endif; ?>

        </div>

        <form method="POST"
            enctype="multipart/form-data"
            action="<?= App::url('/admin/products/upload-image') ?>">

            <input type="hidden" name="product_id"
                value="<?= $product['ID_PRODUCTO'] ?>">

            <div class="mb-3">

                <input type="file"
                    name="image"
                    class="form-control"
                    required>

            </div>

            <button class="btn btn-outline-dark">
                Subir imagen
            </button>

        </form>

    </div>

</div>