# Requerimientos del Proyecto de Prueba

## 1. Nombre del proyecto
Sistema de Prueba

## 2. Usuarios del sistema
- Administrador

## 3. Módulos o pantallas requeridas
- Pantalla de Inicio de Sesión (Login)

## 4. Detalle funcional por módulo o pantalla

### Módulo/Pantalla: Login

#### Usuario que la utilizará
Administrador

#### Objetivo
Permitir el acceso seguro al sistema mediante correo y contraseña.

#### Elementos que debe mostrar
- Un formulario centrado en la pantalla.
- Logo del sistema (marcador de posición).

#### Campos requeridos
| Campo | Tipo de dato | Obligatorio | Ejemplo |
|---|---|---|---|
| Correo Electrónico | Texto (Email) | Sí | admin@correo.com |
| Contraseña | Texto (Password) | Sí | ******** |

#### Botones o acciones
- Botón "Iniciar Sesión" (Debe validar los campos y enviar el formulario).

#### Flujo de funcionamiento
1. El usuario ingresa a la página.
2. Introduce su correo y contraseña.
3. Presiona "Iniciar Sesión".
4. Si los datos son correctos, se simula el ingreso (redirección a un archivo `dashboard.php`).

#### Mensajes del sistema
- Si falta un campo obligatorios: "Por favor, completa este campo."
- Si los datos son incorrectos: "El correo o la contraseña son incorrectos."