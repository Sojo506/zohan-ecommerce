USE zohan_tech_store;

START TRANSACTION;

-- =========================================================
-- Seed complementario del catalogo.
-- Ejecutar DESPUES de app/resources/inserts.sql
-- =========================================================

-- Usuarios extra para diversificar comentarios
INSERT INTO USUARIO_TB (
    IDENTIFICACION,
    NOMBRE,
    APELLIDO_PATERNO,
    APELLIDO_MATERNO,
    FECHA_REGISTRO,
    ID_DIRECCION,
    ID_TIPO_USUARIO,
    ID_ESTADO
) VALUES
('303330333', 'Daniel', 'Mora', 'Jimenez', '2026-03-05 12:15:00', 1, 2, 1),
('404440444', 'Valeria', 'Campos', 'Solis', '2026-03-06 15:20:00', 2, 2, 1);

INSERT INTO CORREO_TB (IDENTIFICACION, CORREO, ID_ESTADO) VALUES
('303330333', 'daniel.mora@zohantech.local', 1),
('404440444', 'valeria.campos@zohantech.local', 1);

INSERT INTO TELEFONO_TB (IDENTIFICACION, TELEFONO, ID_ESTADO) VALUES
('303330333', '88883333', 1),
('404440444', '88884444', 1);

INSERT INTO CUENTA_TB (
    ID_CUENTA,
    IDENTIFICACION,
    USERNAME,
    PASSWORD,
    INTENTOS_FALLIDOS,
    ULTIMO_LOGIN,
    ID_ESTADO
) VALUES
(3, '303330333', 'daniel.mora', '$2y$12$/YYW1cTt.9HxcpMS7p2ZouXqR/1FH2O6SL42RInzd/vsrCjazRrDy', 0, '2026-04-01 18:10:00', 1),
(4, '404440444', 'valeria.campos', '$2y$12$/YYW1cTt.9HxcpMS7p2ZouXqR/1FH2O6SL42RInzd/vsrCjazRrDy', 0, '2026-04-02 20:45:00', 1);

-- Marcas extra
INSERT INTO MARCA_TB (ID_MARCA, NOMBRE, ID_ESTADO) VALUES
(4, 'ASUS', 1),
(5, 'Acer', 1),
(6, 'Razer', 1),
(7, 'MSI', 1),
(8, 'LG', 1),
(9, 'Dell', 1),
(10, 'HyperX', 1),
(11, 'HP', 1);

-- Productos extra para cubrir y reforzar todas las categorias
INSERT INTO PRODUCTO_TB (
    ID_PRODUCTO,
    SKU,
    NOMBRE,
    DESCRIPCION,
    PRECIO,
    STOCK_MINIMO,
    ID_CATEGORIA,
    ID_MARCA,
    ID_ESTADO
) VALUES
(5, 'LAP-ASU-G14', 'ASUS ROG Zephyrus G14', 'Laptop gamer compacta con Ryzen 9, 32 GB RAM, SSD de 1 TB y pantalla de alta tasa de refresco.', 1599.00, 2, 1, 4, 1),
(6, 'LAP-ACE-SW5', 'Acer Swift 5', 'Ultrabook ligera para oficina y estudio con 16 GB RAM, SSD de 1 TB y chasis premium.', 1099.50, 3, 1, 5, 1),
(7, 'LAP-MSI-K15', 'MSI Katana 15', 'Laptop gamer con RTX dedicada, refrigeracion reforzada y pantalla Full HD de 144 Hz.', 1349.99, 2, 1, 7, 1),
(8, 'LAP-DEL-X13', 'Dell XPS 13', 'Laptop ultraligera con pantalla InfinityEdge, excelente bateria y acabado en aluminio.', 1249.00, 2, 1, 9, 1),
(9, 'MON-SAM-M8', 'Samsung Smart Monitor M8', 'Monitor 4K de 32 pulgadas con apps integradas, control remoto y conectividad USB-C.', 429.90, 3, 2, 3, 1),
(10, 'MON-LEN-R24', 'Lenovo Legion R24e', 'Monitor Full HD de 24 pulgadas con 180 Hz y baja latencia para juego competitivo.', 219.00, 4, 2, 1, 1),
(11, 'MON-LG-32UQ', 'LG UltraFine 32UQ', 'Monitor 4K IPS orientado a creadores con colores precisos y buena conectividad.', 519.00, 2, 2, 8, 1),
(12, 'MON-DEL-S272', 'Dell S2722DGM', 'Monitor curvo QHD de 27 pulgadas con 165 Hz para gaming y multimedia.', 289.99, 3, 2, 9, 1),
(13, 'KEY-RAZ-HMN', 'Razer Huntsman Mini', 'Teclado optico 60 por ciento para setups compactos y respuesta instantanea.', 129.95, 6, 3, 6, 1),
(14, 'MOU-LOG-MX3', 'Logitech MX Master 3S', 'Mouse inalambrico premium para productividad con desplazamiento magnetico silencioso.', 109.00, 5, 3, 2, 1),
(15, 'HDP-HYX-CL3', 'HyperX Cloud III', 'Headset con sonido envolvente, microfono claro y almohadillas comodas para largas sesiones.', 99.90, 6, 3, 10, 1),
(16, 'KEY-HP-HYPA', 'HP HyperX Alloy Origins', 'Teclado mecanico compacto con switches rapidos, estructura de aluminio y RGB.', 119.50, 5, 3, 10, 1);

