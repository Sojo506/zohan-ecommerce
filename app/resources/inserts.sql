USE zohan_tech_store;

START TRANSACTION;

-- Este seed respeta el requerimiento de dejar ESTADO_TB solo con Activo e Inactivo.
-- Nota: el flujo actual de registro/OTP en PHP referencia un estado pendiente con ID 3.
INSERT INTO ESTADO_TB (ID_ESTADO, NOMBRE) VALUES
(1, 'Activo'),
(2, 'Inactivo'),
(3, 'Pendiente');

-- Direcciones
INSERT INTO PAIS_TB (ID_PAIS, NOMBRE_PAIS, ID_ESTADO) VALUES
(1, 'Costa Rica', 1);

INSERT INTO PROVINCIA_TB (ID_PROVINCIA, NOMBRE_PROVINCIA, ID_PAIS, ID_ESTADO) VALUES
(1, 'San Jose', 1, 1);

INSERT INTO CANTON_TB (ID_CANTON, NOMBRE_CANTON, ID_PROVINCIA, ID_ESTADO) VALUES
(1, 'Escazu', 1, 1);

INSERT INTO DISTRITO_TB (ID_DISTRITO, NOMBRE_DISTRITO, ID_CANTON, ID_ESTADO) VALUES
(1, 'San Rafael', 1, 1),
(2, 'San Miguel', 1, 1);

INSERT INTO DIRECCION_TB (ID_DIRECCION, ID_DISTRITO, DETALLES, ID_ESTADO) VALUES
(1, 1, '200 metros norte de Multiplaza Escazu, edificio Zohan Tech', 1),
(2, 2, 'De la iglesia 150 metros este, casa color gris', 1);

-- Usuarios
-- Se usa ADMIN porque el panel administrativo valida ese literal en sesion.
INSERT INTO TIPO_USUARIO_TB (ID_TIPO_USUARIO, NOMBRE, ID_ESTADO) VALUES
(1, 'ADMIN', 1),
(2, 'CLIENTE', 1);

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
('101110111', 'Fabian', 'Sojo', 'Castro', '2026-03-01 09:00:00', 1, 1, 1),
('202220222', 'Maria', 'Lopez', 'Rojas', '2026-03-02 10:30:00', 2, 2, 1);

INSERT INTO CORREO_TB (IDENTIFICACION, CORREO, ID_ESTADO) VALUES
('101110111', 'admin@zohantech.local', 1),
('202220222', 'cliente@zohantech.local', 1);

INSERT INTO TELEFONO_TB (IDENTIFICACION, TELEFONO, ID_ESTADO) VALUES
('101110111', '88881111', 1),
('202220222', '88882222', 1);

INSERT INTO CUENTA_TB (
    ID_CUENTA,
    IDENTIFICACION,
    USERNAME,
    PASSWORD,
    INTENTOS_FALLIDOS,
    ULTIMO_LOGIN,
    ID_ESTADO
) VALUES
(1, '101110111', 'admin', '$2y$12$.q56p9Rdhuc13WGPHxeVVO5ysKemDb3Ovqjq2s8wUW9hQn.wENTrO', 0, '2026-03-31 08:00:00', 1),
(2, '202220222', 'maria.lopez', '$2y$12$/YYW1cTt.9HxcpMS7p2ZouXqR/1FH2O6SL42RInzd/vsrCjazRrDy', 0, '2026-03-30 19:45:00', 1);

-- Catalogo
INSERT INTO CATEGORIA_TB (ID_CATEGORIA, NOMBRE, ID_ESTADO) VALUES
(1, 'Laptops', 1),
(2, 'Monitores', 1),
(3, 'Perifericos', 1);

