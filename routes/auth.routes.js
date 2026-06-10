import express from 'express';
import jwt from 'jsonwebtoken';

const router = express.Router();
const SECRET_KEY = 'tu_clave_secreta_muy_segura';

// Almacén en memoria para intentos fallidos
const loginAttempts = {};

router.post('/login', (req, res) => {
    const { email, password } = req.body;
    const now = Date.now();

    // 1. Verificación de bloqueo
    if (loginAttempts[email]) {
        const { count, blockedUntil } = loginAttempts[email];
        
        if (now < blockedUntil) {
            const remaining = Math.ceil((blockedUntil - now) / 1000);
            return res.status(429).json({ 
                message: `Demasiados intentos fallidos. Intenta de nuevo en ${remaining} segundos.` 
            });
        }
    }

    // 2. Validación de credenciales
    if (email === "administrador@uagrm.edu.bo" && password === "123456") {
        delete loginAttempts[email];
        const token = jwt.sign({ email, role: 'admin' }, SECRET_KEY, { expiresIn: '1h' });
        return res.json({ success: true, token, user: { email, role: 'admin' } });
    }

    // 3. Incrementamos el contador
    const attempt = loginAttempts[email] || { count: 0, blockedUntil: 0 };
    attempt.count += 1;

    // 4. Verificamos si ya alcanzó el límite
    if (attempt.count >= 3) {
        const multiplier = Math.pow(2, Math.floor(attempt.count / 3) - 1);
        attempt.blockedUntil = now + (30000 * multiplier);
        attempt.count = 0; 
        loginAttempts[email] = attempt;
        
        return res.status(429).json({ 
            message: "Has superado el límite de intentos. Tiempo de Espera." 
        });
    }

    loginAttempts[email] = attempt;

    const restantes = 3 - attempt.count;

    return res.status(401).json({ 
        message: `Credenciales incorrectas. Intentos restantes: ${restantes}` 
    });
}); // <--- ESTE PARÉNTESIS CERRABA LA RUTA Y FALTABA

export default router;