-- Imagenes extra
INSERT INTO PRODUCTO_IMAGE_TB (ID_IMAGEN, ID_PRODUCTO, URL_IMAGE, ID_ESTADO) VALUES
(6, 5, 'https://via.placeholder.com/900x700?text=ASUS+ROG+Zephyrus+G14', 1),
(7, 5, 'https://via.placeholder.com/900x700?text=ASUS+ROG+G14+Abierta', 1),
(8, 6, 'https://via.placeholder.com/900x700?text=Acer+Swift+5', 1),
(9, 7, 'https://via.placeholder.com/900x700?text=MSI+Katana+15', 1),
(10, 8, 'https://via.placeholder.com/900x700?text=Dell+XPS+13', 1),
(11, 9, 'https://via.placeholder.com/900x700?text=Samsung+Smart+Monitor+M8', 1),
(12, 10, 'https://via.placeholder.com/900x700?text=Lenovo+Legion+R24e', 1),
(13, 11, 'https://via.placeholder.com/900x700?text=LG+UltraFine+32UQ', 1),
(14, 12, 'https://via.placeholder.com/900x700?text=Dell+S2722DGM', 1),
(15, 13, 'https://via.placeholder.com/900x700?text=Razer+Huntsman+Mini', 1),
(16, 14, 'https://via.placeholder.com/900x700?text=Logitech+MX+Master+3S', 1),
(17, 15, 'https://via.placeholder.com/900x700?text=HyperX+Cloud+III', 1),
(18, 16, 'https://via.placeholder.com/900x700?text=HyperX+Alloy+Origins', 1);

-- Inventario base para el catalogo extra
INSERT INTO INVENTARIO_TB (ID_INVENTARIO, ID_PRODUCTO, STOCK, ID_ESTADO) VALUES
(5, 5, 9, 1),
(6, 6, 12, 1),
(7, 7, 7, 1),
(8, 8, 10, 1),
(9, 9, 16, 1),
(10, 10, 18, 1),
(11, 11, 11, 1),
(12, 12, 14, 1),
(13, 13, 30, 1),
(14, 14, 22, 1),
(15, 15, 26, 1),
(16, 16, 19, 1);

-- Movimientos de inventario
INSERT INTO MOVIMIENTO_INVENTARIO_TB (
    ID_MOVIMIENTO,
    ID_PRODUCTO,
    ID_TIPO_MOVIMIENTO,
    CANTIDAD,
    MOTIVO,
    FECHA_MOVIMIENTO,
    ID_ESTADO
) VALUES
(7, 5, 1, 9, 'Carga inicial catalogo ampliado', '2026-04-01 09:00:00', 1),
(8, 6, 1, 12, 'Carga inicial catalogo ampliado', '2026-04-01 09:05:00', 1),
(9, 7, 1, 7, 'Carga inicial catalogo ampliado', '2026-04-01 09:10:00', 1),
(10, 8, 1, 10, 'Carga inicial catalogo ampliado', '2026-04-01 09:15:00', 1),
(11, 9, 1, 16, 'Carga inicial catalogo ampliado', '2026-04-01 09:20:00', 1),
(12, 10, 1, 18, 'Carga inicial catalogo ampliado', '2026-04-01 09:25:00', 1),
(13, 11, 1, 11, 'Carga inicial catalogo ampliado', '2026-04-01 09:30:00', 1),
(14, 12, 1, 14, 'Carga inicial catalogo ampliado', '2026-04-01 09:35:00', 1),
(15, 13, 1, 30, 'Carga inicial catalogo ampliado', '2026-04-01 09:40:00', 1),
(16, 14, 1, 22, 'Carga inicial catalogo ampliado', '2026-04-01 09:45:00', 1),
(17, 15, 1, 26, 'Carga inicial catalogo ampliado', '2026-04-01 09:50:00', 1),
(18, 16, 1, 19, 'Carga inicial catalogo ampliado', '2026-04-01 09:55:00', 1),
(19, 5, 2, 1, 'Venta mostrador abril', '2026-04-06 14:22:00', 1),
(20, 10, 2, 2, 'Venta mostrador abril', '2026-04-07 11:45:00', 1),
(21, 14, 2, 1, 'Venta web abril', '2026-04-08 20:15:00', 1),
(22, 15, 3, 2, 'Ajuste positivo por ingreso de proveedor', '2026-04-09 08:10:00', 1);

