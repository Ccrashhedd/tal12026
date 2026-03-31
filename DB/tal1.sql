CREATE DATABASE ecommerce;
USE ecommerse;  

CREATE TABLE PRODUCTO (
    idProducto BIGINT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    detalle TEXT NOT NULL,
    precio DECIMAL(20,4) NOT NULL
);