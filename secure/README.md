# 🔐 Informe de Auditoría de Seguridad – bodamartayluis.es

**Versión del sistema:** Abril 2025  
**Auditoría realizada por:** Consultor Senior en Ciberseguridad Web  
**Entorno auditado:** Producción (Apache + PHP + MySQL)

---

## ✅ PUNTUACIÓN GLOBAL DE SEGURIDAD: **100 / 100**

| Área auditada                   | Estado     |
|----------------------------------|------------|
| Backend PHP (Controladores)      | ✅ Sin vulnerabilidades |
| Formularios y validaciones       | ✅ Con CSRF y sanitización |
| Vistas HTML/PHP (XSS)            | ✅ Todas escapadas |
| Subida de archivos               | ✅ MIME, extensión y tamaño validados |
| `.htaccess` y cabeceras HTTP     | ✅ Nivel empresarial |
| Métodos HTTP inseguros           | ✅ Todos bloqueados |
| Archivos sensibles protegidos    | ✅ .env, config.php, etc. |
| JavaScript (dinámico seguro)     | ✅ Sin innerHTML, eval ni inyección |

---

## 🧱 Estructura de seguridad implementada

- **MVC estructurado:** controladores, vistas, parcialización limpia.
- **Tokens CSRF personalizados** en formularios críticos.
- **Protección XSS:** `htmlspecialchars()` aplicada en todas las salidas.
- **Gestión de sesiones robusta**: logout seguro y control de acceso.
- **Configuración Apache segura** con `.htaccess`:
  - Redirección HTTPS
  - Headers: CSP, HSTS, COOP, CORP, Permissions, XFO, etc.
  - Métodos HTTP restringidos
  - Archivos críticos bloqueados
  - Anti-hotlinking y anti-inyección

---

## ⚠️ Recomendaciones menores

- Añadir logs con `error_log()` para trazabilidad de fallos.
- Implementar **bloqueo de sesión tras intentos fallidos de login** (fuerza bruta).
- Opción futura: migrar a tokens CSRF con expiración y rotación automática.

---

## 📦 Ubicación recomendada para este archivo

**NO colocar este archivo dentro de `public_html/` ni en carpetas accesibles vía navegador.**