-- Comentarios para enriquecer ratings y reseñas
INSERT INTO COMENTARIO_TB (
    ID_COMENTARIO,
    ID_PRODUCTO,
    IDENTIFICACION,
    CALIFICACION,
    COMENTARIO,
    FECHA_COMENTARIO,
    ID_ESTADO
) VALUES
(3, 5, '202220222', 5, 'Muy potente para edicion y gaming. La pantalla se ve increible.', '2026-04-03 19:20:00', 1),
(4, 5, '303330333', 4, 'Excelente rendimiento, aunque se siente un poco caliente bajo carga.', '2026-04-04 10:05:00', 1),
(5, 6, '404440444', 5, 'Liviana, rapida y perfecta para llevar a la universidad.', '2026-04-05 08:30:00', 1),
(6, 6, '202220222', 4, 'La bateria rinde bastante y el teclado es comodo.', '2026-04-05 18:12:00', 1),
(7, 7, '303330333', 5, 'Corre juegos AAA sin problemas y el enfriamiento responde bien.', '2026-04-06 21:30:00', 1),
(8, 7, '404440444', 4, 'Buen equipo gamer, aunque el cargador es algo grande.', '2026-04-07 09:40:00', 1),
(9, 8, '202220222', 5, 'Muy premium. Ideal para trabajo remoto y viajes.', '2026-04-07 22:15:00', 1),
(10, 8, '303330333', 4, 'Pantalla excelente y muy buen acabado, pero el precio es alto.', '2026-04-08 07:55:00', 1),
(11, 9, '404440444', 5, 'Se ve elegante en escritorio y la calidad de imagen es buenisima.', '2026-04-08 13:10:00', 1),
(12, 9, '202220222', 4, 'Muy util para trabajar y ver contenido sin conectar tantos dispositivos.', '2026-04-08 21:05:00', 1),
(13, 10, '303330333', 5, 'Excelente monitor para shooters. Muy fluido y con buenos colores.', '2026-04-09 12:25:00', 1),
(14, 10, '404440444', 4, 'Relación calidad precio bastante buena para gaming.', '2026-04-09 20:50:00', 1),
(15, 11, '202220222', 5, 'Muy preciso en color para diseño y el panel IPS luce genial.', '2026-04-10 11:18:00', 1),
(16, 11, '303330333', 4, 'Ideal para productividad y multimedia. Base estable.', '2026-04-10 17:35:00', 1),
(17, 12, '404440444', 4, 'La curvatura ayuda bastante y el tiempo de respuesta convence.', '2026-04-11 09:12:00', 1),
(18, 12, '202220222', 5, 'Muy inmersivo para juegos y peliculas.', '2026-04-11 23:00:00', 1),
(19, 13, '303330333', 5, 'Teclado rapidisimo y perfecto para un setup pequeño.', '2026-04-12 14:45:00', 1),
(20, 13, '404440444', 4, 'Muy buen tacto y tamaño compacto. Me adapto rapido.', '2026-04-12 18:20:00', 1),
(21, 14, '202220222', 5, 'De los mejores mouse que he probado para trabajo diario.', '2026-04-12 20:05:00', 1),
(22, 14, '303330333', 5, 'Ergonomico y silencioso. La rueda mag speed es excelente.', '2026-04-13 08:30:00', 1),
(23, 15, '404440444', 4, 'Muy comodos y el microfono se escucha claro en llamadas.', '2026-04-13 10:25:00', 1),
(24, 15, '202220222', 5, 'Buen audio y no cansan aunque los use por horas.', '2026-04-13 16:15:00', 1),
(25, 16, '303330333', 4, 'Switches agradables y buena construccion general.', '2026-04-13 19:40:00', 1),
(26, 16, '404440444', 5, 'Muy firme, bonito RGB y tamaño ideal para escritorio pequeño.', '2026-04-13 21:30:00', 1);

-- Promo adicional sobre productos gamer / performance
INSERT INTO PROMOCION_TB (
    ID_PROMOCION,
    NOMBRE,
    DESCRIPCION,
    PORCENTAJE,
    FECHA_INICIO,
    FECHA_FIN,
    ID_ESTADO
) VALUES
(2, 'Setup Pro Abril', 'Descuento temporal para equipos gamer y perifericos premium.', 12.00, '2026-04-01', '2026-05-31', 1);

INSERT INTO PROMOCION_PRODUCTO_TB (ID_PROMOCION, ID_PRODUCTO, ID_ESTADO) VALUES
(2, 5, 1),
(2, 7, 1),
(2, 10, 1),
(2, 13, 1),
(2, 15, 1),
(2, 16, 1);

COMMIT;