INSERT INTO MARCA_TB (ID_MARCA, NOMBRE, ID_ESTADO) VALUES
(1, 'Lenovo', 1),
(2, 'Logitech', 1),
(3, 'Samsung', 1);

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
(1, 'LAP-LOQ-15', 'Lenovo LOQ 15', 'Laptop gamer con procesador Intel Core i7, 16 GB RAM y SSD de 512 GB.', 899.99, 3, 1, 1, 1),
(2, 'MON-ODY-27', 'Samsung Odyssey G5 27', 'Monitor QHD de 27 pulgadas con 165 Hz para juego y productividad.', 249.90, 4, 2, 3, 1),
(3, 'MOU-G502-X', 'Logitech G502 X', 'Mouse ergonomico para gaming con sensor de alta precision.', 59.99, 8, 3, 2, 1),
(4, 'KEY-G915-TKL', 'Logitech G915 TKL', 'Teclado mecanico inalambrico tenkeyless con iluminacion RGB.', 179.90, 5, 3, 2, 1);

INSERT INTO PRODUCTO_IMAGE_TB (ID_IMAGEN, ID_PRODUCTO, URL_IMAGE, ID_ESTADO) VALUES
(1, 1, 'https://via.placeholder.com/900x700?text=Lenovo+LOQ+15', 1),
(2, 1, 'https://via.placeholder.com/900x700?text=Lenovo+LOQ+15+Lateral', 1),
(3, 2, 'https://via.placeholder.com/900x700?text=Samsung+Odyssey+G5', 1),
(4, 3, 'https://via.placeholder.com/900x700?text=Logitech+G502+X', 1),
(5, 4, 'https://via.placeholder.com/900x700?text=Logitech+G915+TKL', 1);

INSERT INTO COMENTARIO_TB (
    ID_COMENTARIO,
    ID_PRODUCTO,
    IDENTIFICACION,
    CALIFICACION,
    COMENTARIO,
    FECHA_COMENTARIO,
    ID_ESTADO
) VALUES
(1, 1, '202220222', 5, 'Excelente rendimiento y muy buena relacion precio valor.', '2026-03-22 11:15:00', 1),
(2, 3, '202220222', 4, 'Muy comodo para sesiones largas y responde bastante bien.', '2026-03-23 16:40:00', 1);

-- Inventario
INSERT INTO INVENTARIO_TB (ID_INVENTARIO, ID_PRODUCTO, STOCK, ID_ESTADO) VALUES
(1, 1, 14, 1),
(2, 2, 20, 1),
(3, 3, 38, 1),
(4, 4, 25, 1);

INSERT INTO TIPO_MOVIMIENTO_TB (ID_TIPO_MOVIMIENTO, NOMBRE, ID_ESTADO) VALUES
(1, 'Entrada', 1),
(2, 'Venta', 1),
(3, 'Ajuste', 1);

INSERT INTO MOVIMIENTO_INVENTARIO_TB (
    ID_MOVIMIENTO,
    ID_PRODUCTO,
    ID_TIPO_MOVIMIENTO,
    CANTIDAD,
    MOTIVO,
    FECHA_MOVIMIENTO,
    ID_ESTADO
) VALUES
(1, 1, 1, 15, 'Carga inicial de inventario', '2026-03-01 09:30:00', 1),
(2, 2, 1, 20, 'Carga inicial de inventario', '2026-03-01 09:35:00', 1),
(3, 3, 1, 40, 'Carga inicial de inventario', '2026-03-01 09:40:00', 1),
(4, 4, 1, 25, 'Carga inicial de inventario', '2026-03-01 09:45:00', 1),
(5, 1, 2, 1, 'Venta #1 pagada con PayPal', '2026-03-20 14:31:00', 1),
(6, 3, 2, 2, 'Venta #1 pagada con PayPal', '2026-03-20 14:31:00', 1);

-- Venta y facturacion
INSERT INTO VENTA_TB (ID_VENTA, ID_CUENTA, FECHA_VENTA, ID_ESTADO) VALUES
(1, 2, '2026-03-20 14:30:00', 1);

INSERT INTO VENTA_PRODUCTO_TB (ID_VENTA, ID_PRODUCTO, CANTIDAD, PRECIO) VALUES
(1, 1, 1, 899.99),
(1, 3, 2, 59.99);

