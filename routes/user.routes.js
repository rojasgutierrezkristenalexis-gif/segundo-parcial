import express from 'express';
// Importa tu modelo de usuario cuando lo necesites:
// import User from '../models/User.js'; 

const router = express.Router();

/**
 * Ruta: GET /api/users/perfil
 * Descripción: Obtiene la información del usuario actual.
 */
router.get('/perfil', (req, res) => {
    // Por ahora enviamos datos estáticos (mock)
    // Cuando conectes Mongoose, usarás algo como: 
    // const user = await User.findById(req.user.id);
    
    try {
        const usuario = {
            id: "1",
            nombre: "Administrador del Sistema",
            email: "administrador@uagrm.edu.bo",
            role: "admin",
            carrera: "Ingeniería Informática"
        };
        
        res.json({ success: true, user: usuario });
    } catch (error) {
        res.status(500).json({ success: false, message: "Error al obtener el perfil" });
    }
});

/**
 * Ruta: PUT /api/users/actualizar-perfil
 * Descripción: Permite al usuario editar su propia información.
 */
router.put('/actualizar-perfil', (req, res) => {
    // Aquí irá tu lógica para actualizar campos en la base de datos
    res.json({ success: true, message: "Perfil actualizado correctamente" });
});

export default router;