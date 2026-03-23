<div class="admin-panel-card">

    <div class="admin-panel-card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">Editar producto</h4>

        <a href="<?= App::url('/admin/products') ?>" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

    </div>

    <div class="admin-panel-card-body">

        <form method="POST" action="<?= App::url('/admin/products/update') ?>">

            <input type="hidden" name="id" value="<?= $product['ID_PRODUCTO'] ?>">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">SKU</label>
                    <input type="text"
                        name="sku"
                        class="form-control"
                        value="<?= htmlspecialchars($product['SKU']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text"
                        name="nombre"
                        class="form-control"
                        value="<?= htmlspecialchars($product['NOMBRE']) ?>">
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>

                <textarea name="descripcion"
                    rows="4"
                    class="form-control"><?= htmlspecialchars($product['DESCRIPCION']) ?></textarea>
            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Precio</label>
                    <input type="number"
                        step="0.01"
                        name="precio"
                        class="form-control"
                        value="<?= $product['PRECIO'] ?>">
                </div>

            </div>

            <button class="btn btn-dark">
                <i class="bi bi-save"></i> Actualizar producto
            </button>

        </form>

        <hr class="my-4">

        <!-- IMÁGENES -->

        <h5 class="mb-3">Imágenes del producto</h5>

        <div class="row g-3 mb-4">

            <?php if (!empty($images)): ?>

                <?php foreach ($images as $img): ?>

                    <div class="col-md-3 col-6">

                        <div class="card">

                            <img src="<?= $img['URL_IMAGE'] ?>"
                                class="card-img-top"
                                style="height:150px;object-fit:cover;">

                            <div class="card-body p-2 text-center">

                                <a href="<?= App::url('/admin/products/delete-image/' . $img['ID_IMAGEN']) ?>"
                                    class="btn btn-sm btn-outline-danger btn-delete-image">

                                    <i class="bi bi-trash"></i>
                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p class="text-muted">No hay imágenes para este producto.</p>

            <?php endif; ?>

        </div>


        <!-- SUBIR IMAGEN -->

        <form method="POST"
            enctype="multipart/form-data"
            action="<?= App::url('/admin/products/upload-image') ?>">

            <input type="hidden"
                name="product_id"
                value="<?= $product['ID_PRODUCTO'] ?>">

            <div class="mb-3">

                <input type="file"
                    name="image"
                    class="form-control"
                    required>

            </div>

            <button class="btn btn-outline-dark">
                <i class="bi bi-upload"></i> Subir imagen
            </button>

        </form>

    </div>

</div>