INSERT INTO FACTURA_TB (
    ID_FACTURA,
    ID_VENTA,
    IMPUESTO,
    SUBTOTAL,
    TOTAL,
    FECHA_FACTURA,
    ID_ESTADO
) VALUES
(1, 1, 132.60, 1019.97, 1152.57, '2026-03-20 14:32:00', 1);

-- Promociones
INSERT INTO PROMOCION_TB (
    ID_PROMOCION,
    NOMBRE,
    DESCRIPCION,
    PORCENTAJE,
    FECHA_INICIO,
    FECHA_FIN,
    ID_ESTADO
) VALUES
(1, 'Semana Gamer', 'Promocion especial para equipo gamer seleccionado.', 15.00, '2025-01-01', '2030-12-31', 1);

INSERT INTO PROMOCION_PRODUCTO_TB (ID_PROMOCION, ID_PRODUCTO, ID_ESTADO) VALUES
(1, 1, 1),
(1, 3, 1);

INSERT INTO CUPON_DESCUENTO_TB (
    ID_CUPON,
    CODIGO,
    PORCENTAJE,
    FECHA_INICIO,
    FECHA_FIN,
    USO_MAXIMO,
    ID_ESTADO
) VALUES
(1, 'BIENVENIDO10', 10.00, '2025-01-01', '2030-12-31', 50, 1);

-- Pagos
INSERT INTO MONEDA_TB (ID_MONEDA, NOMBRE, ID_ESTADO) VALUES
(1, 'USD', 1);

INSERT INTO PAGO_PAYPAL_TB (
    ID_PAGO,
    ID_FACTURA,
    PAYPAL_ORDER_ID,
    PAYPAL_CAPTURE_ID,
    TOTAL,
    ID_MONEDA,
    FECHA_REGISTRO,
    ID_ESTADO
) VALUES
(1, 1, 'PAYPAL-ORDER-0001', 'PAYPAL-CAPTURE-0001', 1152.57, 1, '2026-03-20 14:33:00', 1);

-- Seguridad
INSERT INTO REFRESH_TOKEN_TB (
    ID_REFRESH_TOKEN,
    ID_CUENTA,
    TOKEN_HASH,
    JTI,
    ISSUED_AT,
    EXPIRES_AT,
    REVOKED_AT,
    IP,
    USER_AGENT,
    ID_ESTADO
) VALUES
(1, 1, '9dce8d1cb7424df0f1b7f2c6f5f2fdb6d1ae95f8a4f4c84d4b88b1ee8a441001', 'jti-admin-0001', '2026-03-31 08:00:00', '2026-04-07 08:00:00', NULL, '127.0.0.1', 'Seed script admin session', 1),
(2, 2, '3ac8cfa48926f2ecf4104d6b9b8bb8b5b5997b2d3fc8138d269d7f74f5af1102', 'jti-cliente-0001', '2026-03-30 19:45:00', '2026-04-06 19:45:00', NULL, '127.0.0.1', 'Seed script customer session', 1);

INSERT INTO TIPO_OTP_TB (ID_TIPO_OTP, NOMBRE, ID_ESTADO) VALUES
(1, 'Activacion de cuenta', 1),
(2, 'Cambio de password', 1),
(3, 'Cambio de correo', 1);

INSERT INTO CODIGO_OTP_TB (
    OTP_CODE,
    ID_CUENTA,
    ID_TIPO_OTP,
    HASH,
    EXPIRES_AT,
    INTENTOS,
    CREATED_AT,
    ACTIVE_FLAG,
    ID_ESTADO
) VALUES
('123456', 2, 1, '$2y$12$HFGkFLvY/GFhggD5/vdsVenO82R9dohNXsLa7GbThufisUm7ER3RS', '2026-03-02 10:40:00', 0, '2026-03-02 10:30:00', 0, 1),
('654321', 1, 2, '$2y$12$LuP5vywhtjwhOJwQPdCW..fTOwI/5NUlel0lQuG6VQ6pOG93PFENy', '2026-03-31 08:10:00', 0, '2026-03-31 08:00:00', 0, 1);

COMMIT;
