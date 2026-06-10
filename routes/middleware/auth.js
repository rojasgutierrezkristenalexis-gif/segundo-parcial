// middleware/auth.js
import jwt from 'jsonwebtoken';

const SECRET_KEY = 'tu_clave_secreta_muy_segura'; // DEBE SER LA MISMA QUE EN LOGIN

export const verifyToken = (req, res, next) => {
    const token = req.headers['authorization'];

    if (!token) {
        return res.status(403).json({ message: "Acceso denegado. No hay token." });
    }

    // El token suele venir como "Bearer <token>"
    const bearerToken = token.split(' ')[1];

    try {
        const verified = jwt.verify(bearerToken, SECRET_KEY);
        req.user = verified;
        next(); // ¡Todo bien! Pasa a la siguiente función
    } catch (error) {
        res.status(401).json({ message: "Token inválido o expirado." });
    }
};